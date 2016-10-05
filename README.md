# SoftDeleteableListenerExtensionBundle

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

Add the bundle to `app/AppKernel.php`:

``` php
# app/AppKernel.php

$bundles = array(
    ...
    new Evence\Bundle\SoftDeleteableExtensionBundle\EvenceSoftDeleteableExtensionBundle(),
);
```
