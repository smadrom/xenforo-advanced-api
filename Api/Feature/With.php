<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api\Feature;

use XF\Mvc\Entity\Finder;

/**
 * Class Expand
 * @package SM\AdvancedApi\Api\Feature
 */
class With extends AbstractFeature
{
    protected function feature(): Finder
    {
        $structure = $this->finder->getStructure();

        return $this->finder;
    }
}