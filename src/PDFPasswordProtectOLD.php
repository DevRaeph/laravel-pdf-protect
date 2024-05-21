<?php
/*
*       ____                        __                    _
*      / __ \  ___  _   __  ___    / /  ____    ____ _   (_)   _  __
*     / / / / / _ \| | / / / _ \  / /  / __ \  / __ `/  / /   | |/_/
*    / /_/ / /  __/| |/ / /  __/ / /  / /_/ / / /_/ /  / /   _>  <
*   /_____/  \___/ |___/  \___/ /_/   \____/  \__, /  /_/   /_/|_|
*                                         /____/
*  ___________________________________________________________________
* | Author:     Develogix Agency e.U. - Raphael Planer
* | E-Mail:     office@develogix.at
* | Project:    laravel-pdf-protect
* | Filename:   PDFPasswordProtect.php
* | Created:    12.06.2023 (12:56:02)
* | Copyright (C) 2023 Develogix Agency e.U. All Rights Reserved
* | Website:    https://develogix.at
*/

namespace DevRaeph\PDFPasswordProtect;

use DevRaeph\PDFPasswordProtect\Exceptions\InputFileNotFoundException;
use DevRaeph\PDFPasswordProtect\Exceptions\InputFileNotSetException;
use DevRaeph\PDFPasswordProtect\Exceptions\OutputFileNotSetException;
use DevRaeph\PDFPasswordProtect\Exceptions\PasswordNotSetException;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;

/**
 * PDFPassword Protect
 * Use the setInputFile, setOutputFile and setPassword methods to add the necessary parameters.
 * Afterwords call the secure method to finalize the protected PDF.
 */
class PDFPasswordProtectOLD
{
    protected string $inputFile;

    protected string $outputFile;

    protected string $password;

    protected string $ownerPassword;

    protected string $mode = 'utf-8';

    protected array|string $format = 'auto';

    /**
     * This function will append the file path to the facade. Support is
     * storage_path() atm. WIP is Storage::class and s3 support.
     * @param string $inputFile
     * @return PDFPasswordProtect
     * @throws InputFileNotFoundException
     */
    public function setInputFile(string $inputFile): PDFPasswordProtectOLD
    {
        try {
            fopen($inputFile, 'r');
        } catch (\Exception $exception) {
            throw new InputFileNotFoundException();
        }
        $this->inputFile = $inputFile;

        return $this;
    }

    /**
     * This function will define the output path. Same as InputFile with storage_path().
     * Storage::class and s3 support will come later this year.
     * @param string $outputFile
     * @return PDFPasswordProtect
     */
    public function setOutputFile(string $outputFile): PDFPasswordProtectOLD
    {
        $this->outputFile = $outputFile;

        return $this;
    }

    /**
     * This function will set the password for the encrypted PDF file.
     * @param string $password
     * @return PDFPasswordProtect
     */
    public function setPassword(string $password): PDFPasswordProtectOLD
    {
        $this->password = $password;

        return $this;
    }

    /**
     * This function will define a separate Owner password for the file.
     * Only the Owner can modify and edit this file.
     * @param string $ownerPassword
     * @return PDFPasswordProtect
     */
    public function setOwnerPassword(string $ownerPassword): PDFPasswordProtectOLD
    {
        $this->ownerPassword = $ownerPassword;

        return $this;
    }

    /**
     * Mode of the Document.
     * Default 'utf-8'.
     * Possible values are country codes for example: 'en-GB', 'en_GB' or 'en'.
     * @param string $mode
     * @return PDFPasswordProtect
     */
    public function setMode(string $mode): PDFPasswordProtectOLD
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Format of the Document.
     * Default 'auto', is using the input file values.
     * Possible values are 'A0', 'A4', 'Letter', etc.
     * For more possible values check https://mpdf.github.io/reference/mpdf-functions/construct.html
     * @param string|array $format
     * @return PDFPasswordProtect
     */
    public function setFormat(array|string $format): PDFPasswordProtectOLD
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws InputFileNotSetException
     * @throws PdfTypeException
     */
    private function getPageSize(): array
    {
        $mpdf = new \Mpdf\Mpdf();
        if (! isset($this->inputFile)) {
            throw new InputFileNotSetException();
        }
        $mpdf->setSourceFile($this->inputFile);
        $getFirstPage = $mpdf->importPage(1);

        return $mpdf->getTemplateSize($getFirstPage);
    }

    /**
     * Secure will create the final protected PDF.
     *
     * @throws CrossReferenceException
     * @throws InputFileNotSetException
     * @throws MpdfException
     * @throws OutputFileNotSetException
     * @throws PasswordNotSetException
     * @throws PdfParserException
     * @throws PdfTypeException
     */
    public function secure(): void
    {
        if (! isset($this->inputFile)) {
            throw new InputFileNotSetException();
        }

        if (! isset($this->outputFile)) {
            throw new OutputFileNotSetException();
        }

        if (! isset($this->password)) {
            throw new PasswordNotSetException();
        }

        $pageSize = $this->getPageSize();
        $export = new \Mpdf\Mpdf([
            'mode' => $this->mode,
            'format' => ($this->format == 'auto')
                ? [$pageSize['width'], $pageSize['height']]
                : (($pageSize['width'] < $pageSize['height']) ? $this->format : $this->format.'-L'),
        ]);

        $pagecount = $export->setSourceFile($this->inputFile);
        for ($p = 1; $p <= $pagecount; $p++) {
            $tplId = $export->importPage($p);
            $wh = $export->getTemplateSize($tplId);
            if (($p == 1)) {
                $export->state = 0;
                $export->AddPage($wh['width'] > $wh['height'] ? 'L' : 'P');
                $export->UseTemplate($tplId);
            } else {
                $export->state = 1;
                $export->AddPage($wh['width'] > $wh['height'] ? 'L' : 'P');
                $export->UseTemplate($tplId);
            }
        }

        //set owner password to user password if null
        $ownerPassword = (isset($this->ownerPassword)) ? $this->ownerPassword : $this->password;
        $export->SetProtection(['copy', 'print'], $this->password, $ownerPassword);

        $export->Output($this->outputFile, Destination::FILE);
    }

    /**
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException*@throws InputFileNotSetException
     *
     * @deprecated deprecated since version 2.0, check the docs
     */
    public function encrypt($inputFile, $outputFile, $password, $ownerPassword = null): void
    {
        $pageSize = $this->getPageSize();
        $export = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [$pageSize['width'], $pageSize['height']]]);
        $pagecount = $export->setSourceFile($inputFile);

        for ($p = 1; $p <= $pagecount; $p++) {
            $tplId = $export->importPage($p);
            $wh = $export->getTemplateSize($tplId);
            if (($p == 1)) {
                $export->state = 0;
                $export->AddPage($wh['width'] > $wh['height'] ? 'L' : 'P');
                $export->UseTemplate($tplId);
            } else {
                $export->state = 1;
                $export->AddPage($wh['width'] > $wh['height'] ? 'L' : 'P');
                $export->UseTemplate($tplId);
            }
        }

        //set owner password to user password if null
        $ownerPassword = is_null($ownerPassword) ? $password : $ownerPassword;
        $export->SetProtection(['copy', 'print'], $password, $ownerPassword);

        $export->Output($outputFile, Destination::FILE);
    }
}
