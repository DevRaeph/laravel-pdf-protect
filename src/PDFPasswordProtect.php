<?php

namespace DevRaeph\PDFPasswordProtect;

use DevRaeph\PDFPasswordProtect\Exceptions\InputFileNotFoundException;
use DevRaeph\PDFPasswordProtect\Exceptions\InputFileNotSetException;
use DevRaeph\PDFPasswordProtect\Exceptions\OutputFileNotSetException;
use DevRaeph\PDFPasswordProtect\Exceptions\PasswordNotSetException;
use Illuminate\Support\Facades\Storage;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;

/**
 * PDFPasswordProtect
 * Use the setInputFile, setOutputFile, setPassword methods to add the necessary parameters.
 * Afterwards call the secure method to finalize the protected PDF.
 */
class PDFPasswordProtect
{
    protected string $inputFile;

    protected string $outputFile;

    protected string $password;

    protected ?string $ownerPassword = null;

    protected string $mode = 'utf-8';

    protected array|string $format = 'auto';

    protected string $inputDisk;

    protected string $outputDisk;

    /**
     * Set the input file and disk.
     *
     * @param string $inputFile
     * @param string $disk
     * @return PDFPasswordProtect
     * @throws InputFileNotFoundException
     */
    public function setInputFile(string $inputFile, string $disk = 'local'): PDFPasswordProtect
    {
        if (! Storage::disk($disk)->exists($inputFile)) {
            throw new InputFileNotFoundException();
        }
        $this->inputFile = $inputFile;
        $this->inputDisk = $disk;

        return $this;
    }

    /**
     * Set the output file and disk.
     *
     * @param string $outputFile
     * @param string $disk
     * @return PDFPasswordProtect
     */
    public function setOutputFile(string $outputFile, string $disk = 'local'): PDFPasswordProtect
    {
        $this->outputFile = $outputFile;
        $this->outputDisk = $disk;

        return $this;
    }

    /**
     * Set the password for the PDF.
     *
     * @param string $password
     * @return PDFPasswordProtect
     */
    public function setPassword(string $password): PDFPasswordProtect
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set the owner password for the PDF.
     *
     * @param string $ownerPassword
     * @return PDFPasswordProtect
     */
    public function setOwnerPassword(string $ownerPassword): PDFPasswordProtect
    {
        $this->ownerPassword = $ownerPassword;

        return $this;
    }

    /**
     * Set the mode of the document.
     *
     * @param string $mode
     * @return PDFPasswordProtect
     */
    public function setMode(string $mode): PDFPasswordProtect
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Set the format of the document.
     *
     * @param string|array $format
     * @return PDFPasswordProtect
     */
    public function setFormat(array|string $format): PDFPasswordProtect
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get the page size of the PDF.
     *
     * @return array
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

        $tempFile = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($tempFile, Storage::disk($this->inputDisk)->get($this->inputFile));

        $mpdf->setSourceFile($tempFile);
        $getFirstPage = $mpdf->importPage(1);
        unlink($tempFile);

        return $mpdf->getTemplateSize($getFirstPage);
    }

    /**
     * Secure the PDF with a password.
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
                : (($pageSize['width'] < $pageSize['height']) ? $this->format : $this->format . '-L'),
        ]);

        $tempInputFile = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($tempInputFile, Storage::disk($this->inputDisk)->get($this->inputFile));

        $pagecount = $export->setSourceFile($tempInputFile);
        for ($p = 1; $p <= $pagecount; $p++) {
            $tplId = $export->importPage($p);
            $wh = $export->getTemplateSize($tplId);
            $export->state = $p == 1 ? 0 : 1;
            $export->AddPage($wh['width'] > $wh['height'] ? 'L' : 'P');
            $export->UseTemplate($tplId);
        }
        unlink($tempInputFile);

        $ownerPassword = $this->ownerPassword ?? $this->password;
        $export->SetProtection(['copy', 'print'], $this->password, $ownerPassword);

        $tempOutputFile = tempnam(sys_get_temp_dir(), 'pdf');
        $export->Output($tempOutputFile, Destination::FILE);

        Storage::disk($this->outputDisk)->put($this->outputFile, file_get_contents($tempOutputFile));
        unlink($tempOutputFile);
    }

    /**
     * Encrypt the PDF.
     *
     * @param string $inputFile
     * @param string $outputFile
     * @param string $password
     * @param string|null $ownerPassword
     * @param string $inputDisk
     * @param string $outputDisk
     * @throws MpdfException
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws InputFileNotSetException
     *
     * @deprecated Deprecated since version 2.0, check the docs.
     */
    public function encrypt($inputFile, $outputFile, $password, $ownerPassword = null, $inputDisk = 'local', $outputDisk = 'local'): void
    {
        $this->setInputFile($inputFile, $inputDisk)
            ->setOutputFile($outputFile, $outputDisk)
            ->setPassword($password)
            ->setOwnerPassword($ownerPassword ?? $password)
            ->secure();
    }
}
