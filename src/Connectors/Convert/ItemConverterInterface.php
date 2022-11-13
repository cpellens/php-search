<?php

namespace SearchLib\Connectors\Convert;

use SearchLib\Data\SearchableItemInterface;

/**
 * @template T
 */
interface ItemConverterInterface
{
    /**
     * Converts a searchable item to a driver-ready interface
     *
     * @param \SearchLib\Data\SearchableItemInterface $item
     * @return T
     */
    public function getDriverReadyItem(SearchableItemInterface $item): mixed;

    /**
     * Converts a driver-ready interface to a searchable item
     *
     * @param T $item
     * @return \SearchLib\Data\SearchableItemInterface
     */
    public function getItem(mixed $item): SearchableItemInterface;

    /**
     * @param string $className
     * @return string
     */
    public function getClassAlias(string $className): string;

    /**
     * @param string $className
     * @param string $alias
     * @return void
     */
    public function setClassAlias(string $className, string $alias): void;
}
