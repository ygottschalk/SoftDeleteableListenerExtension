<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('evence_soft_deleteable_extension_homepage', new Route('/hello/{name}', array(
    '_controller' => 'EvenceSoftDeleteableExtensionBundle:Default:index',
)));

return $collection;
