<?php

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Parameter;


$container->setDefinition('evence.softdeletale.listener.softdelete', new Definition('Evence\Bundle\SoftDeleteableExtensionBundle\EventListener\SoftDeleteListener', array()))


->addMethodCall('setContainer', [
    new Reference('service_container')
])
->addTag('doctrine.event_listener', array(
    'event' => 'preSoftDelete'
));
