<?php

namespace Evence\Bundle\SoftDeleteableExtensionBundle\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Evence\Bundle\SoftDeleteableExtensionBundle\Exception\OnSoftDeleteUnknownTypeException;
use Evence\Bundle\SoftDeleteableExtensionBundle\Mapping\Annotation\onSoftDelete;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Soft delete listener class for onSoftDelete behaviour.
 *
 * @author Ruben Harms <info@rubenharms.nl>
 *
 * @link http://www.rubenharms.nl
 * @link https://www.github.com/RubenHarms
 */
class SoftDeleteListener
{
    use ContainerAwareTrait;

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws OnSoftDeleteUnknownTypeException
     */
    public function preSoftDelete(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $entity = $args->getEntity();

        $entityReflection = new \ReflectionObject($entity);

        $namespaces = $em->getConfiguration()
            ->getMetadataDriverImpl()
            ->getAllClassNames();

        $reader = new AnnotationReader();
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($namespaces as $namespace) {
            $reflectionClass = new \ReflectionClass($namespace);
            if ($reflectionClass->isAbstract()) {
                continue;
            }

            $reflectionObject = new \ReflectionObject(new $namespace());
            foreach ($reflectionObject->getProperties() as $property) {
                if ($onDelete = $reader->getPropertyAnnotation($property, 'Evence\Bundle\SoftDeleteableExtensionBundle\Mapping\Annotation\onSoftDelete')) {
                    $objects = null;
                    if ($manyToOne = $reader->getPropertyAnnotation($property, 'Doctrine\ORM\Mapping\ManyToOne')) {
                        $ns = $manyToOne->targetEntity;
                        if (!preg_match('#^\\\#', $ns)) {
                            $ns = $entityReflection->getNamespaceName().'\\'.$ns;
                        }

                        if ($entity instanceof $ns) {
                            $objects = $em->getRepository($namespace)->findBy(array(
                                $property->name => $entity,
                            ));
                        }
                    }

                    if ($objects) {
                        foreach ($objects as $object) {
                            if (strtoupper($onDelete->type) === 'SET NULL') {
                                $accessor->setValue($object, $property->name, null);
                            } elseif (strtoupper($onDelete->type) === 'CASCADE') {
                                $em->remove($object);
                            } else {
                                throw new OnSoftDeleteUnknownTypeException($onDelete->type);
                            }
                        }
                    }
                }
            }
        }
    }
}
