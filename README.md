# Laravel PDF Protect

[![Latest Version on Packagist](https://img.shields.io/packagist/v/devraeph/laravel-pdf-protect.svg?style=flat-square)](https://packagist.org/packages/devraeph/laravel-pdf-protect)
[![Total Downloads](https://img.shields.io/packagist/dt/devraeph/laravel-pdf-protect.svg?style=flat-square)](https://packagist.org/packages/devraeph/laravel-pdf-protect)

Simple wrapper package around MPDF's `setProtection` method that allows you to set password on PDF files.

## Installation

You can install the package via composer:

```bash
composer require devraeph/laravel-pdf-protect
```

## Usage

You can use via Facade like so:

```php
PdfPasswordProtect::encrypt(storage_path('pdf/document.pdf'),storage_path('pdf/'.'encrypted-documented.pdf'),'janedoe');
```
Encrypt method in detail
* $inputFile and $outputFile has to be a path like `storage_path("pdf/document.pdf")`
```
PdfPasswordProtect::encrypt($inputFile,outputFile,$password)
```

The final file will be located in `storage/pdf` as `encrypted-document.pdf`

### Testing

```bash
composer test
```

## Credits

- [Owen Jubilant](https://github.com/owenoj)
- [DevRaeph](https://github.com/devraeph) (refactored classes to comply with PSR-4)

## License
The MIT License (MIT).
