# Laravel PDF Protect (fork)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/devraeph/laravel-pdf-protect.svg?style=flat-square)](https://packagist.org/packages/devraeph/laravel-pdf-protect)
[![Check & fix styling](https://github.com/DevRaeph/laravel-pdf-protect/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/DevRaeph/laravel-pdf-protect/actions/workflows/php-cs-fixer.yml)
[![run-tests](https://github.com/DevRaeph/laravel-pdf-protect/actions/workflows/run-tests.yml/badge.svg)](https://github.com/DevRaeph/laravel-pdf-protect/actions/workflows/run-tests.yml)
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

- [Owen Jubilant](https://github.com/Owen-oj) (creator of the original package) - [PDF Password Protect](https://github.com/Owen-oj/pdf-password-protect)
- [DevRaeph](https://github.com/devraeph) (refactored classes to comply with PSR-4)

## License
The MIT License (MIT).
