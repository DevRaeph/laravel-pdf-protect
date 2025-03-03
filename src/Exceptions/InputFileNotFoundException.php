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
* | Filename:   InputFileNotSetException.php
* | Created:    12.06.2023 (12:56:02)
* | Copyright (C) 2023 Develogix Agency e.U. All Rights Reserved
* | Website:    https://develogix.at
*/

namespace DevRaeph\PDFPasswordProtect\Exceptions;

use Exception;
use Throwable;

class InputFileNotFoundException extends Exception
{
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        if ($message == '') {
            $message = 'Input File not found, please use storage_path(file_name)';
        }
        parent::__construct($message, $code, $previous);
    }
}
