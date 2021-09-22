<?php

namespace App\DataObjects;

use JsonSerializable;

class ProductsBatch implements JsonSerializable
{
    private ?array $create = null;
    private ?array $update = null;

    /**
     * @return array|null
     */
    public function getCreate(): ?array
    {
        return $this->create;
    }

    /**
     * @param array|null $create
     */
    public function setCreate(?array $create): void
    {
        $this->create = $create;
    }

    /**
     * @return array|null
     */
    public function getUpdate(): ?array
    {
        return $this->update;
    }

    /**
     * @param array|null $update
     */
    public function setUpdate(?array $update): void
    {
        $this->update = $update;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'create'  => $this->create,
            'update' => $this->update
        ]);
    }
}
