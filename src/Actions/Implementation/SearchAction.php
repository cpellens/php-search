<?php

namespace SearchLib\Actions\Implementation;

use Closure;
use SearchLib\Actions\SearchActionInterface;
use SearchLib\Data\SearchableItemInterface;
use SplQueue;

class SearchAction extends AbstractAction
{
    protected string $queryString;
    protected \SplStack $callbacks;

    public function __construct(SplQueue $queue = new SplQueue())
    {
        parent::__construct($queue);

        $this->callbacks = new \SplStack();

        $this->setType(SearchActionInterface::TYPE_SEARCH);
    }

    /**
     * @param \Closure $closure
     * @return $this
     */
    public function onEachResult(Closure $closure): static
    {
        $this->callbacks->push($closure);

        return $this;
    }

    /**
     * @param \SearchLib\Data\SearchableItemInterface $item
     * @return bool
     */
    public function onResult(SearchableItemInterface $item): bool
    {
        foreach ($this->callbacks as $callback) {
            $result = call_user_func($callback, $item);

            if ($result === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function getQueryString(): string
    {
        return $this->queryString;
    }

    /**
     * @param string $queryString
     */
    public function setQueryString(string $queryString): void
    {
        $this->queryString = $queryString;
    }
}
