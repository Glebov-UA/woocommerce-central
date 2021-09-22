<?php

namespace App\DataObjects;

use JsonSerializable;

class Product extends AbstractDataObject
{
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->getVal('id');
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->values['id'] = $id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->getVal('name');
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->values['name'] = $name;
    }

    /**
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->getVal('sku');
    }

    /**
     * @param string|null $sku
     */
    public function setSku(?string $sku): void
    {
        $this->values['sku'] = $sku;
    }

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return $this->getVal('short_description');
    }

    /**
     * @param string|null $short_description
     */
    public function setShortDescription(?string $short_description): void
    {
        $this->values['short_description'] = $short_description;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->getVal('description');
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->values['description'] = $description;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->getVal('status');
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->values['status'] = $status;
    }

    /**
     * @return string|null
     */
    public function getStockStatus(): ?string
    {
        return $this->getVal('stock_status');
    }

    /**
     * @param string|null $stock_status
     */
    public function setStockStatus(?string $stock_status): void
    {
        $this->values['stock_status'] = $stock_status;
    }

    /**
     * @return string|null
     */
    public function getRegularPrice(): ?string
    {
        return $this->getVal('regular_price');
    }

    /**
     * @param string|null $regular_price
     */
    public function setRegularPrice(?string $regular_price): void
    {
        $this->values['regular_price'] = $regular_price;
    }

    /**
     * @return array|null
     */
    public function getCategories(): ?array
    {
        return $this->getVal('categories');
    }

    /**
     * @param array|null $categories
     */
    public function setCategories(?array $categories): void
    {
        $this->values['categories'] = $categories;
    }

    /**
     * @return array|null
     */
    public function getImages(): ?array
    {
        return $this->getVal('images');
    }

    /**
     * @param array|null $images
     */
    public function setImages(?array $images): void
    {
        $this->values['images'] = $images;
    }

    /**
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        return $this->getVal('attributes');
    }

    /**
     * @param array|null $attributes
     */
    public function setAttributes(?array $attributes): void
    {
        $this->values['attributes'] = $attributes;
    }

    /**
     * @return array|null
     */
    public function getMetaData(): ?array
    {
        return $this->getVal('meta_data');
    }

    /**
     * @param array|null $attributes
     */
    public function setMetaData(?array $attributes): void
    {
        $this->values['meta_data'] = $attributes;
    }
}
