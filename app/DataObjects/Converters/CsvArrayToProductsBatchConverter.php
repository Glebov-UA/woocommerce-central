<?php

namespace App\DataObjects\Converters;

use App\DataObjects\ProductsBatch;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CsvArrayToProductsBatchConverter
{
    private $store;
    private $productsBatch;

    /**
     * @param $store
     */
    public function __construct($store)
    {
        $this->store = $store;
        $this->productsBatch = new ProductsBatch();
    }

    /**
     * @return mixed
     */
    public function getProductsBatch()
    {
        return $this->productsBatch;
    }

    public function convert(array $array) {
        $create = [];
        $update = [];

        $productConverter = new CsvArrayToProductConverter($this->store);

        //create a product and put it into an appropriate array
        foreach ($array as $productVal) {
            $productConverter->convert($productVal);
            $product = $productConverter->getProduct();
            if ($product->getId() != null) {
                array_push($update, $product);
            } else {
                array_push($create, $product);
            }
        }
        //set only non-empty batches
        if(count($create) != 0) {
            $this->productsBatch->setCreate($create);
        }
        if(count($update) != 0) {
            $this->productsBatch->setUpdate($update);
        }
        Log::debug('Product batch processed',[$this, json_encode($this->productsBatch)]);
    }
}
