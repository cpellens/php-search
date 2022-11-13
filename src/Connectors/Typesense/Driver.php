<?php

namespace SearchLib\Connectors\Typesense;

use Exception;
use SearchLib\Actions\SearchActionInterface;
use SearchLib\Connectors\Convert\ItemConverterInterface;
use SearchLib\Connectors\SearchDriverInterface;
use SearchLib\Connectors\Typesense\Processors\MigrationProcessor;
use SearchLib\Connectors\Typesense\Processors\SearchProcessor;
use SearchLib\Connectors\Typesense\Processors\StoreProcessor;
use SearchLib\Processors\ProcessorInterface;
use Typesense\Client;

class Driver implements SearchDriverInterface
{
    protected Client $client;
    protected Converter $converter;

    /**
     * @throws \Typesense\Exceptions\ConfigError
     */
    public function __construct(protected Configuration $configuration)
    {
        $this->client = new Client($this->configuration->jsonSerialize());
    }

    public function getConvertAdapter(): ItemConverterInterface
    {
        return $this->converter ?? $this->converter = new Converter();
    }

    public function run(SearchActionInterface $action): void
    {
        $processor = $this->getProcessor($action);
        $processor->process($action);
    }

    /**
     * @param \SearchLib\Actions\SearchActionInterface $action
     * @return \SearchLib\Processors\ProcessorInterface
     * @throws \Exception
     */
    private function getProcessor(SearchActionInterface $action): ProcessorInterface
    {
        switch ($action->getType()) {
            case SearchActionInterface::TYPE_STORE:
                return new StoreProcessor($this);
            case SearchActionInterface::TYPE_MIGRATION:
                return new MigrationProcessor($this);
            case SearchActionInterface::TYPE_SEARCH:
                return new SearchProcessor($this);
            default:
                break;
        }

        throw new Exception('Unsupported Action');
    }

    /**
     * @return \Typesense\Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
