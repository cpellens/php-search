<?php

namespace SearchLib\Data;

use Iterator;

abstract class AbstractSearchableItem implements SearchableItemInterface
{
    protected int $id;

    /**
     * @inheritDoc
     */
    abstract public static function getSchemaName(): string;

    /**
     * @inheritDoc
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function read(): Iterator
    {
        $fields = static::getFields();

        foreach ($fields as $field) {
            yield $field => $this->$field;
        }
    }

    /**
     * @inheritDoc
     */
    abstract public static function getFields(): array|Iterator;

    /**
     * @inheritDoc
     */
    public function hydrate(Iterator|array $iterator): SearchableItemInterface
    {
        foreach ($iterator as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }
}
