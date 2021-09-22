<?php

namespace App\DataObjects;

class MetaData extends AbstractDataObject
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
    public function getKey(): ?string
    {
        return $this->getVal('key');
    }

    /**
     * @param string|null $key
     */
    public function setKey(?string $key): void
    {
        $this->values['key'] = $key;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->getVal('value');
    }

    /**
     * @param string|null $value
     */
    public function setValue(?string $value): void
    {
        $this->values['value'] = $value;
    }
}
