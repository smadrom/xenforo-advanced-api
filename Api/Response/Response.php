<?php
declare(strict_types=1);

namespace SM\AdvancedApi\Api\Response;

use RuntimeException;

/**
 * Class Row
 * @package SM\AdvancedApi\Api\Response
 */
class Response
{
    /**
     * @param string $name
     * @return Error|Row|Rows|Structure
     */
    public static function init(string $name)
    {
        $class = __NAMESPACE__ . '\\' . ucfirst($name);

        if (class_exists($class)) {
            return new $class();
        }

        throw new RuntimeException('Response class ' . $class . ' not found');
    }
}
