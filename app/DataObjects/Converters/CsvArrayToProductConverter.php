<?php

namespace App\DataObjects\Converters;

use App\DataObjects\Attribute;
use App\DataObjects\MetaData;
use App\DataObjects\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CsvArrayToProductConverter
{
    private $store;
    private $product;

    private CsvArrayToAttributesConverter $attributesConverter;
    private array $metaData;

    /**
     * @param $store
     */
    public function __construct($store)
    {
        $this->store = $store;
        $this->product = new Product();
        $this->attributesConverter = new CsvArrayToAttributesConverter($store);
        $this->metaData = [];
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /** @noinspection DuplicatedCode */
    public function convert(array $array)
    {
        foreach ($array as $key => $value) {
            Log::debug('Preparing Json. Key is', [$this, $key]);
            Log::debug('Preparing Json. Value is', [$this, $value]);
            switch ($key) {
                case 'ID':
                    $this->convertId($value);
                    break;
                case 'SKU':
                    $this->convertSku($value);
                    break;
                case 'Name':
                    $this->convertName($value);
                    break;
                case 'Short Description':
                    $this->convertShortDescription($value);
                    break;
                case 'Description':
                    $this->convertDescription($value);
                    break;
                case 'Categories':
                    $this->convertCategories($value);
                    break;
                case 'Published':
                    $this->convertPublished($value);
                    break;
                case 'Regular price':
                    $this->convertRegularPrice($value);
                    break;
                case 'In stock?':
                    $this->convertStockStatus($value);
                    break;
                case 'Images':
                    $this->convertImages($value);
                    break;
                default:
                    if (str_contains($key, 'Attribute')) {
                        $this->convertAttribute($key, $value);
                    }
                    if (str_contains($key, 'Meta')) {
                        $this->convertMetaData($key, $value);
                    }
                    break;
            }
        }
        $this->finalizeAttributes();
        $this->finalizeMetaData();
    }

    private function convertId($value)
    {
        $this->product->setId($value);
    }

    private function convertSku($value)
    {
        $this->product->setSku($value);
        $this->checkIfExistsBySku($value);
    }

    private function checkIfExistsBySku($sku) {
        $skuSearchResponse = Http::withBasicAuth($this->store->consumer_key, $this->store->consumer_secret)->get($this->store->url . '/wp-json/wc/v3/products',  ['sku' => $sku])->json();
        Log::debug('Checking if sku already exists.', [$this, $sku, $skuSearchResponse]);
        foreach ($skuSearchResponse as $response) {
            if($response['sku'] == $sku) {
                Log::debug('Checking if sku already exists. - it does', [$this, $sku]);
                $this->product->setId($response['id']);
                return;
            }
        }
        Log::debug('Checking if sku already exists. - it does not', [$this, $sku]);
    }

    private function convertName($value)
    {
        $this->product->setName($value);
    }

    private function convertShortDescription($value)
    {
        $this->product->setShortDescription($value);
    }

    private function convertDescription($value)
    {
        $this->product->setDescription($value);
    }

    private function convertCategories($value)
    {
        $converter = new CsvArrayToCategoriesArrayConverter($this->store);
        $converter->convert($value);
        $this->product->setCategories($converter->getCategories());
    }

    private function convertPublished($value)
    {
        switch ($value) {
            case 1:
                $this->product->setStatus('publish');
                break;
            case 0:
                $this->product->setStatus('draft');
                break;
        }
    }

    private function convertRegularPrice($value)
    {
        $this->product->setRegularPrice($value);
    }

    private function convertStockStatus($value)
    {
        switch ($value) {
            case 1:
                $this->product->setStockStatus('instock');
                break;
            case 0:
                $this->product->setStockStatus('outofstock');
                break;
        }
    }

    //TODO Separate entity for images
    private function convertImages($value) {
        $arr = explode(', ', $value);
        $images = [];
        foreach ($arr as $image) {
            array_push($images, ["src" => $image]);
        }
        $this->product->setImages($images);
    }

    private function convertAttribute($key, $value) {
        $this->attributesConverter->convert($key, $value);
    }

    private function finalizeAttributes() {
        $this->product->setAttributes($this->attributesConverter->getAttributes());
    }

    //Meta: _wcj_purchase_price
    private function convertMetaData($key, $value) {
        if(preg_match('/Meta:\s(.*)/', $key, $matches) && !empty($value)) {
            $meta = new MetaData();
            $meta->setKey($matches[1]);
            $meta->setValue($value);
            array_push($this->metaData, $meta);
        }
    }

    private function finalizeMetaData()
    {
        if(!empty($this->metaData)) {
            $this->product->setMetaData($this->metaData);
        }
    }
}
