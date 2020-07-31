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

    public function row(array $row): Row
    {
        $this->response = $row;

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
