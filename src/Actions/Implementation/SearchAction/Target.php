<?php

namespace SearchLib\Actions\Implementation\SearchAction;

use Iterator;
use SearchLib\Data\SearchableItemInterface;

class Target implements \SearchLib\Data\SearchableItemInterface
{
    public const STRING_NAME_SCHEMA = 'search';
    public const STRING_FIELD_QUERY_BY = 'query_by';
    public const STRING_FIELD_QUERY = 'q';

    private string $schema;
    private array|Iterator $searchFields;

    public function __construct(protected string $className)
    {
        if (is_subclass_of($this->className, SearchableItemInterface::class)) {
            $this->schema       = $this->className::getSchemaName();
            $this->searchFields = $this->className::getFields();
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSchemaName(): string
    {
        return self::STRING_NAME_SCHEMA;
    }

    /**
     * @inheritDoc
     */
    public static function getFields(): array|Iterator
    {
        return [self::STRING_FIELD_QUERY_BY, self::STRING_FIELD_QUERY];
    }

    /**
     * @inheritDoc
     */
    public function read(): Iterator
    {
        yield self::STRING_FIELD_QUERY_BY => $this->getSearchFields();
    }

    private function getSearchFields(): array
    {
        if (!is_array($this->searchFields)) {
            $this->searchFields = iterator_to_array($this->searchFields);
        }

        return $this->searchFields;
    }

    /**
     * @inheritDoc
     */
    public function getId(): int|string
    {
        return $this->schema;
    }

    /**
     * @inheritDoc
     */
    public function hydrate(Iterator|array $iterator): \SearchLib\Data\SearchableItemInterface
    {
        return $this;
    }
}
