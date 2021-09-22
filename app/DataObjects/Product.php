<?php

namespace App\DataObjects;

use JsonSerializable;

class Product implements JsonSerializable
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $sku = null;
    private ?string $short_description = null;
    private ?string $description = null;
    private ?string $status = null;
    private ?string $stock_status = null;
    private ?string $regular_price = null;
    private ?array $categories = null;
    private ?array $images = null;
    private ?array $attributes = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @param string|null $sku
     */
    public function setSku(?string $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    /**
     * @param string|null $short_description
     */
    public function setShortDescription(?string $short_description): void
    {
        $this->short_description = $short_description;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getStockStatus(): ?string
    {
        return $this->stock_status;
    }

    /**
     * @param string|null $stock_status
     */
    public function setStockStatus(?string $stock_status): void
    {
        $this->stock_status = $stock_status;
    }

    /**
     * @return string|null
     */
    public function getRegularPrice(): ?string
    {
        return $this->regular_price;
    }

    /**
     * @param string|null $regular_price
     */
    public function setRegularPrice(?string $regular_price): void
    {
        $this->regular_price = $regular_price;
    }

    /**
     * @return array|null
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }

    /**
     * @param array|null $categories
     */
    public function setCategories(?array $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * @return array|null
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     * @param array|null $images
     */
    public function setImages(?array $images): void
    {
        $this->images = $images;
    }

    /**
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    /**
     * @param array|null $attributes
     */
    public function setAttributes(?array $attributes): void
    {
        $this->attributes = $attributes;
    }



    public function jsonSerialize()
    {
        return array_filter([
            'id'  => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'status' => $this->status,
            'stock_status' => $this->stock_status,
            'regular_price' => $this->regular_price,
            'categories' => $this->categories,
            'images' => $this->images,
            'attributes' => $this->attributes,
        ]);
    }
}
