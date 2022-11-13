<?php

namespace SearchLib\Connectors\Typesense\Processors;

use SearchLib\Actions\SearchActionInterface;
use SearchLib\Processors\AbstractProcessor;
use Typesense\Collection;

/**
 * @extends AbstractProcessor<\SearchLib\Connectors\Typesense\Driver>
 */
class StoreProcessor extends AbstractProcessor
{
    /**
     * @inheritDoc
     */
    public function process(SearchActionInterface $action): void
    {
        /** @var \SearchLib\Connectors\Typesense\Driver $driver */
        $driver  = $this->getDriver();
        $convert = $driver->getConvertAdapter();
        $client  = $driver->getClient();
        $items   = $action->read();

        static $collections = [];
        static $batches = [];

        /** @var \SearchLib\Data\SearchableItemInterface $item */
        foreach ($items as $item) {
            $document   = $convert->getDriverReadyItem($item);
            $collection = $collections[$item::class]
                ?? $collections[$item::class] = $client->getCollections()->offsetGet($item::getSchemaName());
            $batch      = $batches[$item::class] ?? $batches[$item::class] = [
                'collection' => &$collection,
                'items'      => []
            ];

            $batch['items'][]      = $document;
            $batches[$item::class] = $batch;
        }

        foreach ($batches as $class => $batch) {
            $collection = $batch['collection'];
            $items      = $batch['items'];

            unset($batches[$class]);
            $this->batchCreate($items, $collection);
        }
    }

    /**
     * @param array $items
     * @param \Typesense\Collection $collection
     * @return void
     * @throws \Http\Client\Exception
     * @throws \JsonException
     * @throws \Typesense\Exceptions\TypesenseClientError
     */
    private function batchCreate(array $items, Collection $collection): void
    {
        $collection->getDocuments()->import($items);
    }
}
