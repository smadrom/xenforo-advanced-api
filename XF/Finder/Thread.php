<?php
declare(strict_types=1);

namespace SM\AdvancedApi\XF\Finder;

use XF;
use XF\Mvc\Entity\ArrayCollection;

/**
 * Class Thread
 * @package SM\AdvancedApi\XF\Finder
 */
class Thread extends XFCP_Thread
{
    /**
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return ArrayCollection [Entity]
     */
    public function fetch($limit = null, $offset = null)
    {
        XF::fire('finder_pre_fetch', [$this], XF::extension()->resolveExtendedClassToRoot($this));

        return parent::fetch($limit, $offset);
    }
}