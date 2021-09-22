<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CsvParseService implements CsvParseServiceInterface
{
    public function parseCsv($stores, UploadedFile $file) {
        //Save File
        $filepath = $this->saveFile($file);
        //Parse csv
        $data = $this->csvToArray($filepath);
        //Prepare json
        foreach ($stores as $store) {
            Log::debug('Preparing Json.',[$this, $data]);
            $products = $this->prepareJson($store, $data);
            Log::debug('Json Prepared.',[$this, $products]);
            $json = [];
            $json['create'] = [];
            $json['update'] = [];
            foreach ($products as $product) {
                if (($id = $this->getProductIdBySku($store, $product['sku'])) != null) {
                    $product['id'] = $id;
                    array_push($json['update'], $product);
                } else {
                    array_push($json['create'], $product);
                }
            }
            if(count($json['create']) == 0) {
                unset($json['create']);
            }
            if(count($json['update']) == 0) {
                unset($json['update']);
            }
            Log::debug('Uploading products',[$this, $json]);
            $response = Http::withBasicAuth($store->consumer_key, $store->consumer_secret)->post($store->url . '/wp-json/wc/v3/products/batch', $products);
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

    private function prepareJson($store, $data) {
        $products = array();
        foreach ($data as $indexData => $productData) {
            $product = array();
            foreach ($productData as $key => $value) {
                Log::debug('Preparing Json. Key is', [$this, $key]);
                Log::debug('Preparing Json. Value is', [$this, $value]);
                switch ($key) {
                    case 'SKU':
                        $product['sku'] = $value;
                        break;
                    case 'Name':
                        $product['name'] = $value;
                        break;
                    case 'Short Description':
                        $product['short_description'] = $value;
                        break;
                    case 'Description':
                        $product['description'] = $value;
                        break;
                    case 'Categories':
                        $ids = $this->getCategoryIds($store, $value);
                        $product['categories'] = [];
                        foreach ($ids as $id) {
                            array_push($product['categories'], ['id' => $id]);
                        }
                        break;
                    case 'Published':
                        switch ($value) {
                            case 1:
                                $product['status'] = 'publish';
                                break;
                            case 0:
                                $product['status'] = 'draft';
                                break;
                        }
                        break;
                    case 'Regular price':
                        $product['regular_price'] = $value;
                        break;
                    case 'In stock?':
                        switch ($value) {
                            case 1:
                                $product['stock_status'] = 'instock';
                                break;
                            case 0:
                                $product['stock_status'] = 'outofstock';
                                break;
                        }
                        break;
                    case 'Images':
                        $arr = explode(', ', $value);
                        $images = [];
                        foreach ($arr as $image) {
                            array_push($images, ["src" => $image]);
                        }
                        $product['images'] = $images;
                        break;
                    default:
                        if (str_contains($key ,'Attribute')) {
                            $this->processAttributeValue($value, $product);
                        }
                        break;
                }
            }
            array_push($products, $product);
        }
        return $products;
    }

    private function processAttributeValue($value, &$product) {
        $matches = [];
        //if matches name and also grab id
//        if(preg_match('Attribute\s(\d*)\s([a-zA-Z]*)', $value, $matches) == 1) {
//            foreach ($product['attributes'] as $attribute) {
//                if($attribute['num'] == $matches[1]) {
//
//                }
//            }
//        }
    }



    private function getCategoryIds(Store $store, $categories) {
        Log::debug('Getting CategoryIds',[$this, $store, $categories]);
        $categoryIds = [];
        $categoriesArray = explode(', ', $categories);

        $func = function (Pool $pool) use ($store, $categoriesArray) {
            $responses = [];
            foreach ($categoriesArray as $category) {
                array_push($responses, $pool->withBasicAuth($store->consumer_key, $store->consumer_secret)->get($store->url . '/wp-json/wc/v3/products/categories', ['search' => $category]));
            }
            return $responses;
        };

        $responses = Http::withBasicAuth($store->consumer_key, $store->consumer_secret)->pool($func);
        Log::debug('Getting CategoryIds. Responses:',[$this, $responses]);
        //Each response is a check for separate category
        foreach ($responses as $response) {
            Log::debug('Getting CategoryIds. Iterating through responses',[$this, $response]);
            $json = $response->json();
            Log::debug('Getting CategoryIds. Response JSON',[$this, $json]);
            $found = false;
            $i = 0;
            foreach ($json as $entry) {
                Log::debug('Getting CategoryIds. $entry',[$this, $entry]);
                if ($entry['name'] == $categoriesArray[$i]) {
                    Log::debug('Found category.',[$this, $entry['name']]);
                    $found = true;
                    array_push($categoryIds, $entry['id']);
                    break;
                } else {
                    $i++;
                }
            }
            //If not found, we need to create a category
            if(!$found) {
                Log::debug('Category not found. Creating it.',[$this, $categoriesArray[$i]]);
                $response = Http::withBasicAuth($store->consumer_key, $store->consumer_secret)->post($store->url . '/wp-json/wc/v3/products/categories', ['name' => $categoriesArray[$i]]);
                array_push($categoryIds, $response->json()['id']);
            }
        }
        return $categoryIds;
    }

    private function getProductIdBySku($store, $sku) {
        Log::debug('Trying to find a product by Sku.',[$this, $sku]);
        $response = Http::withBasicAuth($store->consumer_key, $store->consumer_secret)->get($store->url . '/wp-json/wc/v3/products', ['sku' => $sku]);
        $json = $response->json();
        Log::debug('Trying to find a product by Sku. Response json:',[$this, $json]);
        if(count($json) > 0) {
            Log::debug('Trying to find a product by Sku. Returning id',[$this, $json[0]['id']]);
            return $json[0]['id'];
        }
        Log::debug('Trying to find a product by Sku. Returning null',[$this]);
        return null;
    }
}
