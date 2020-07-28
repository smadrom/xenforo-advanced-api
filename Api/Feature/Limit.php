<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api\Feature;

use XF\Mvc\Entity\Finder;

/**
 * Class Limit
 * @package SM\AdvancedApi\Api\Feature
 */
class Limit extends AbstractFeature
{
    protected function feature(): Finder
    {
        $limit = $this->inputs['limit'];
        $offset = $this->inputs['offset'];

        $this->finder->limit($limit, $offset);

        return $this->finder;
    }
}