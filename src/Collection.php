<?php

declare(strict_types=1);

namespace Xtompie\Collection;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Collection implements IteratorAggregate, ArrayAccess
{
    public static function of(array $collection): static
    {
        return new static($collection);
    }

    public static function ofEmpty(): static
    {
        return new static([]);
    }

    public function __construct(
        protected array $collection
    ) {
    }

    public function any(): bool
    {
        return (bool) $this->collection;
    }

    public function none(): bool
    {
        return !(bool) $this->collection;
    }

    public function contains(mixed $item): bool
    {
        return $this->filter(fn (mixed $i) => $i === $item)->any();
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function filter(?callable $fn = null): static
    {
        return new static(array_filter($this->collection, $fn, ARRAY_FILTER_USE_BOTH));
    }

    public function reject(callable $fn = null): static
    {
        return $this->filter(fn ($v, $k) => !$fn($v, $k));
    }

    public function only(array $keys): static
    {
        return $this->filter(fn ($v, $k) => in_array($k, $keys));
    }

    public function values(): static
    {
        return new static(array_values($this->collection));
    }

    public function keys(): static
    {
        return new static(array_keys($this->collection));
    }

    public function first(): mixed
    {
        foreach ($this->collection as $item) {
            return $item;
        }
        return null;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->collection);
    }

    public function map(callable $callback): static
    {
        return new static(array_map($callback, $this->collection, array_keys($this->collection)));
    }

    public function mapWithKeys(callable $callback): static
    {
        $result = [];
        foreach ($this->collection as $index => $item) {
            $result += $callback($item, $index);
        }
        return new static($result);
    }

    public function col(string $name): static
    {
        return $this->map(fn (array $item) => $item[$name]);
    }

    public function mapToArray(callable $callback): array
    {
        return array_map($callback, $this->collection, array_keys($this->collection));
    }

    public function offsetExists($offset): bool
    {
        return isset($this->collection[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return isset($this->collection[$offset]) ? $this->collection[$offset] : null;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->collection[] = $value;
        } else {
            $this->collection[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->collection[$offset]);
    }

    public function into(?string $class, ?callable $map = null): mixed
    {
        $result = $this->collection;
        if ($map) {
            $result = array_filter(array_map($map, $result, array_keys($result)));
        }
        if ($class) {
            $result = new $class($result);
        }
        return $result;
    }

    public function toArray(): array
    {
        return $this->collection;
    }

    public function all(): array
    {
        return $this->collection;
    }

    public function slice(int $offset, ?int $length = null): Collection
    {
        return new static(array_slice($this->collection, $offset, $length));
    }

    public function tuples(): static
    {
        return $this->filter(fn (mixed $tuple) => is_array($tuple));
    }

    public function tuplesIdentity(string $identifer): static
    {
        return $this
            ->tuples()
            ->filter(fn (array $tuple) => strlen($tuple[$identifer]) > 0)
        ;
    }

    public function tuplesUnique(string $identifer): static
    {
        return $this
            ->tuplesIdentity($identifer)
            ->mapWithKeys(fn (array $tuple) => [$tuple[$identifer] => $tuple])
            ->values()
        ;
    }

    public function implode(string $glue = ''): string
    {
        return implode($glue, $this->collection);
    }

    public function variant(mixed $val, mixed $default = null): mixed
    {
        if ($this->contains($val)) {
            return $val;
        }

        if ($default !== null) {
            return $default;
        }

        return $this->first();
    }

    public function variantKey(?string $key, mixed $defaultKey = null): mixed
    {
        if ($key !== null && $this->keys()->contains($key)) {
            return $this->collection[$key];
        }

        if ($defaultKey !== null) {
            return $this->collection[$defaultKey] ?? null;
        }

        return $this->first();
    }

    public function min(): mixed
    {
        return min($this->collection);
    }

    public function max(): mixed
    {
        return max($this->collection);
    }

    public function unique(): static
    {
        return new static(array_unique($this->collection));
    }

    public function merge(Collection $collection): static
    {
        return new static(array_merge($this->collection, $collection->toArray()));
    }

    public function random(): mixed
    {
        return $this->shuffle()->first();
    }

    public function shuffle(): static
    {
        $collection = $this->collection;
        shuffle($collection);
        return new static($collection);
    }

    public function reduce(callable $callback, mixed $initial): mixed
    {
        return array_reduce($this->all(), $callback, $initial);
    }

    public function flat(): static
    {
        return new static(array_merge(...$this->collection));
    }
}
