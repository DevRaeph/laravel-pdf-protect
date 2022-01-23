<?php

namespace DevRaeph\PDFPasswordProtect\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DevRaeph\PDFPasswordProtect\Skeleton\SkeletonClass
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
        return 'pdf-password-protect';
    }
}
