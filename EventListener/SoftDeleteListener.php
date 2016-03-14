<?php
namespace Evence\Bundle\SoftDeleteableExtensionBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use HootMarketing\Bundle\ShopBundle\Entity\Shop;
use Symfony\Component\VarDumper\VarDumper;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Annotations\AnnotationReader;
use Evence\Bundle\SoftDeleteableExtensionBundle\Mapping\Annotation\onSoftDelete;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Evence\Bundle\SoftDeleteableExtensionBundle\Exception\OnSoftDeleteUnknownTypeException;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Soft delete listener class for onSoftDelete behaviour
 *
 * @author Ruben Harms <info@rubenharms.nl>
 * @link http://www.rubenharms.nl
 * @link https://www.github.com/RubenHarms
 */
class SoftDeleteListener
{
    use ContainerAwareTrait;

    /**
     *
     * @param LifecycleEventArgs $args            
     * @throws OnSoftDeleteUnknownTypeException
     */
    public function preSoftDelete(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $entity = $args->getEntity();
        
        $namespaces = $em->getConfiguration()
            ->getMetadataDriverImpl()
            ->getAllClassNames();
        
        $reader = new AnnotationReader();
        $accessor = PropertyAccess::createPropertyAccessor();
        
        foreach ($namespaces as $namespace) {
            
            $reflectionObject = new \ReflectionObject(new $namespace());
            foreach ($reflectionObject->getProperties() as $property) {
                if ($onDelete = $reader->getPropertyAnnotation($property, 'Evence\Bundle\SoftDeleteableExtensionBundle\Mapping\Annotation\onSoftDelete')) {
                    $objects = null;                    
                    if ($manyToOne = $reader->getPropertyAnnotation($property, 'Doctrine\ORM\Mapping\ManyToOne')) {
                        if ($entity instanceof $manyToOne->targetEntity) {
                            $objects = $em->getRepository($namespace)->findBy([
                                $property->name => $entity
                            ]);
                        }
                    }
                    if ($objects) {
                        foreach ($objects as $object) {
                            if (strtoupper($onDelete->type) == 'SET NULL') {
                                $accessor->setValue($object, $property->name, null);
                            } elseif (strtoupper($onDelete->type) == 'CASCADE') {
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