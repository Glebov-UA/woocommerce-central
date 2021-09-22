<?php

namespace App\DataObjects\Converters;

use App\DataObjects\Attribute;
use App\DataObjects\CsvAttribute;
use App\Models\Store;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CsvArrayToAttributesConverter
{
    private Store $store;
    private $attributes;
    private $globalAttributes;

    public function __construct($store)
    {
        $this->store = $store;
        $this->attributes = [];
        $this->globalAttributes = [];
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        //Get store attributes
        $response = Http::withBasicAuth($this->store->consumer_key, $this->store->consumer_secret)->get($this->store->url . '/wp-json/wc/v3/products/attributes')->json();
        //Remove indexes and assign ids
        $arr = [];
        foreach ($this->attributes as $key => $attribute) {
            $exists = false;
            if(in_array($key, $this->globalAttributes)) {
                foreach ($response as $attr) {
                    if($attr['name'] == $attribute->getName()) {
                        $exists = true;
                        $attribute->setId($attr['id']);
                        break;
                    }
                }
                //If does not exist - create it
                if(!$exists) {
                    $attributeCreationResponse = Http::withBasicAuth($this->store->consumer_key, $this->store->consumer_secret)->post($this->store->url . '/wp-json/wc/v3/products/attributes',  $attribute)->json();
                    $attribute->setId($attributeCreationResponse['id']);
                }
            }
            //Set name to null because it breaks batch processing - it will simply return empty response
            $attribute->setName(null);
            $attribute->setVisible(null);
            array_push($arr, $attribute);
        }
        return $arr;
    }

    public function convert($key, $value) {
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
                    break;
                case 'global':
                    if($value == '1') {
                        array_push($this->globalAttributes, $matches[1]);
                    }
                    break;
            }
        }
    }
}
