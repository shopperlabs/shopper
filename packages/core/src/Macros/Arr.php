<?php

declare(strict_types=1);

namespace Shopper\Core\Macros;

use InvalidArgumentException;

class Arr
{
    /**
     * @param  array<array-key, mixed>  $tuples
     * @return array<array-key, mixed>
     */
    public static function permutate(array $tuples): array
    {
        if (empty($tuples)) {
            return [];
        }

        foreach ($tuples as $key => $values) {
            if (! is_array($values) || empty($values)) {
                throw new InvalidArgumentException("Each attribute must be a non-empty array. Problem with attribute: {$key}");
            }
        }

        $values = array_values($tuples);
        $keys = array_keys($tuples);

        $permutations = \Illuminate\Support\Arr::crossJoin(...$values);

        return array_map(fn (array $combination): array => array_combine($keys, $combination), $permutations);
    }

    /**
     * @param  array<array-key, mixed>  $permutation
     */
    public static function performPermutationIntoWord(array $permutation, string $key, string $separator = ' / '): string
    {
        return implode($separator, array_column($permutation, $key));
    }

    /**
     * @param  array<array-key, mixed>  $permutation
     * @return array<array-key, mixed>
     */
    public static function getPermutationIds(array $permutation): array
    {
        return array_column($permutation, 'id');
    }

    /**
     * @param  array<array-key, mixed>  $array
     * @param  array<array-key, mixed>  $compare
     * @return array<array-key, mixed>
     */
    public static function recursiveArrayDiffAssoc(array $array, array $compare): array
    {
        $difference = [];

        foreach ($array as $key => $value) {
            if (! array_key_exists($key, $compare)) {
                $difference[$key] = $value;
            } elseif (is_array($value) && is_array($compare[$key])) {
                $nestedDiff = self::recursiveArrayDiffAssoc($value, $compare[$key]);
                if (! empty($nestedDiff)) {
                    $difference[$key] = $nestedDiff;
                }
            } elseif ($value !== $compare[$key]) {
                $difference[$key] = $value;
            }
        }

        return $difference;
    }
}
