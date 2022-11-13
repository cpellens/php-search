<?php

namespace SearchLib\Connectors\Typesense\Data;

use SearchLib\Data\SearchableItemInterface;

interface TypesenseItemInterface extends SearchableItemInterface
{
    /**
     * @return array<string, string>
     */
    public static function getFieldMapping(): array;

    /**
     * @return string
     */
    public static function getDefaultSortBy(): string;
}
