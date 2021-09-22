<?php

namespace App\DataObjects;

use JsonSerializable;

class Attribute extends AbstractDataObject
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
     * @return array|null
     */
    public function getOptions(): ?array
    {
        return $this->getVal('options');
    }

    /**
     * @param array|null $options
     */
    public function setOptions(?array $options): void
    {
        $this->values['options'] = $options;
    }

    /**
     * @return bool|null
     */
    public function getVisible(): ?bool
    {
        return $this->getVal('visible');
    }

    /**
     * @param bool|null $visible
     */
    public function setVisible(?bool $visible): void
    {
        $this->values['visible'] = $visible;
    }
}
