<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api;

use XF\Mvc\Entity\Finder;
use function array_key_exists;
/**
 * Class AdvancedApi
 * @package SM\AdvancedApi\Api
 */
class AdvancedApi
{
    const FILTER = [
        'filter' => '?str',
        'search' => '?str',
        'orderby' => '?str',
        'limit' => 'uint',
        'offset' => 'uint',
        'with' => '?str',
    ];

    /**
     * @param Finder $finder
     * @param array $inputs
     * @return mixed
     */
    public static function run(Finder $finder, array $inputs)
    {
        foreach ($inputs as $feature => $input) {
            if ($input !== null && array_key_exists($feature, self::FILTER)) {
                //hack: order
                if ($feature === 'orderby') {
                    $feature = 'order';
                }
                //hack: order
                $featureClass = 'SM\\AdvancedApi\\Api\\Feature\\' . ucfirst($feature);
                if (class_exists($featureClass)) {
                    $finder = $featureClass::run($finder, $inputs);
                }
            }
        }

        return $finder;
    }
}