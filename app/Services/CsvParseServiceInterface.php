<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

interface CsvParseServiceInterface
{
    function parseCsv($stores, UploadedFile $file);
}
