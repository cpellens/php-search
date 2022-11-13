<?php

namespace SearchLib\Connectors\Typesense;

use SearchLib\Connectors\Convert\ItemConverterInterface;
use SearchLib\Data\SearchableItemInterface;

class Converter implements ItemConverterInterface
{
    public const STRING_CLASS_KEY = '_class';

    public function __construct(protected array $aliases = [])
    {
    }

    /**
     * @param \SearchLib\Data\SearchableItemInterface $item
     * @return array<string|int, mixed>
     */
    public function getDriverReadyItem(SearchableItemInterface $item): array
    {
        $row                           = iterator_to_array($item->read());
        $row[static::STRING_CLASS_KEY] = get_class($item);

        return $row;
    }

    /**
     * @param mixed $item
     * @return \SearchLib\Data\SearchableItemInterface
     */
    public function getItem(mixed $item): SearchableItemInterface
    {
        $className = $item[static::STRING_CLASS_KEY];
        unset($item[static::STRING_CLASS_KEY]);

        if (!class_exists($className)) {
            $className = $this->getClassAlias($className);
        }

        /** @var \SearchLib\Data\SearchableItemInterface $item */
        $entity = new $className();

        return $entity->hydrate($item);
    }

    public function getClassAlias(string $className): string
    {
        return $this->aliases[$className] ?? $className;
    }

    public function setClassAlias(string $className, string $alias): void
    {
        $this->aliases[$className] = $alias;
    }
}
