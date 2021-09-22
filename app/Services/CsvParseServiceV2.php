<?php

namespace App\Services;

use App\DataObjects\Converters\CsvArrayToProductsBatchConverter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CsvParseServiceV2 implements CsvParseServiceInterface
{

    function parseCsv($stores, UploadedFile $file)
    {
        //Save File
        $filepath = $this->saveFile($file);
        //Parse csv
        $data = $this->csvToArray($filepath);
        //Prepare json
        foreach ($stores as $store) {
            $converter = new CsvArrayToProductsBatchConverter($store);
            $converter->convert($data);
            $batch = $converter->getProductsBatch();
            Log::debug('Uploading products',[$this]);
            $response = Http::withBasicAuth($store->consumer_key, $store->consumer_secret)->post($store->url . '/wp-json/wc/v3/products/batch', json_decode(json_encode($batch),true));
            Log::debug('Uploading products finished. Response.',[$this, $response, $response->json()]);
        }
    }

    private function saveFile(UploadedFile $file) {
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();
        // Valid File Extensions
        $valid_extension = array("csv");
        // 2MB in Bytes
        $maxFileSize = 2097152;
        // File upload location
        $location = 'uploads';
        // Upload file
        $file->move($location,$filename);
        return public_path($location."/".$filename);
    }

    private function csvToArray($filepath, $delimiter = ',') {
        $data = array();
        $header = null;
        if (($handle = fopen($filepath, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE)
            {
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }
}
