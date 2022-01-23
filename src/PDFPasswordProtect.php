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
        $tplId = $mpdf->ImportPage($pagecount);
        $mpdf->UseTemplate($tplId);

        //set owner password to user password if null
        $ownerPassword = is_null($ownerPassword) ? $password : $ownerPassword;

        $mpdf->SetProtection(array('copy', 'print'), $password, $ownerPassword);

        $mpdf->Output($outputFile,\Mpdf\Output\Destination::FILE,);
    }
}
