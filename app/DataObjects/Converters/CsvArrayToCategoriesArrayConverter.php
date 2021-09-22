<?php

namespace App\DataObjects\Converters;

use App\DataObjects\Category;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CsvArrayToCategoriesArrayConverter
{
    private $store;
    private $categories;

    /**
     * @param $store
     */
    public function __construct($store)
    {
        $this->store = $store;
        $this->categories = [];
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /** @noinspection DuplicatedCode */
    public function convert($array) {
        Log::debug('Getting CategoryIds',[$this, $array]);
        $categoriesArray = explode(', ', $array);

        $store = $this->store;
        $func = function (Pool $pool) use ($store, $categoriesArray) {
            $responses = [];
            foreach ($categoriesArray as $category) {
                array_push($responses, $pool->withBasicAuth($this->store->consumer_key, $this->store->consumer_secret)->get($this->store->url . '/wp-json/wc/v3/products/categories', ['search' => $category]));
            }
            return $responses;
        };

        $responses = Http::withBasicAuth($this->store->consumer_key, $this->store->consumer_secret)->pool($func);
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
                    $category = new Category();
                    $category->setId($entry['id']);
                    array_push($this->categories, $category);
                    break;
                } else {
                    $i++;
                }
            }
            //If not found, we need to create a category
            if(!$found) {
                Log::debug('Category not found. Creating it.',[$this, $categoriesArray[$i]]);
                $response = Http::withBasicAuth($this->store->consumer_key, $this->store->consumer_secret)->post($this->store->url . '/wp-json/wc/v3/products/categories', ['name' => $categoriesArray[$i]]);
                $category = new Category();
                $category->setId($response->json()['id']);
                array_push($this->categories, $category);
            }
        }
    }
}
