<?php

namespace SearchLib\Actions\Implementation;

use SearchLib\Actions\SearchActionInterface;

class StoreAction extends AbstractAction
{
    public function __construct()
    {
        parent::__construct();

        $this->setType(SearchActionInterface::TYPE_STORE);
    }
}
