<?php

declare(strict_types=1);

namespace Evence\Bundle\SoftDeleteableExtensionBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Gedmo\SoftDeleteable\SoftDeleteableListener;

class SoftDeleteSubscriber extends SoftDeleteListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [ SoftDeleteableListener::PRE_SOFT_DELETE ];
    }
}
