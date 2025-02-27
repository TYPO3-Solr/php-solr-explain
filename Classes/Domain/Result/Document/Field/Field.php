<?php

namespace ApacheSolrForTypo3\SolrExplain\Domain\Result\Document\Field;

class Field
{
    protected string $name;

    protected mixed $value;

    protected int $type;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @noinspection PhpUnused
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @noinspection PhpUnused
     */
    public function getType(): int
    {
        return $this->type;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @noinspection PhpUnused
     */
    public function isMultiValue(): bool
    {
        return is_array($this->value);
    }
}
