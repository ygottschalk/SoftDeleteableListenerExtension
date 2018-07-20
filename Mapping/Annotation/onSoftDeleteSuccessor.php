<?php

namespace Evence\Bundle\SoftDeleteableExtensionBundle\Mapping\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * onSoftDeleteSuccessor annotation for onSoftDelete behavioral extension.
 *
 * @Annotation
 * @Target("PROPERTY")
 *
 * @author Ruben Harms <info@rubenharms.nl>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
final class onSoftDeleteSuccessor extends Annotation
{
}
