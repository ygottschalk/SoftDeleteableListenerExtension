# SoftDeleteableListenerExtension

**Modified from the original to work standalone (WITHOUT Symfony)**

Extensions to Gedmo's softDeleteable listener which has had this issue reported since 2012 : https://github.com/Atlantic18/DoctrineExtensions/issues/505.

Provides the `onSoftDelete` functionality to an association of a doctrine entity. This functionality behaves like the SQL `onDelete` function  (when the owner side is deleted). *It will prevent Doctrine errors when a reference is soft-deleted.*

**Cascade delete the entity**

To (soft-)delete an entity when its parent record is soft-deleted :

```
 @Evence\onSoftDelete(type="CASCADE")
```

**Set reference to null (instead of deleting the entity)**

```
 @Evence\onSoftDelete(type="SET NULL")
```

**Replace reference by some property marked as successor (must be of same entity class)**

```
 @Evence\onSoftDelete(type="SUCCESSOR")
```

## Entity example

``` php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Evence\Bundle\SoftDeleteableExtensionBundle\Mapping\Annotation as Evence;

/*
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AdvertisementRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Advertisement
{

    ...

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Shop")
     * @ORM\JoinColumn(name="shop_id", referencedColumnName="id")
     * @Evence\onSoftDelete(type="CASCADE")
     */
    private $shop;

    ...
}
```

## Install

**Install with composer:**
```
composer require evence/soft-deleteable-extension-bundle
```

## Usage

To use this, you need to do 2 steps:

- Let `doctrine` load the required annotation files
- Subscribe / listen to the `preSoftDelete` event

### Let `doctrine` know about the annotations

**Method 1:** using composers autoloader
``` php
# bootstrap.php

/**
 * @var ClassLoader $loader
 */
$loader = require_once 'vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
```

**Method 2:** using to provided loader
``` php
# bootstrap.php

\Evence\Bundle\SoftDeleteableExtensionBundle\Loader\AnnotationLoader::registerAnnotations();
```

### Listen to the `preSoftDelete` event

```php
# bootstrap.php

$softDeleteSubscriber = new SoftDeleteSubscriber();
$entityManager->getEventManager()->addEventSubscriber($softDeleteSubscriber);
```
