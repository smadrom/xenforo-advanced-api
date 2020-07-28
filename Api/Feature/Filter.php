<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api\Feature;

use XF\Mvc\Entity\Finder;
use function array_key_exists;
use function count;

/**
 * Class Filter
 * @package SM\AdvancedApi\Api\Feature
 */
class Filter extends AbstractFeature
{
    protected function feature(): Finder
    {
        preg_match_all('/(?<column>\w*\.?\w*)(?<operator>>=|<=|=|>|<|~|!=)(?<value>\w*);?/u', $this->inputs['filter'], $matches, PREG_SET_ORDER);

        $columns = [];

        foreach ($matches as $match) {
            if ($this->isValid(['column', 'operator', 'value'], $match) && $this->isColumnExists($match['column'])) {

                $column = [
                    $match['column'],
                    $match['operator'],
                    $match['value'],
                ];

                if ($match['operator'] === '~') {
                    $column = $this->escapeLike($column);
                }

                $columns[$match['column']][] = $column;
            }
        }

        foreach ($columns as $conditions) {
            if (count($conditions) > 1) {
                $this->finder->whereOr($conditions);
                continue;
            }
            $this->finder->where($conditions);
        }

        return $this->finder;
    }

    /**
     * @param array $column
     * @param string $format
     * @return array
     */
    protected function escapeLike(array $column, $format = '%?%'): array
    {
        return [$column[0], 'like', $this->finder->escapeLike($column[2], $format)];
    }
}