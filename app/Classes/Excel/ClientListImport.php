<?php


namespace App\Classes\Excel;


use Maatwebsite\Excel\Files\ExcelFile;

/**
 * Class ClientListImport
 * @package app\Classes\Excel
 */
class ClientListImport extends ExcelFile {

    public function getFile()
    {
        return storage_path('app/exports') . '/combined.xlsx';
    }

    public function getFilters()
    {
        return [
            'chunk'
        ];
    }

}