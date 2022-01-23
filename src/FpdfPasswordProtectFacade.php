<?php

namespace DevRaeph\FpdfPasswordProtect;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DevRaeph\FpdfPasswordProtect\Skeleton\SkeletonClass
 */
class FpdfPasswordProtectFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fpdf-password-protect';
    }
}
