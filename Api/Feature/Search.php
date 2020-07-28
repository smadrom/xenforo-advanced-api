<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api\Feature;

use XF\Mvc\Entity\Finder;

/**
 * Class Filter
 * @package SM\AdvancedApi\Api\Feature
 */
class Search extends AbstractFeature
{
    protected function feature(): Finder
    {
        $conditions = [];

        foreach ($this->columns as $column => $params) {
            if ($params['type'] === 5) {
                $conditions[] = [
                    $column,
                    'like',
                    $this->finder->escapeLike($this->inputs['search'], '%?%')
                ];
            }
        }

        $this->finder->whereOr($conditions);

        return $this->finder;
    }
}