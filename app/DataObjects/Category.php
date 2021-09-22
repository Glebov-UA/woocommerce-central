<?php

namespace App\DataObjects;

use JsonSerializable;

class Category extends AbstractDataObject
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
}
