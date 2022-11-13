<?php

namespace SearchLib\Connectors;

use SearchLib\Actions\SearchActionInterface;
use SearchLib\Data\SearchableItemInterface;

interface SearchContextInterface
{
    /**
     * Returns the driver being used by searches
     *
     * @return \SearchLib\Connectors\SearchDriverInterface
     */
    public function getSearchDriver(): SearchDriverInterface;

    /**
     * Sets the driver used for searching
     *
     * @param \SearchLib\Connectors\SearchDriverInterface $driver
     * @return void
     */
    public function setSearchDriver(SearchDriverInterface $driver): void;

    /**
     * Adds an item to the collection
     *
     * @param \SearchLib\Data\SearchableItemInterface ...$item
     * @return void
     */
    public function store(SearchableItemInterface ...$item): void;

    /**
     * Returns whether to automatically flush the buffer after it is modified
     *
     * @return bool
     */
    public function getAutoFlush(): bool;

    /**
     * Sets whether to automatically flush the buffer as it is modified
     *
     * @param bool $autoPersist
     * @return void
     */
    public function setAutoFlush(bool $autoPersist = true): void;

    /**
     * Flushes the buffer and persists the data
     *
     * @return int
     */
    public function flush(): int;

    /**
     * @param string $query
     * @param string ...$itemClass
     * @return \SearchLib\Actions\SearchActionInterface
     */
    public function search(string $query, string ...$itemClass): SearchActionInterface;

    /**
     * Enqueue a custom action
     *
     * @param \SearchLib\Actions\SearchActionInterface $action
     * @return void
     */
    public function push(SearchActionInterface $action): void;

    /**
     * @param string|\SearchLib\Data\SearchableItemInterface $item
     * @return void
     */
    public function createSchema(string|SearchableItemInterface $item): void;
}
