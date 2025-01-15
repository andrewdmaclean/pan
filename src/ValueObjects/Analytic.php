<?php

declare(strict_types=1);

namespace Pan\ValueObjects;

/**
 * @internal
 */
final class Analytic
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var int
     */
    public int $impressions;

    /**
     * @var int
     */
    public int $hovers;

    /**
     * @var int
     */
    public int $clicks;

    /**
     * Creates a new Analytic instance.
     */
    public function __construct(
        int $id,
        string $name,
        int $impressions,
        int $hovers,
        int $clicks
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->impressions = $impressions;
        $this->hovers = $hovers;
        $this->clicks = $clicks;
    }

    /**
     * Prevents property reassignment after construction.
     */
    public function __set(string $name, $value): void
    {
        throw new \LogicException('Cannot modify readonly property: ' . $name);
    }

    /**
     * Returns the analytic as an array.
     *
     * @return array{id: int, name: string, impressions: int, hovers: int, clicks: int}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'impressions' => $this->impressions,
            'hovers' => $this->hovers,
            'clicks' => $this->clicks,
        ];
    }
}
