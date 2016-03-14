# SoftDeleteableListenerExtensionBundle
Extensions to Gedmo's softDeleteable listener.

Provides the onSoftDelete functionality to an association of a doctrine entity. This function behaves like the SQL function onDelete (when the owner side is deleted). *This will prevent Doctrine errors when a reference is soft deleted.*

**Cascade delete entity**

```
 @Evence\onSoftDelete(type="CASCADE")
```

The example above (soft) deletes the advertisement when the shop is soft deleted


**Set reference to null (instead of delete the entity)**

```
 @Evence\onSoftDelete(type="SET NULL")
```

The example above set the reference to null.


## Entity example


``` php
<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Evence\Bundle\SoftDeleteableExtensionBundle\Mapping\Annotation as Evence;

  
 /*
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AdvertisementRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
  class Advertisement {
  
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
