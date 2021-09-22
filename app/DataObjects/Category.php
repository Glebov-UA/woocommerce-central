<?php

namespace App\DataObjects;

use JsonSerializable;

class Category implements JsonSerializable
{
    private ?int $id = null;
    private ?string $name = null;

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



    public function jsonSerialize()
    {
        return array_filter([
            'id'  => $this->id,
            'name' => $this->name,
        ]);
    }
}
