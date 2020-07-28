<?php
declare(strict_types=1);

namespace SM\AdvancedApi;

use SM\AdvancedApi\Api\AdvancedApi;
use XF\Api\App;
use XF\Mvc\Entity\Finder;

/**
 * Class Listener
 * @package SM\AdvancedApi
 */
class Listener
{
    public static function apiFinderPreFetch(Finder $finder): Finder
    {
        $request = $finder->app()->request();

        if ($request->getRequestMethod() === 'get') {
            $finder = AdvancedApi::run($finder, $request->filter(AdvancedApi::FILTER));
        }

        return $finder;
    }

    /**
     * @param App $app
     * @return App
     */
    public static function apiLimitFeature(App $app): App
    {
        $limit = $app->request()->filter('limit', 'uint');

        if ($limit > 0) {
            $app->options()->offsetSet('discussionsPerPage', $limit);
        }

        return $app;
    }
}