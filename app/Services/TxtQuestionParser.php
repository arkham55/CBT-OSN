<?php

namespace App\Services;

use App\Models\Question;

class TxtQuestionParser
{
    public function parseAndImport($filePath, $subjectId)
    {
        $rawBytes = file_get_contents($filePath);
        // Konversi otomatis ke UTF-8 untuk menghindari error MySQL 1366 (Incorrect string value)
        $text = mb_convert_encoding($rawBytes, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
        return $this->parseTextAndImport($text, $subjectId);
    }

    public function parseTextAndImport($text, $subjectId)
    {
        // Prevent PHP from timing out during long AI processing
        set_time_limit(0);

        // Normalize line endings to standard \n
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Split text into blocks by question numbers (e.g. "1. ", " 2. ", "82.", "82)")
        $blocks = preg_split('/^\s*(\d+)[\.\)]\s*/m', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        $parsedQuestions = [];
        $pendingInstruction = trim($blocks[0] ?? ''); // Text before the first question

        for ($i = 1; $i < count($blocks); $i += 2) {
            if (!isset($blocks[$i + 1])) continue;

            $number = $blocks[$i];
            $rawContent = $blocks[$i + 1];

            // Extract trailing instruction for the NEXT question(s)
            $trailingInstruction = '';
            if (preg_match('/\n\s*((?:Untuk\s+(?:menjawab|soal)|SOAL\s*OSN).*)$/msi', $rawContent, $trailingMatch)) {
                $trailingInstruction = trim($trailingMatch[1]);
            }

            // Force options (A, B, C, D, E) to start on a new line if they are inline
            $rawContent = preg_replace('/(?<=\s|^)([a-e])[\.\)\,]\s+/i', "\n$1. ", $rawContent);

            // Extract the question content first
            $contentSplit = preg_split('/^\s*([a-e])[\.\)\,]\s*|^\s*Alasan jawaban:|^\s*Kunci|^\s*(?:Untuk\s+(?:menjawab|soal)|SOAL\s*OSN)/mi', $rawContent);
            $questionText = trim($contentSplit[0]);

            // The remaining text after the question is the options block
            $optionsBlock = substr($rawContent, strlen($contentSplit[0]));

            // Extract options (A, B, C, D, E) from the options block
            // Allow case-insensitive, leading spaces, brackets, and horizontal formatting.
            $optionRegex = '/(?:^|\s+)([a-e])[\.\)\,]\s*(.*?)(?=\s+[a-e][\.\)\,]\s+|\n\s*Alasan jawaban:|\n\s*Kunci|\n\s*(?:Untuk\s+(?:menjawab|soal)|SOAL\s*OSN)|\z)/msi';
            preg_match_all($optionRegex, $optionsBlock, $optionMatches, PREG_SET_ORDER);

            $options = ['A' => '-', 'B' => '-', 'C' => '-', 'D' => '-', 'E' => '-'];
            foreach ($optionMatches as $match) {
                $letter = strtoupper($match[1]);
                $options[$letter] = trim($match[2]);
            }

            // Prepend any pending instructions to this question
            if (!empty($pendingInstruction)) {
                $questionText = $pendingInstruction . "\n\n" . $questionText;
                $pendingInstruction = ''; // Clear it after using
            }

            // Set pending instruction for the NEXT loop iteration
            if (!empty($trailingInstruction)) {
                $pendingInstruction = $trailingInstruction;
            }

            // Extract explanation or key
            $explanation = null;
            if (preg_match('/^\s*(?:Alasan jawaban|Kunci jawaban|Kunci):\s*(.*?)(?=\n^\s*Untuk\s*soal|\z)/msi', $rawContent, $expMatch)) {
                $explanation = trim($expMatch[1]);
            }

            // Smart fill for SBMPTN style questions (1), (2), (3) that lack A,B,C,D
            if ($options['A'] === '-' && preg_match('/\(1\).*?\(2\).*?\(3\)/s', $questionText)) {
                $options['A'] = '1, 2, dan 3 benar';
                $options['B'] = '1 dan 2 saja yang benar';
                $options['C'] = '2 dan 3 saja yang benar';
                $options['D'] = '3 saja yang benar';
            }

            // Smart Answer Guesser
            $correctOption = 'A'; // Default
            if ($explanation) {
                $lowerExp = strtolower($explanation);
                if (preg_match('/\(jawaban ([a-e])\)/i', $explanation, $ansMatch)) {
                    $correctOption = strtoupper($ansMatch[1]);
                } elseif (preg_match('/pilihan ([a-e])/i', $explanation, $ansMatch)) {
                    $correctOption = strtoupper($ansMatch[1]);
                } else {
                    foreach ($options as $key => $val) {
                        if (strlen($val) > 10 && strpos($lowerExp, strtolower(substr($val, 0, 20))) !== false) {
                            $correctOption = $key;
                            break;
                        }
                    }
                }
            }

            // Save to temporary array instead of DB directly
            if (!empty($questionText)) {
                $parsedQuestions[] = [
                    'content' => $questionText,
                    'option_a' => $options['A'],
                    'option_b' => $options['B'],
                    'option_c' => $options['C'],
                    'option_d' => $options['D'],
                    'option_e' => $options['E'],
                    'correct_option' => $correctOption,
                    'explanation' => $explanation,
                ];
            }
        }

        return $this->processWithGeminiAndSave($parsedQuestions, $subjectId);
    }

    private function processWithGeminiAndSave(array $questions, $subjectId)
    {
        $apiKey = config('services.gemini.api_key');
        
        // If no API key or empty array, just save directly
        if (empty($apiKey) || empty($questions)) {
            return $this->saveQuestionsToDb($questions, $subjectId);
        }

        // Process in chunks to avoid huge payloads, truncation, and skipped items by the AI
        $chunkSize = 15;
        $chunks = array_chunk($questions, $chunkSize);
        $totalImported = 0;

        foreach ($chunks as $chunk) {
            $prompt = "Anda adalah Guru Biologi dan asisten ujian yang ahli. Berikut adalah array JSON berisi soal-soal pilihan ganda berbahasa Indonesia. Tugas Anda:\n";
            $prompt .= "1. Perbaiki salah eja (typo) pada 'content' dan 'option_a' sampai 'option_e' (misal: 'Crause' menjadi 'Krause'). Rapikan spasi berlebih dan tanda baca.\n";
            $prompt .= "2. BACA SOALNYA: 'correct_option' saat ini adalah kunci jawaban sementara dari file. HARAP PATUHI DAN PERTAHANKAN huruf pada 'correct_option' tersebut HANYA JIKA memang memungkinkan dan masuk akal secara keilmuan (jangan diubah kecuali kunci aslinya sama sekali tidak masuk akal atau kosong).\n";
            $prompt .= "3. Tuliskan penjelasan singkat padat mengapa 'correct_option' tersebut adalah jawaban yang tepat (fokuskan argumen untuk membenarkan kunci jawaban tersebut) pada key 'explanation'.\n";
            $prompt .= "4. JANGAN ubah nama-nama key JSON. Kembalikan HANYA teks JSON ARRAY murni (tanpa format ```json atau markdown lainnya).\n\n";
            $prompt .= json_encode($chunk);

            try {
                $response = \Illuminate\Support\Facades\Http::timeout(180)->withoutVerifying()->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                    [
                        'contents' => [
                            [
                                'parts' => [['text' => $prompt]]
                            ]
                        ]
                    ]
                );

                if ($response->status() === 429) {
                    // Coba baca berapa detik kita disuruh menunggu oleh Google
                    $delay = 65; // Default aman 65 detik
                    $errorData = $response->json();
                    if (isset($errorData['error']['details'])) {
                        foreach ($errorData['error']['details'] as $detail) {
                            if (isset($detail['retryDelay'])) {
                                $delay = (int) str_replace('s', '', $detail['retryDelay']) + 2; // Tambah 2 detik untuk jaga-jaga
                                break;
                            }
                        }
                    }
                    
                    // Tunggu sesuai permintaan Google
                    sleep($delay);
                    
                    $response = \Illuminate\Support\Facades\Http::timeout(180)->withoutVerifying()->post(
                        "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                        [
                            'contents' => [
                                [
                                    'parts' => [['text' => $prompt]]
                                ]
                            ]
                        ]
                    );
                }

                if ($response->successful()) {
                    $responseData = $response->json();
                    if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                        $aiText = $responseData['candidates'][0]['content']['parts'][0]['text'];
                        
                        // Clean markdown block if Gemini still returns it
                        $aiText = trim(str_replace(['```json', '```'], '', $aiText));
                        
                        $aiFixedQuestions = json_decode($aiText, true);

                        // If decoding succeeds and it's an array, save it
                        if (is_array($aiFixedQuestions)) {
                            $totalImported += $this->saveQuestionsToDb($aiFixedQuestions, $subjectId);
                            continue;
                        } else {
                            \Illuminate\Support\Facades\Log::error("JSON Decode failed. AI Output: " . $aiText);
                        }
                    } else {
                        \Illuminate\Support\Facades\Log::error("Unexpected Gemini response structure: " . json_encode($responseData));
                    }
                } else {
                    \Illuminate\Support\Facades\Log::error("Gemini HTTP Error: " . $response->status() . " - " . $response->body());
                }
                
                // Fallback: If AI fails or JSON is malformed, save original chunk
                \Illuminate\Support\Facades\Log::warning("AI Processing failed for a chunk, saving original.");
                $totalImported += $this->saveQuestionsToDb($chunk, $subjectId);

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Gemini API Error: " . $e->getMessage());
                // Fallback to original
                $totalImported += $this->saveQuestionsToDb($chunk, $subjectId);
            }
        }

        return $totalImported;
    }

    private function saveQuestionsToDb(array $questions, $subjectId)
    {
        $count = 0;
        foreach ($questions as $q) {
            if (!empty($q['content'])) {
                Question::create([
                    'subject_id' => $subjectId,
                    'content' => $q['content'] ?? '',
                    'option_a' => $q['option_a'] ?? '-',
                    'option_b' => $q['option_b'] ?? '-',
                    'option_c' => $q['option_c'] ?? '-',
                    'option_d' => $q['option_d'] ?? '-',
                    'option_e' => $q['option_e'] ?? '-',
                    'correct_option' => $q['correct_option'] ?? 'A',
                    'explanation' => $q['explanation'] ?? null,
                ]);
                $count++;
            }
        }
        return $count;
    }
}
