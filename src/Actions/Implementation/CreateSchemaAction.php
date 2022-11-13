<?php

namespace SearchLib\Actions\Implementation;

use SearchLib\Actions\SearchActionInterface;
use SplQueue;

class CreateSchemaAction extends AbstractAction
{
    public function __construct(SplQueue $queue = new SplQueue())
    {
        parent::__construct($queue);

        $this->setType(SearchActionInterface::TYPE_MIGRATION);
    }
}
