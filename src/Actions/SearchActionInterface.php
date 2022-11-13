<?php

namespace SearchLib\Actions;

use SearchLib\Data\SearchableItemInterface;

interface SearchActionInterface
{
    public const TYPE_WRITE = 1;
    public const TYPE_READ = 2;
    public const TYPE_DELETE = self::TYPE_WRITE | 4;
    public const TYPE_INDEX = self::TYPE_READ | 8;
    public const TYPE_SEARCH = self::TYPE_READ | 16;
    public const TYPE_IMPORT = self::TYPE_WRITE | 32;
    public const TYPE_STORE = self::TYPE_WRITE | 64;
    public const TYPE_MIGRATION = self::TYPE_WRITE | 128;

    /**
     * Returns the type of interaction
     *
     * @return int
     */
    public function getType(): int;

    /**
     * Updates this action to tell the driver what type of transaction this is
     *
     * @param int $type
     * @return void
     */
    public function setType(int $type): void;

    /**
     * Appends an item to this queue
     *
     * @param \SearchLib\Data\SearchableItemInterface $item
     * @return void
     */
    public function write(SearchableItemInterface $item): void;

    /**
     * Pops the first item from the queue.
     * Returns FALSE when the queue is empty
     *
     * @return \Iterator<SearchableItemInterface>
     */
    public function read(): \Iterator;
}
