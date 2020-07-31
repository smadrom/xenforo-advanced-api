<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api\Response;

/**
 * Class Rowss
 * @package SM\AdvancedApi\Api\Response
 */
class Rows
{
    private $response;

    public function rows(array $rows): Rows
    {
        $this->response['rows'] = $rows;

        return $this;
    }

    public function size(int $size): Rows
    {
        $this->response['meta']['size'] = $size;

        return $this;
    }

    public function limit(int $limit): Rows
    {
        $this->response['meta']['limit'] = $limit;

        return $this;
    }

    public function offset(int $offset): Rows
    {
        $this->response['meta']['offset'] = $offset;

        return $this;
    }

    public function entity(string $entity): Rows
    {
        $this->response['meta']['entity'] = $entity;

        return $this;
    }

    public function href(string $href): Rows
    {
        $this->response['meta']['href'] = $href;

        return $this;
    }

    /**
     * @return array
     */
    public function build(): array
    {
        return (array)$this->response;
    }
}
