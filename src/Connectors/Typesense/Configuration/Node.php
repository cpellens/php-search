<?php

namespace SearchLib\Connectors\Typesense\Configuration;

class Node implements \JsonSerializable
{
    public function __construct(
        protected string $host = 'localhost',
        protected int $port = 8108,
        protected string $protocol = 'http'
    ) {
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     */
    public function setProtocol(string $protocol): void
    {
        $this->protocol = $protocol;
    }

    public function jsonSerialize(): array
    {
        return [
            'host'     => $this->host,
            'port'     => $this->port,
            'protocol' => $this->protocol
        ];
    }
}
