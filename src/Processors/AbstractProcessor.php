<?php

namespace SearchLib\Processors;

use SearchLib\Actions\SearchActionInterface;
use SearchLib\Connectors\SearchDriverInterface;

/**
 * @template T of SearchDriverInterface
 * @template-implements T
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * @var T
     */
    private SearchDriverInterface $driver;

    public function __construct(SearchDriverInterface $driver)
    {
        $this->setDriver($driver);
    }

    /**
     * @inheritDoc
     */
    abstract public function process(SearchActionInterface $action): void;

    /**
     * @inheritDoc
     */
    public function getDriver(): SearchDriverInterface
    {
        return $this->driver;
    }

    /**
     * @inheritDoc
     */
    public function setDriver(SearchDriverInterface $driver): void
    {
        $this->driver = $driver;
    }
}
