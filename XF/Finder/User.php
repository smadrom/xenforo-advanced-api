<?php
declare(strict_types=1);

namespace SM\AdvancedApi\XF\Finder;

use XF;
use XF\Mvc\Entity\ArrayCollection;

/**
 * Class User
 * @package SM\AdvancedApi\XF\Finder
 */
class User extends XFCP_User
{
    /**
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return ArrayCollection [Entity]
     */
    public function fetch($limit = null, $offset = null)
    {
        XF::fire('finder_pre_fetch', [$this]);

        return parent::fetch($limit, $offset);
    }
}