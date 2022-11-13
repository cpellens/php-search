<?php

namespace SearchLib\Data;

use Iterator;

interface SearchableItemInterface
{
    /**
     * Returns the name of the Schema for this item
     *
     * @return string
     */
    public static function getSchemaName(): string;

    /**
     * Returns a list of the fields on this item
     *
     * @return array<string>|Iterator<string>
     */
    public static function getFields(): array|Iterator;

    /**
     * Returns the unique ID of this item
     *
     * @return int|string
     */
    public function getId(): int|string;

    /**
     * Reads this item property by property
     *
     * @return \Iterator<string|int, mixed>
     */
    public function read(): Iterator;

    /**
     * @param array|\Iterator $iterator
     * @return $this
     */
    public function hydrate(array|Iterator $iterator): self;
}
