<?php

declare(strict_types=1);

namespace Shopper\Cart\Pipelines;

use InvalidArgumentException;

final class CartPipeline
{
    /** @var list<array{position: string, anchor: class-string|null, step: class-string}> */
    private array $insertions = [];

    /**
     * @param  class-string  $anchor
     * @param  class-string  $step
     */
    public function insertAfter(string $anchor, string $step): void
    {
        $this->insertions[] = ['position' => 'after', 'anchor' => $anchor, 'step' => $step];
    }

    /**
     * @param  class-string  $anchor
     * @param  class-string  $step
     */
    public function insertBefore(string $anchor, string $step): void
    {
        $this->insertions[] = ['position' => 'before', 'anchor' => $anchor, 'step' => $step];
    }

    /**
     * @param  class-string  $step
     */
    public function append(string $step): void
    {
        $this->insertions[] = ['position' => 'append', 'anchor' => null, 'step' => $step];
    }

    /**
     * Build the final ordered step list from the configured base and the
     * registered insertions. Insertions apply in registration order, so two
     * add-ons anchoring on the same step keep a deterministic sequence. An
     * insertion anchored on a step that is not in the pipeline fails fast at
     * build time rather than silently dropping the add-on's behavior.
     *
     * @param  list<class-string>  $base
     * @return list<class-string>
     */
    public function build(array $base): array
    {
        $steps = $base;

        foreach ($this->insertions as $insertion) {
            if ($insertion['position'] === 'append') {
                $steps[] = $insertion['step'];

                continue;
            }

            $index = array_search($insertion['anchor'], $steps, true);

            if ($index === false) {
                throw new InvalidArgumentException(sprintf(
                    'Cannot insert the cart pipeline step [%s] %s [%s]: the anchor step is not in the pipeline.',
                    $insertion['step'],
                    $insertion['position'],
                    $insertion['anchor'],
                ));
            }

            $offset = $insertion['position'] === 'after' ? $index + 1 : $index;

            array_splice($steps, $offset, 0, [$insertion['step']]);
        }

        return $steps;
    }
}
