<?php

namespace App\Services;

class DocxQuestionParser extends TxtQuestionParser
{
    public function parseAndImport($filePath, $subjectId)
    {
        $text = $this->readDocx($filePath);
        
        if (empty(trim($text))) {
            throw new \Exception("File DOCX kosong atau format tidak didukung.");
        }

        return $this->parseTextAndImport($text, $subjectId);
    }

    private function readDocx($filename)
    {
        $zip = new \ZipArchive();
        if ($zip->open($filename) === true) {
            if (($index = $zip->locateName("word/document.xml")) !== false) {
                $data = $zip->getFromIndex($index);
                $zip->close();
                
                $dom = new \DOMDocument();
                $dom->loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                
                $text = "";
                // Ekstrak semua paragraf (menggunakan nama elemen XML Word)
                $paragraphs = $dom->getElementsByTagName("p");
                foreach ($paragraphs as $p) {
                    $text .= $p->textContent . "\n";
                }
                
                return $text;
            }
            $zip->close();
        }
        throw new \Exception("Bukan file DOCX yang valid atau file rusak.");
    }
}