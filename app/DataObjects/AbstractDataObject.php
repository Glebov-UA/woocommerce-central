<?php

namespace App\DataObjects;

use JsonSerializable;

abstract class AbstractDataObject implements JsonSerializable
{
    protected array $values;

    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    protected function getVal(string $name) {
        return (array_key_exists($name, $this->values)) ?  $this->values[$name] : null;
    }

    /**
     * @return array|mixed
     */
    public function getVals()
    {
        return $this->values;
    }

    /**
     * @param array|mixed $values
     */
    public function setVals($values): void
    {
        $this->values = $values;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array_filter($this->values);
    }
}
