<?php

namespace App\Listener;

use App\Entity\AcademicYear;
use App\Entity\Categories;
use App\Entity\Contact;
use App\Entity\Level;
use App\Entity\Likes;
use App\Entity\Parameter;
use App\Entity\Slider;
use App\Entity\SocialNetwork;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber
{

    private $cacheManager;
    private $uploaderHelper;

    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper)
    {
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }

    public function getSubscribedEvents()
    {
        return [
            'preRemove',
            'preUpdate'
        ];
    }
 
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof AcademicYear) {
            return; 
        }elseif ($entity instanceof Categories) {
            return;
        }elseif ($entity instanceof Contact) {
            return;
        }elseif ($entity instanceof Level) {
            return;
        }elseif ($entity instanceof Parameter) {
            return;
        }elseif ($entity instanceof SocialNetwork) {
            return;
        }
        $this->cacheManager->remove($this->uploaderHelper->asset($entity,'imageFile'));

    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof AcademicYear) {
            return; 
        }elseif ($entity instanceof Categories) {
            return;
        }elseif ($entity instanceof Contact) {
            return;
        }elseif ($entity instanceof Level) {
            return;
        }elseif ($entity instanceof Parameter) {
            return;
        }elseif ($entity instanceof SocialNetwork) {
            return;
        }

        if ($entity->getImageFile() instanceof UploadedFile) {
            $this->cacheManager->remove($this->uploaderHelper->asset($entity,'imageFile'));
        }
    }
}
