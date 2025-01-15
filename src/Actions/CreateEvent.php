<?php

declare(strict_types=1);

namespace Pan\Actions;

use Pan\Contracts\AnalyticsRepository;
use Pan\Enums\EventType;

/**
 * @internal
 */
final class CreateEvent
{
    /**
     * Creates a new action instance.
     */
    public function __construct(
        private AnalyticsRepository $repository,
    ) {
        $this->repository = $repository;
    }

    /**
     * Executes the action.
     */
    public function handle(string $name, EventType $event): void
    {
        $this->repository->increment($name, $event);
    }

     /**
     * Prevents property reassignment after construction.
     */
    public function __set(string $name, $value): void
    {
        throw new \LogicException('Cannot modify readonly property: ' . $name);
    }
}
