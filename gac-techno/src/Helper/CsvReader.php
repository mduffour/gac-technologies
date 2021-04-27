<?php

declare(strict_types=1);

namespace App\Helper;

use RuntimeException;
use SplFileObject;

final class CsvReader
{
    public function getData(
        string $filename, 
        string $delimiter = ";", 
        string $enclosure = "\"", 
        string $escape = "\\"
        ): iterable {
            
        $this->csvFile = new SplFileObject($filename);
        $this->csvFile->setFlags(
            SplFileObject::READ_CSV | 
            SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | 
            SplFileObject::DROP_NEW_LINE
        );

        if ($this->csvFile->getSize() === 0) {
            throw new RuntimeException('File is empty');
        }

        $this->csvFile->rewind();
        
        $data = $headers =  [];
        $i = 0;
        while ($this->csvFile->valid()) {
            $row = $this->csvFile->fgetcsv($delimiter, $enclosure, $escape);

            //Skip invalid first line
            if ($i < 1) {
                $i++;
                continue;
            }

            //Get headers
            if ($i === 1) {
               $headers = array_map([$this, 'sanitizeValue'], $row);
               $i++;
               continue;
            }

            $i++;

            if (!empty($row)) {
                $data[] = array_combine($headers, array_map([$this, 'sanitizeValue'], $row));
            }
        }

        return $data;
    }

    private static function sanitizeValue(string $value): string
    {
        return trim(utf8_encode($value));
    }
}