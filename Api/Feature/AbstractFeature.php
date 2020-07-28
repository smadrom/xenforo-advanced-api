<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api\Feature;

use XF\Mvc\Entity\Finder;
use function array_key_exists;

/**
 * Class AbstractFeature
 * @package SM\AdvancedApi\Api\Feature
 */
abstract class AbstractFeature
{
    protected $finder;
    protected $columns;

    /***
     * @var array $inputs
     */
    protected $inputs;

    /**
     * AbstractFeature constructor.
     * @param Finder $finder
     * @param array $inputs
     */
    private function __construct(Finder $finder, array $inputs)
    {
        $this->inputs = $inputs;
        $this->finder = $finder;
        $this->columns = $this->finder->getStructure()->columns;
    }

    /**
     * @param Finder $finder
     * @param array $inputs
     * @return mixed
     */
    public static function run(Finder $finder, array $inputs): Finder
    {
        $self = new static($finder, $inputs);
        return $self->feature();
    }

    abstract protected function feature(): Finder;

    protected function isValid(array $keys, array $match): bool
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $match) || $match[$key] === '') {
                return false;
            }
        }

        return true;
    }

    protected function isColumnExists(string $filterColumn): bool
    {
        foreach ($this->columns as $column => $params) {
            if ($filterColumn === $column) {
                return true;
            }
        }
        return false;
    }
}