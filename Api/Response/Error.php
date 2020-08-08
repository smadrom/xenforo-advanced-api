<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api\Response;

/**
 * Class Error
 * @package SM\AdvancedApi\Api\Response
 */
class Error
{
    private $response;

    public function message(string $message): Error
    {
        $this->response['message'] = $message;

        return $this;
    }

    public function code(int $code): Error
    {
        $this->response['code'] = $code;

        return $this;
    }

    public function show(): void
    {
        echo json_encode($this->response);
    }

    public function build(): array
    {
        return (array)$this->response;
    }
}
