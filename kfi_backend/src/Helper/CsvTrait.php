<?php


namespace App\Helper;

use League\Csv\Exception;
use League\Csv\Reader;

trait CsvTrait
{
    /**
     * @throws Exception
     */
    private function readCsvFile($file): array
    {
        // Read CSV file
        $reader = Reader::createFromPath(self::PATH_FILE . $file);
        $reader->setHeaderOffset(0);

        return ['header' => $reader->getHeader(), 'items' => $reader->getRecords()];
    }

    private function checkHeader($headerCsv): array
    {
        return array_diff(self::HEADER, $headerCsv);
    }
}