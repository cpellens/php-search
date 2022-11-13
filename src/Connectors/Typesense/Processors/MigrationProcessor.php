<?php

namespace SearchLib\Connectors\Typesense\Processors;

use SearchLib\Actions\SearchActionInterface;
use SearchLib\Connectors\Typesense\Converter;
use SearchLib\Processors\AbstractProcessor;

class MigrationProcessor extends AbstractProcessor
{
    public function process(SearchActionInterface $action): void
    {
        /** @var \SearchLib\Connectors\Typesense\Driver $driver */
        $driver  = $this->getDriver();
        $convert = $driver->getConvertAdapter();
        $client  = $driver->getClient();
        $items   = $action->read();

        foreach ($items as $item) {
            $di = $convert->getDriverReadyItem($item);
            unset($di[Converter::STRING_CLASS_KEY]);

            $client->getCollections()->create($di);
        }
    }
}
