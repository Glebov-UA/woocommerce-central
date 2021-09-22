<?php

namespace App\DataObjects;

use JsonSerializable;

class Attribute implements JsonSerializable
{
    private ?int $id = null;
    private ?string $name = null;
    private ?array $options = null;
    private ?bool $visible = null;

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
     * @return array|null
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * @param array|null $options
     */
    public function setOptions(?array $options): void
    {
        $this->options = $options;
    }

    /**
     * @return bool|null
     */
    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    /**
     * @param bool|null $visible
     */
    public function setVisible(?bool $visible): void
    {
        $this->visible = $visible;
    }

    public function jsonSerialize()
    {
        return array_filter([
            'id'  => $this->id,
            'name' => $this->name,
            'visible' => $this->visible,
            'options' => $this->options
        ]);
    }

}
