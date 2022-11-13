<?php

namespace SearchLib\Connectors\Typesense;

use JsonSerializable;
use SearchLib\Connectors\Typesense\Configuration\Node;

class Configuration implements JsonSerializable
{
    public function __construct(private string $apiKey, protected array $nodes = [])
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'api_key' => $this->getApiKey(),
            'nodes'   => array_map(fn(Node $node) => $node->jsonSerialize(), $this->nodes)
        ];
    }

    /**
     * @return string
     */
    protected function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param \SearchLib\Connectors\Typesense\Configuration\Node $node
     */
    public function addNode(Node $node): void
    {
        $this->nodes[] = $node;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }
}
