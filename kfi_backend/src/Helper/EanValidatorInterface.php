<?php

namespace App\Helper;

interface EanValidatorInterface
{

    /**
     * @param string $ean
     * @param int $type
     * @return bool
     * @throws \Exception
     */
    public function isValid(string $ean, int $type): bool;
}