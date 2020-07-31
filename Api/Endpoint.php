<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api;

use SM\AdvancedApi\Api\Exception\NotFound;
use SM\AdvancedApi\Api\Response\Response;
use XF\Api\Controller\AbstractController;
use XF\Api\Mvc\Reply\ApiResult;
use XF\Mvc\ParameterBag;

/**
 * Class Endpoint
 * @package SM\AdvancedApi\Api
 */
class Endpoint extends AbstractController
{
    private $entity;
    private $filter;
    private $finder;

    /**
     * @param $action
     * @param ParameterBag $params
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->entity = $params->get('entity');
        $this->filter = $this->app->request()->filter(AdvancedApi::FILTER);

        $entityClass = ucfirst($this->entity);

        if (!class_exists('XF\\Entity\\' . $entityClass)) {
            throw new NotFound('Entity ' . $entityClass . ' not found');
        }

        $this->finder = $this->em()->getFinder('XF:' . $entityClass, false);

        $this->setupFeatures();
    }

    /**
     * @return ApiResult
     */
    public function actionGet(): ApiResult
    {
        $rows = $this->finder->fetch()->toArray();

        $result = Response::init('rows')
            ->entity($this->entity)
            ->href($this->app->router()->buildLink('canonical:entity') . $this->entity)
            ->size($this->finder->total())
            ->limit($this->filter['limit'])
            ->offset($this->filter['offset'])
            ->rows($rows)
            ->build();

        return $this->apiResult($result);
    }

    private function setupFeatures()
    {
        AdvancedApi::run($this->finder, $this->filter);
    }
}