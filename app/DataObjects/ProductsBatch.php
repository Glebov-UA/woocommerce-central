<?php

namespace App\DataObjects;

use JsonSerializable;

class ProductsBatch extends AbstractDataObject
{
    /**
     * @return array|null
     */
    public function getCreate(): ?array
    {
        return $this->getVal('create');
    }

    /**
     * @param array|null $create
     */
    public function setCreate(?array $create): void
    {
        $this->values['create'] = $create;
    }

    /**
     * @return array|null
     */
    public function getUpdate(): ?array
    {
        return $this->getVal('update');
    }

    /**
     * @param array|null $update
     */
    public function setUpdate(?array $update): void
    {
        $this->values['update'] = $update;
    }
}
