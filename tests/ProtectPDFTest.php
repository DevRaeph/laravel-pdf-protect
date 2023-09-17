<?php

it('will throw InputFile missing exception', function () {
    \DevRaeph\PDFPasswordProtect\Facade\PDFPasswordProtect::secure();
})->throws(\DevRaeph\PDFPasswordProtect\Exceptions\InputFileNotSetException::class);

it('will throw InputFile not found exception', function () {
    \DevRaeph\PDFPasswordProtect\Facade\PDFPasswordProtect::setInputFile('test.pdf')
        ->secure();
})->throws(\DevRaeph\PDFPasswordProtect\Exceptions\InputFileNotFoundException::class);
