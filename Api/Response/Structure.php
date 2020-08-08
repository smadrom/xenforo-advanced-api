<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api\Response;

/**
 * Class Structure
 * @package SM\AdvancedApi\Api\Response
 */
class Structure
{
    private $response;

    public function structure(\XF\Mvc\Entity\Structure $structure): Structure
    {
        $this->response = $structure;

        return $this;
    }

    public function columns(array $columns): Structure
    {
        $this->response = $columns;

        return $this;
    }

    public function relations(array $relations): Structure
    {
        $this->response = $relations;

        return $this;
    }

    public function build(): array
    {
        return (array)$this->response;
    }
}
