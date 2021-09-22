<?php

namespace App\DataObjects\Converters;

use App\DataObjects\Attribute;
use App\DataObjects\Product;
use Illuminate\Support\Facades\Log;

class CsvArrayToProductConverter
{
    private $store;
    private $product;

    private CsvArrayToAttributesConverter $attributesConverter;

    /**
     * @param $store
     */
    public function __construct($store)
    {
        $this->store = $store;
        $this->product = new Product();
        $this->attributesConverter = new CsvArrayToAttributesConverter($store);
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
                        $this->processAttributes($key, $value);
                    }
                    break;
            }
        }
        $this->finalizeAttributes();
    }

    private function convertSku($value)
    {
        $this->product->setSku($value);
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

    private function convertAttributeValue($key, $value) {
        Log::debug('Converting attribute value', [$this, $key, $value]);
        if(preg_match('/Attribute\s(\d*)\s([a-zA-Z()]*)/', $key, $matches) && !empty($value)) {
            Log::debug('Converting attribute value - preg matches', [$this, $key, $value, $matches]);
            if(!array_key_exists($matches[1], $this->attributes)) {
                Log::debug('Converting attribute value - attribute does not yet exist, creating it', [$this, $this->attributes]);
                $this->attributes[$matches[1]] = new Attribute();
            }
            switch ($matches[2]) {
                case 'name':
                    $this->attributes[$matches[1]]->setName($value);
                    break;
                case 'visible':
                    if($value == '1') {
                        $this->attributes[$matches[1]]->setVisible(true);
                    } else {
                        $this->attributes[$matches[1]]->setVisible(false);
                    }
                    break;
                case 'value(s)':
                    $this->attributes[$matches[1]]->setOptions(explode(', ', $value));
            }
        }
    }

    private function processAttributes($key, $value) {
        $this->attributesConverter->convert($key, $value);
    }

    private function finalizeAttributes() {
        $this->product->setAttributes($this->attributesConverter->getAttributes());
    }
}
