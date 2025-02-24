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

namespace DevRaeph\PDFPasswordProtect\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DevRaeph\PDFPasswordProtect\PDFPasswordProtect
 */
class PDFPasswordProtect extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \DevRaeph\PDFPasswordProtect\PDFPasswordProtect::class;
    }
}
