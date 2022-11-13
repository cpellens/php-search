<?php

namespace SearchLib\Connectors\Typesense\Data;

use Iterator;

class Schema implements \SearchLib\Data\SearchableItemInterface
{
    public const STRING_FIELD_NAME = 'name';
    public const STRING_FIELD_FIELDS = 'fields';
    public const STRING_FIELD_DEFAULT_SORTING_FIELD = 'default_sorting_field';

    protected string $name;
    protected string $sortBy;

    public function __construct(protected string $className)
    {
        if (is_subclass_of($this->className, TypesenseItemInterface::class)) {
            $this->setName($this->className::getSchemaName());
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSchemaName(): string
    {
        return 'collections';
    }

    /**
     * @inheritDoc
     */
    public static function getFields(): array|Iterator
    {
        return [
            self::STRING_FIELD_NAME,
            self::STRING_FIELD_FIELDS,
            self::STRING_FIELD_DEFAULT_SORTING_FIELD
        ];
    }

    /**
     * @inheritDoc
     */
    public function getId(): int|string
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function read(): Iterator
    {
        yield self::STRING_FIELD_NAME => $this->getName();
        yield self::STRING_FIELD_FIELDS => $this->getFieldMapping();

        if (isset($this->sortBy)) {
            yield self::STRING_FIELD_DEFAULT_SORTING_FIELD => $this->getSortBy();
        }
    }

    private function getFieldMapping(): array
    {
        $map = [];

        if (is_subclass_of($this->className, TypesenseItemInterface::class)) {
            $map = $this->className::getFieldMapping();

            foreach ($map as $name => $type) {
                $map[] = compact('name', 'type');
                unset($map[$name]);
            }
        }

        return $map;
    }

    /**
     * @return string
     */
    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    /**
     * @param string $sortBy
     */
    public function setSortBy(string $sortBy): void
    {
        $this->sortBy = $sortBy;
    }

    /**
     * @inheritDoc
     */
    public function hydrate(Iterator|array $iterator): \SearchLib\Data\SearchableItemInterface
    {
        foreach ($iterator as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }
}
