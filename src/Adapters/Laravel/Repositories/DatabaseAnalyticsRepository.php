<?php

declare(strict_types=1);

namespace Pan\Adapters\Laravel\Repositories;

use Illuminate\Support\Facades\DB;
use Pan\Contracts\AnalyticsRepository;
use Pan\Enums\EventType;
use Pan\PanConfiguration;
use Pan\ValueObjects\Analytic;
/**
 * @internal
 */
final class DatabaseAnalyticsRepository implements AnalyticsRepository
{
    /**
     * @var PanConfiguration
     */
    private PanConfiguration $config;

    /**
     * Creates a new analytics repository instance.
     */
    public function __construct(PanConfiguration $config)
    {
        $this->config = $config;
    }

    /**
     * Returns all analytics.
     *
     * @return array<int, Analytic>
     */
    public function all(): array
    {
        /** @var array<int, Analytic> $all */
        $all = DB::table('pan_analytics')->get()->map(fn (mixed $analytic): Analytic => new Analytic(
            id: (int) $analytic->id,
            name: $analytic->name,
            impressions: (int) $analytic->impressions,
            hovers: (int) $analytic->hovers,
            clicks: (int) $analytic->clicks,
        ))->toArray();

        return $all;
    }

    /**
     * Increments the given event for the given analytic.
     */
    public function increment(string $name, EventType $event): void
    {
        [
            'allowed_analytics' => $allowedAnalytics,
            'max_analytics' => $maxAnalytics,
        ] = $this->config->toArray();

        if (!empty($allowedAnalytics) && !in_array($name, $allowedAnalytics, true)) {
            return;
        }

        if (DB::table('pan_analytics')->where('name', $name)->count() === 0) {
            if (DB::table('pan_analytics')->count() < $maxAnalytics) {
                DB::table('pan_analytics')->insert(['name' => $name, $event->column() => 1]);
            }

            return;
        }

        DB::table('pan_analytics')->where('name', $name)->increment($event->column());
    }

    /**
     * Flush all analytics.
     */
    public function flush(): void
    {
        DB::table('pan_analytics')->truncate();
    }

    /**
     * Prevent property reassignment after construction.
     */
    public function __set(string $name, $value): void
    {
        throw new \LogicException('Cannot modify readonly property: ' . $name);
    }
}
