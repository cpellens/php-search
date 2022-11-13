<?php

namespace SearchLib\Connectors;

use SearchLib\Actions\SearchActionInterface;
use SearchLib\Connectors\Convert\ItemConverterInterface;

interface SearchDriverInterface
{
    /**
     * Returns the convert adapter for this driver
     *
     * @return \SearchLib\Connectors\Convert\ItemConverterInterface
     */
    public function getConvertAdapter(): ItemConverterInterface;

    /**
     * Persist data actions the server
     *
     * @param \SearchLib\Actions\SearchActionInterface $action
     * @return void
     */
    public function run(SearchActionInterface $action): void;
}
