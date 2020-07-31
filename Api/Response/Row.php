<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api\Response;

/**
 * Class Row
 * @package SM\AdvancedApi\Api\Response
 */
class Row
{
    private $response;

    public function rows(array $rows): Row
    {
        $this->response['rows'] = $rows;

        return $this;
    }

    public function size(int $size): Row
    {
        $this->response['meta']['size'] = $size;

        return $this;
    }

    public function limit(int $limit): Row
    {
        $this->response['meta']['limit'] = $limit;

        return $this;
    }

    public function offset(int $offset): Row
    {
        $this->response['meta']['offset'] = $offset;

        return $this;
    }

    public function entity(string $entity): Row
    {
        $this->response['meta']['entity'] = $entity;

        return $this;
    }

    public function href(string $href): Row
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
