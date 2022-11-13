<?php

namespace SearchLib\Connectors\Typesense\Processors;

use SearchLib\Actions\Implementation\SearchAction\Target;
use SearchLib\Actions\SearchActionInterface;
use SearchLib\Processors\AbstractProcessor;

class SearchProcessor extends AbstractProcessor
{
    /**
     * @inheritDoc
     * @param \SearchLib\Actions\Implementation\SearchAction $action
     * @throws \Http\Client\Exception
     * @throws \Typesense\Exceptions\TypesenseClientError
     */
    public function process(SearchActionInterface $action): void
    {
        /** @var \SearchLib\Connectors\Typesense\Driver $driver */
        $driver    = $this->getDriver();
        $client    = $driver->getClient();
        $converter = $driver->getConvertAdapter();
        $items     = $action->read();

        /** @var \SearchLib\Actions\Implementation\SearchAction\Target $item */
        foreach ($items as $item) {
            $conversion   = $converter->getDriverReadyItem($item);
            $searchParams = [
                Target::STRING_FIELD_QUERY_BY => implode(',', $conversion[Target::STRING_FIELD_QUERY_BY]),
                Target::STRING_FIELD_QUERY    => $action->getQueryString()
            ];
            $collection   = $client->getCollections()->offsetGet($item->getId());
            $currentPage  = 0;
            $continue     = true;

            do {
                $result      = $collection->getDocuments()->search(
                    array_merge(
                        $searchParams,
                        [
                            'page' => $currentPage + 1
                        ]
                    )
                );
                $currentPage = intval($result['page'] ?? 0);

                if (isset($result['hits'])) {
                    foreach ($result['hits'] as $hit) {
                        $data   = $hit['document'];
                        $entity = $converter->getItem($data);

                        $continue = $action->onResult($entity);
                    }
                }
            } while (!empty($result['hits']) && $continue);
        }
    }
}
