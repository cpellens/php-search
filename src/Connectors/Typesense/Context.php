<?php

namespace SearchLib\Connectors\Typesense;

use SearchLib\Actions\Implementation\CreateSchemaAction;
use SearchLib\Actions\Implementation\SearchAction;
use SearchLib\Actions\Implementation\StoreAction;
use SearchLib\Actions\SearchActionInterface;
use SearchLib\Connectors\SearchContextInterface;
use SearchLib\Connectors\SearchDriverInterface;
use SearchLib\Connectors\Typesense\Data\Schema;
use SearchLib\Connectors\Typesense\Data\TypesenseItemInterface;
use SearchLib\Data\SearchableItemInterface;
use SplQueue;

class Context implements SearchContextInterface
{
    /** @var \SplQueue<\SearchLib\Actions\SearchActionInterface> */
    private SplQueue $queue;

    public function __construct(protected SearchDriverInterface $driver, protected bool $autoPersist = false)
    {
        $this->queue = new SplQueue();

        $this->setSearchDriver($this->driver);
    }

    /**
     * @inheritDoc
     */
    public function setSearchDriver(SearchDriverInterface $driver): void
    {
        $this->driver = $driver;
    }

    /**
     * @inheritDoc
     */
    public function store(SearchableItemInterface ...$items): void
    {
        $storeAction = new StoreAction();
        foreach ($items as $item) {
            $storeAction->write($item);
        }
        $this->push($storeAction);
    }

    /**
     * @inheritDoc
     */
    public function push(SearchActionInterface $action): void
    {
        $this->queue->enqueue($action);

        if ($this->getAutoFlush()) {
            $this->flush();
        }
    }

    /**
     * @inheritDoc
     */
    public function getAutoFlush(): bool
    {
        return $this->autoPersist;
    }

    /**
     * @inheritDoc
     */
    public function flush(): int
    {
        $i = 0;

        while (!$this->queue->isEmpty()) {
            $action = $this->queue->dequeue();
            $this->getSearchDriver()->run($action);

            $i++;
        }

        return $i;
    }

    /**
     * @inheritDoc
     */
    public function getSearchDriver(): SearchDriverInterface
    {
        return $this->driver;
    }

    /**
     * @inheritDoc
     */
    public function setAutoFlush(bool $autoPersist = true): void
    {
        /**
         * If we are enabling auto persist, flush
         */
        if (!$this->getAutoFlush() && $autoPersist) {
            $this->flush();
        }

        $this->autoPersist = $autoPersist;
    }

    /**
     * @inheritDoc
     */
    public function search(string $query, string ...$itemClass): SearchActionInterface
    {
        $search = new SearchAction();
        $search->setQueryString($query);

        foreach ($itemClass as $className) {
            $search->write(new SearchAction\Target($className));
        }

        $this->push($search);

        return $search;
    }

    /**
     * @inheritDoc
     */
    public function createSchema(string|SearchableItemInterface $item): void
    {
        $className = is_string($item) ? $item : $item::class;

        if (is_subclass_of($className, TypesenseItemInterface::class)) {
            $schema = new Schema($className);

            $action = new CreateSchemaAction();
            $action->write($schema);

            $this->push($action);
        }
    }
}
