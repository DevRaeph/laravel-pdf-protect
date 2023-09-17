# Laravel PDF Protect

[![Latest Version on Packagist](https://img.shields.io/packagist/v/devraeph/laravel-pdf-protect.svg?style=flat-square)](https://packagist.org/packages/devraeph/laravel-pdf-protect)
[![Check & fix styling](https://github.com/DevRaeph/laravel-pdf-protect/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/DevRaeph/laravel-pdf-protect/actions/workflows/php-cs-fixer.yml)
[![run-tests](https://github.com/DevRaeph/laravel-pdf-protect/actions/workflows/run-tests.yml/badge.svg)](https://github.com/DevRaeph/laravel-pdf-protect/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/devraeph/laravel-pdf-protect.svg?style=flat-square)](https://packagist.org/packages/devraeph/laravel-pdf-protect)
[![Issues]](https://github.com/DevRaeph/laravel-pdf-protect/issues)

Simple wrapper package around MPDF's `setProtection` method that allows you to set password on PDF files.

### Version Compatibilities

| Laravel HashId 	 |         PHP Version      	         |   Laravel 7.*    	   |          Laravel 8.*    	           |          Laravel 9.*    	           |  Laravel 10.*    	   |
|------------------|:----------------------------------:|:--------------------:|:-----------------------------------:|:-----------------------------------:|:--------------------:|
| `1.0.0`     	    | `>=7.4` - `<= 8.0`               	 |  :white_check_mark:  | :white_check_mark:                	 |        :x:                	         | :x:                	 |
| `1.1.2`     	    | `>=7.4` - `<= 8.1`               	 |  :white_check_mark:  | :white_check_mark:                	 |      :white_check_mark:      	      | :x:                	 |
| `2.x`     	      |      `>=7.4` - `<= 8.2`    	       | :white_check_mark: 	 |        :white_check_mark: 	         |        :white_check_mark: 	         | :white_check_mark:	  |

### Installation

You can install the package via composer:

```bash
composer require devraeph/laravel-pdf-protect
```

### Usage

#### Version 2.x
In version 2.x the usage is more common to use and simplified. 

```php
$inputFile = storage_path('pdf/LetterFormat.pdf');
$outputFile = storage_path('pdf/encrypted-123.pdf');

PDFPasswordProtect::setInputFile($inputFile)
    ->setOutputFile($outputFile)
    ->setPassword("1234")
    ->secure();
```

You can also add the optional method 'setOwnerPassword':
```php
...

PDFPasswordProtect::setInputFile($inputFile)
    ...
    ->setOwnerPassword("1234")
    ->secure();
```

Alternative new options are: 'setMode' and 'setFormat'.
>setFormat is default 'auto' and will now detect the document format.
> Before v2.x it was set to 'A4'.
```php
PDFPasswordProtect::setInputFile($inputFile)
...
->setMode("en_GB") //You can set different language values. Default is utf-8
->setFormat("auto|A4|Letter") //You can set a Document format. Default is auto.
->secure();
```

#### Version 1.x
You can also use the old version from v1.x in v2.x, but it is 
deprecated and will no longer get any updates.
```php
PdfPasswordProtect::encrypt(storage_path('pdf/document.pdf'),storage_path('pdf/'.'encrypted-documented.pdf'),'janedoe');
```
Encrypt method in detail
* $inputFile and $outputFile has to be a path like `storage_path("pdf/document.pdf")`
```php
PdfPasswordProtect::encrypt($inputFile,$outputFile,$password)
```

The final file will be located in `storage/pdf` as `encrypted-document.pdf`

### WIP
For Version 2.1.x: 
- Support for the Storage::class, to use s3 or external storages.

### Testing

```bash
composer test
```

## Credits

- [Owen Jubilant](https://github.com/Owen-oj) (creator of the original package) - [PDF Password Protect](https://github.com/Owen-oj/pdf-password-protect)
- [DevRaeph](https://github.com/devraeph) (refactored classes to comply with PSR-4)

## Sponsor
[![ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/A0A3E29FS)

## License
The MIT License (MIT).

[Issues]: https://img.shields.io/github/issues/DevRaeph/laravel-pdf-protect
