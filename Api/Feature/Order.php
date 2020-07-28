<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api\Feature;

use XF\Mvc\Entity\Finder;

/**
 * Class Order
 * @package SM\AdvancedApi\Api\Feature
 */
class Order extends AbstractFeature
{
    protected function feature(): Finder
    {
        preg_match_all('/(?<column>\w*\.?\w*)[,](?<direction>asc|desc);?/u', $this->inputs['orderby'], $matches, PREG_SET_ORDER);

        $columns = [];

        foreach ($matches as $match) {
            if ($this->isValid(['column', 'direction'], $match) && $this->isColumnExists($match['column'])) {

                $column = [
                    'column' => $match['column'],
                    'direction' => $match['direction'],
                ];

                $columns[$match['column']][] = $column;
            }
        }

        foreach ($columns as $conditions) {
            foreach ($conditions as $condition) {
                $this->finder->order($condition['column'], $condition['direction']);
            }
        }

        return $this->finder;
    }
}