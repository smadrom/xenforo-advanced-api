<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api;

use SM\AdvancedApi\Api\Response\Response;
use XF\Api\Controller\AbstractController;
use XF\Api\Mvc\Reply\ApiResult;
use XF\Mvc\Entity\Finder;
use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\Exception;

/**
 * Class Endpoint
 * @package SM\AdvancedApi\Api
 */
class Endpoint extends AbstractController
{
    private $id;
    private $entity;
    private $entityClass;
    private $filter;
    /** @var Finder */
    private $finder;

    /**
     * @param $action
     * @param ParameterBag $params
     * @throws Exception
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertApiScopeByRequestMethod('entity:read');

        $this->id = $params->get('id');
        $this->entity = $params->get('entity');
        $this->entityClass = ucfirst($this->entity);

        $this->setupFinder();

        if ($this->id === null) {
            $this->setupFilter();
            $this->setupFeatures();
        }
    }

    /**
     * @return ApiResult
     * @throws Exception
     */
    public function actionGet(): ApiResult
    {
        if ($this->id !== null) {
            return $this->actionGetOne();
        }

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

    /**
     * @return ApiResult
     * @throws Exception
     */
    public function actionGetOne(): ApiResult
    {
        $entityStructure = $this->app->em()->getEntityStructure('XF:' . $this->entityClass);

        $row = $this->finder->where($entityStructure->primaryKey, '=', $this->id)->fetchOne();

        if ($row === null) {
            throw $this->exception(
                $this->error('Not found', 404)
            );
        }

        $result = Response::init('row')->row($row->toArray())->build();

        return $this->apiResult($result);
    }

    private function setupFilter()
    {
        $this->filter = $this->app->request()->filter(AdvancedApi::FILTER);
    }

    /**
     * @throws Exception
     */
    private function setupFinder()
    {
        if (!class_exists('XF\\Entity\\' . $this->entityClass)) {
            throw $this->exception(
                $this->error('Entity ' . $this->entityClass . ' not found', 404)
            );
        }

        $this->finder = $this->em()->getFinder('XF:' . $this->entityClass, false);
    }

    private function setupFeatures()
    {
        AdvancedApi::run($this->finder, $this->filter);
    }
}