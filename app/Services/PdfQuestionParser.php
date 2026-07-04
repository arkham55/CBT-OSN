<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use App\Models\Question;
use Illuminate\Support\Facades\Log;

class PdfQuestionParser
{
    public function parseAndImport($filePath, $subjectId)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $text = $pdf->getText();

        // 1. Split text into blocks by question numbers (e.g. "1. ", "2. ")
        // This is a naive regex but works for the standard OSN PDF format.
        // It looks for a newline, a number, a dot, and a space.
        $blocks = preg_split('/^(\d+)\.\s/m', $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        
        $importedCount = 0;

        // $blocks will be: [0] => preamble text, [1] => "1", [2] => question 1 text, [3] => "2", [4] => question 2 text...
        // If the first block is not a number, we start from index 1.
        $startIndex = (is_numeric(trim($blocks[0] ?? ''))) ? 0 : 1;

        for ($i = $startIndex; $i < count($blocks); $i += 2) {
            if (!isset($blocks[$i + 1])) continue;

            $number = $blocks[$i];
            $rawContent = $blocks[$i + 1];

            // 2. Extract options (A, B, C, D)
            // Look for A. B. C. D. at the start of a line
            $optionRegex = '/^([A-E])\.\s(.*?)(?=\n^[A-E]\.\s|\n^Alasan jawaban:|\z)/ms';
            preg_match_all($optionRegex, $rawContent, $optionMatches, PREG_SET_ORDER);

            $options = ['A' => '-', 'B' => '-', 'C' => '-', 'D' => '-', 'E' => '-'];
            foreach ($optionMatches as $match) {
                $letter = $match[1];
                $options[$letter] = trim($match[2]);
            }

            // 3. Extract the question content (everything before the first option or explanation)
            $contentSplit = preg_split('/^([A-E])\.\s|^Alasan jawaban:/m', $rawContent);
            $questionText = trim($contentSplit[0]);

            // 4. Extract explanation ("Alasan jawaban:")
            $explanation = null;
            if (preg_match('/^Alasan jawaban:\s*(.*?)(?=\z)/ms', $rawContent, $expMatch)) {
                $explanation = trim($expMatch[1]);
            }

            // 5. Smart Answer Guesser
            $correctOption = 'A'; // Default
            if ($explanation) {
                $lowerExp = strtolower($explanation);
                // Look for direct mentions like "(jawaban B)" or "pilihan B"
                if (preg_match('/\(jawaban ([a-e])\)/i', $explanation, $ansMatch)) {
                    $correctOption = strtoupper($ansMatch[1]);
                } elseif (preg_match('/pilihan ([a-e])/i', $explanation, $ansMatch)) {
                    $correctOption = strtoupper($ansMatch[1]);
                } else {
                    // Try to match the text of the options with the text in the explanation
                    // For each option, check if a significant part of its text is in the explanation
                    foreach ($options as $key => $val) {
                        if (strlen($val) > 10 && strpos($lowerExp, strtolower(substr($val, 0, 20))) !== false) {
                            $correctOption = $key;
                            break;
                        }
                    }
                }
            }

            // 6. Save to DB
            if (!empty($questionText)) {
                Question::create([
                    'subject_id' => $subjectId,
                    'content' => $questionText,
                    'option_a' => $options['A'],
                    'option_b' => $options['B'],
                    'option_c' => $options['C'],
                    'option_d' => $options['D'],
                    'option_e' => $options['E'],
                    'correct_option' => $correctOption,
                    'explanation' => $explanation,
                ]);
                $importedCount++;
            }
        }

        return $importedCount;
    }
}
