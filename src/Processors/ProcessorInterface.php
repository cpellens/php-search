<?php

namespace SearchLib\Processors;

use SearchLib\Actions\SearchActionInterface;
use SearchLib\Connectors\SearchDriverInterface;

/**
 * @template T of SearchDriverInterface
 */
interface ProcessorInterface
{
    /**
     * @param \SearchLib\Actions\SearchActionInterface $action
     * @return void
     */
    public function process(SearchActionInterface $action): void;

    /**
     * @param T $driver
     * @return void
     */
    public function setDriver(SearchDriverInterface $driver): void;

    /**
     * @return T
     */
    public function getDriver(): SearchDriverInterface;
}
