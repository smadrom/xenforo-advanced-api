<?php
declare(strict_types=1);

namespace SM\AdvancedApi;

use SM\AdvancedApi\Api\AdvancedApi;
use XF\Api\App;
use XF\Finder\Thread;
use XF\Finder\User;
use XF\Mvc\Entity\Finder;


/**
 * Class Listener
 * @package SM\AdvancedApi
 */
class Listener
{
    /**
     * @param Finder $finder
     * @return User|Thread
     */
    public static function apiFinderPreFetch(Finder $finder)
    {
        $request = $finder->app()->request();

        if ($request->getRequestMethod() === 'get') {
            $finder = AdvancedApi::run($finder, $request->filter(AdvancedApi::FILTER));
        }

        return $finder;
    }
}