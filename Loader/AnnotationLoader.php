<?php

declare(strict_types=1);

namespace Evence\Bundle\SoftDeleteableExtensionBundle\Loader;

use Doctrine\Common\Annotations\AnnotationRegistry;

class AnnotationLoader
{
    public static function registerAnnotations(): void
    {
        AnnotationRegistry::registerFile(
            dirname(__FILE__, 2) . '/Mapping/Annotation/onSoftDelete.php'
        );
        AnnotationRegistry::registerFile(
            dirname(__FILE__, 2) . '/Mapping/Annotation/onSoftDeleteSuccessor.php'
        );
    }
}
