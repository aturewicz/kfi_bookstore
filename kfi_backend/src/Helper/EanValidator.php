<?php

namespace App\Helper;

/**
 * Class EanValidator
 * @package App\Helper
 */
class EanValidator implements EanValidatorInterface
{

    /**
     * @param string $ean
     * @param int $type
     * @return bool
     * @throws \Exception
     */
    public function isValid(string $ean, int $type): bool
    {
        $valid = false;
        if ($this->lengthValid($ean, $type) && ctype_digit($ean)) {
            switch ($type) {
                case 13:
                    $controlNumber = $this->getLastCharacter($ean);
                    $valid = $this->ean13IsValid($ean, $controlNumber);
                    break;
                case 8:
                    //TODO EAN-8 (GTIN-8)
                    break;
                case 128:
                    //TODO EAN-128 (GTIN-128)
                    break;
                case 14:
                    //TODO EAN-14(GTIN-14)
                    break;
                default:
                    // throw new \Exception('EAN type not found!');
            }
        } else {
            // throw new \Exception('WRONG EAN type!');
        }
        return $valid;

    }

    /**
     * Method validate EAN-13
     * @link https://logistykanalogike.wordpress.com/2017/06/05/jak-obliczyc-cyfre-kontrolna-kodu-ean-13/
     * @param string $ean
     * @param int $controlNumber
     * @return bool
     */
    private function ean13IsValid(string $ean, int $controlNumber): bool
    {
        // Even and odd numbers of $ean
        $evenNumbers = $this->substrNumbersArray($ean, 0, 2, 4, 6, 8, 10);
        $oddNumbers = $this->substrNumbersArray($ean, 1, 3, 5, 7, 9, 11);

        // Calculate the sum of values in arrays: $evenNumbers and $oddNumbers
        $evenSum = array_sum($evenNumbers);
        $oddSum = array_sum($oddNumbers);

        $oddMultiplication = $oddSum * 3;
        $sum = $evenSum + $oddMultiplication;
        $sumRound = $sum - $sum % 10 + 10;

        // Calculate control number
        $calculateControlNumber = $sumRound - $sum;

        if ($calculateControlNumber === 10) $calculateControlNumber = 0;

        //Compare ean control number with calculate control number
        if ($controlNumber != $calculateControlNumber) return false;

        return true;
    }

    /**
     * @param string $string
     * @param int $length
     * @return bool
     */
    private function lengthValid(string $string, int $length): bool
    {
        if (strlen($string) === $length) return true;
        return false;
    }

    /**
     * @param string $str
     * @param int ...$offsets
     * @return array
     */
    private function substrNumbersArray(string $str, int ...$offsets): array
    {
        $arr = [];
        foreach ($offsets as $offset) {
            $arr[] = (int)substr($str, $offset, 1);
        }
        return $arr;
    }

    /**
     * @param string $string
     * @return int
     */
    private function getLastCharacter(string $string): int
    {
        return (int)substr($string, -1);
    }
}