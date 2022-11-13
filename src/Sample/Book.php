<?php

namespace SearchLib\Sample;

use Iterator;
use SearchLib\Connectors\Typesense\Data\TypesenseItemInterface;
use SearchLib\Data\AbstractSearchableItem;

class Book extends AbstractSearchableItem implements TypesenseItemInterface
{
    public const STRING_FIELD_TITLE = 'title';
    public const STRING_FIELD_AUTHOR = 'author';
    public const STRING_NAME_SCHEMA = 'books';

    protected string $title;
    protected string $author;
    protected int $id;

    public static function getSchemaName(): string
    {
        return self::STRING_NAME_SCHEMA;
    }

    public static function getFields(): array|Iterator
    {
        return [self::STRING_FIELD_TITLE, self::STRING_FIELD_AUTHOR];
    }

    public static function getFieldMapping(): array
    {
        return [
            self::STRING_FIELD_TITLE  => 'string',
            self::STRING_FIELD_AUTHOR => 'string'
        ];
    }

    public static function getDefaultSortBy(): string
    {
        return self::STRING_FIELD_TITLE;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }
}
