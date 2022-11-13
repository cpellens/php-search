<?php

namespace SearchLib\Actions\Implementation;

use SearchLib\Actions\SearchActionInterface;
use SearchLib\Data\SearchableItemInterface;
use SplQueue;

abstract class AbstractAction implements SearchActionInterface
{
    private int $type = 0;

    public function __construct(private SplQueue $queue = new SplQueue())
    {
    }

    /**
     * @inheritDoc
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @inheritDoc
     */
    public function write(SearchableItemInterface $item): void
    {
        $this->queue->enqueue($item);
    }

    /**
     * @inheritDoc
     */
    public function read(): \Iterator
    {
        $this->queue->rewind();
        while (!$this->queue->isEmpty()) {
            $node = $this->queue->dequeue();
            if ($node instanceof SearchableItemInterface) {
                yield $node;
            }
        }
    }
}
