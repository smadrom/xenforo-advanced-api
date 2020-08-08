<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api;

use SM\AdvancedApi\Api\Feature\With;
use SM\AdvancedApi\Api\Response\Response;
use XF;
use XF\Api\Controller\AbstractController;
use XF\Api\Mvc\Reply\ApiResult;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Structure;
use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\Exception;
use function array_key_exists;

/**
 * Class Endpoint
 * @package SM\AdvancedApi\Api
 */
class Endpoint extends AbstractController
{
    private $id;
    private $entity;
    private $entityList = [];
    private $filter;
    /** @var Finder */
    private $finder;
    /** @var Structure */
    private $structure;

    /**
     * @throws Exception
     */
    public function actionGet(): ApiResult
    {
        if ($this->entity === null) {
            return $this->getEntityList();
        }

        if ($this->id === null) {
            return $this->getRows();
        }

        return $this->getRow();
    }

    public function actionGetColumns(): ApiResult
    {
        $result = Response::init('structure')
            ->columns($this->structure->columns)
            ->build();

        return $this->apiResult($result);
    }

    public function actionGetRelations(): ApiResult
    {
        $relations = [];

        foreach ($this->structure->relations as $relationName => $relation) {
            if ($relation['type'] === Entity::TO_ONE) {
                $relations[] = $relationName;
            }
        }

        $result = Response::init('structure')
            ->columns($relations)
            ->build();

        return $this->apiResult($result);
    }

    public function actionGetStructure(): ApiResult
    {
        $result = Response::init('structure')
            ->structure($this->structure)
            ->build();

        return $this->apiResult($result);
    }

    /**
     * @param $action
     * @param ParameterBag $params
     * @throws Exception
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertApiScopeByRequestMethod('entity');
        $this->id = $params->get('id');
        $this->entity = $params->get('entity');

        $this->scanEntityFolder();

        $this->setupFinder();

        $this->getStructure();

        $this->setupFilter();
        $this->setupFeatures();
    }

    private function getRows(): ApiResult
    {
        $data = $this->finder->fetch();

        $rows = [];

        foreach ($data as $row) {
            $rows[] = $this->formatRow($row);
        }

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
     * @throws Exception
     */
    private function getRow(): ApiResult
    {
        $row = $this->finder->where($this->structure->primaryKey, '=', $this->id)->fetchOne();

        if ($row === null) {
            throw $this->exception(
                $this->error('Not found', 404)
            );
        }

        $row = $this->formatRow($row);

        $result = Response::init('row')->row($row)->build();

        return $this->apiResult($result);
    }

    private function getEntityList(): ApiResult
    {
        $entities = [];

        foreach ($this->entityList as $key => $file) {
            $entities[] = $key;
        }

        $result = Response::init('row')->row($entities)->build();

        return $this->apiResult($result);
    }

    private function scanEntityFolder(): void
    {
        $entityDir = XF::getSourceDirectory() . '/XF/Entity';

        $scanDir = scandir($entityDir);

        $entityList = [];

        foreach ($scanDir as $file) {
            if (preg_match('/(.*Trait.*|.*Abstract.*|.*Interface.*|^\.\.$|^\.$)|(?<filename>.*)(\.php)/', $file, $matches) && isset($matches['filename'])) {
                $lowerCaseFilename = strtolower($matches['filename']);
                $entityList[$lowerCaseFilename] = [
                    'filename' => $matches['filename'],
                    'shortName' => 'XF:' . $matches['filename'],
                ];
            }
        }

        $this->entityList = $entityList;
    }

    private function setupFeatures(): void
    {
        Feature::run($this->finder, $this->filter);
    }

    private function setupFilter(): void
    {
        $filter = $this->app->request()->filter(Feature::FILTER);

        $filter['limit'] = $filter['limit'] === 0 ? 100 : $filter['limit'];

        $this->filter = $filter;
    }

    /**
     * @throws Exception
     */
    private function setupFinder(): void
    {
        $shortName = $this->findShortNameEntity();

        if (!$shortName) {
            throw $this->exception(
                $this->error('Entity ' . $this->entity . ' not found', 404)
            );
        }

        $this->finder = $this->em()->getFinder($shortName);
    }

    private function findShortNameEntity(): ?string
    {
        if (array_key_exists($this->entity, $this->entityList)) {
            return $this->entityList[$this->entity]['shortName'];
        }
        return null;
    }

    private function formatRow(Entity $row): array
    {
        $result = $row->toArray();

        $relations = With::$relations;

        foreach ($relations as $relation) {
            if (isset($row->{$relation})) {
                $result[$relation] = $row->{$relation};
            }
        }

        return $result;
    }

    private function getStructure(): void
    {
        $this->structure = $this->finder->getStructure();
    }
}