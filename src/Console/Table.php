<?php

declare(strict_types=1);

namespace Pan\Console;

use Symfony\Component\Console\Helper\Table as BaseTable;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
final class Table
{
    /**
     * @var OutputInterface
     */
    private OutputInterface $output;

    /**
     * Creates a new instance of the table.
     */
    public function __construct(
        OutputInterface $output,
    ) {
        $this->output = $output;
    }

    /**
     * Displays the table.
     *
     * @param  array<int, string>  $headers
     * @param  array<int, array<int, string>>  $rows
     */
    public function display(array $headers, array $rows): void
    {
        $this->output->writeln('');

        $table = new BaseTable($this->output);

        $table->setStyle('compact');

        $table->setHeaders(array_map(
            fn($header): string => "   <fg=#FC6AFF;options=bold>$header</>",
            $headers
        ));

        $table->setRows(array_map(
            fn($row): array => array_map(
                fn($cell): string => "   $cell",
                $row
            ),
            $rows
        ));

        $table->render();

        $this->output->writeln('');
    }

    /**
     * Prevent property reassignment after construction.
     */
    public function __set(string $name, $value): void
    {
        throw new \LogicException('Cannot modify readonly property: ' . $name);
    }
}
