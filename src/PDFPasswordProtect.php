<?php

namespace DevRaeph\PDFPasswordProtect;

class PDFPasswordProtect
{
    /**
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     */
    public function encrypt($inputFile, $outputFile, $password, $ownerPassword = null)
    {
        $mpdf = new \Mpdf\Mpdf();

        $pagecount = $mpdf->setSourceFile($inputFile);

        for ($p = 1; $p <= $pagecount; $p++) {
            $tplId = $mpdf->importPage($p);
            $wh = $mpdf->getTemplateSize($tplId);
            if (($p == 1)) {
                $mpdf->state = 0;
                $mpdf->AddPage($wh['width'] > $wh['height'] ? 'L' : 'P');
                $mpdf->UseTemplate($tplId);
            } else {
                $mpdf->state = 1;
                $mpdf->AddPage($wh['width'] > $wh['height'] ? 'L' : 'P');
                $mpdf->UseTemplate($tplId);
            }
        }

        //set owner password to user password if null
        $ownerPassword = is_null($ownerPassword) ? $password : $ownerPassword;

        $mpdf->SetProtection(['copy', 'print'], $password, $ownerPassword);

        $mpdf->Output($outputFile, \Mpdf\Output\Destination::FILE, );
    }
}
