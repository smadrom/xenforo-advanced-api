<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api\Feature;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Finder;

/**
 * Class Expand
 * @package SM\AdvancedApi\Api\Feature
 */
class With extends AbstractFeature
{
    public static $relations = [];

    protected function feature(): Finder
    {
        $regexGroup = $this->getRegexGroupForRelations();

        preg_match_all('/(?<relation>' . $regexGroup . '),?/u', $this->inputs['with'], $matches, PREG_SET_ORDER);

        $relations = [];

        foreach ($matches as $match) {
            if ($this->isValid(['relation'], $match)) {
                $relations[] = $match['relation'];
            }
        }

        $this->finder->with($relations);

        self::$relations = $relations;

        return $this->finder;
    }

    private function getRelations(): array
    {
        $structure = $this->finder->getStructure();

        $relations = [];

        foreach ($structure->relations as $relationName => $relation) {
            if ($relation['type'] === Entity::TO_ONE) {
                $relations[] = $relationName;
            }
        }

        return $relations;
    }

    private function getRegexGroupForRelations(): string
    {
        return implode('|', $this->getRelations());
    }
}