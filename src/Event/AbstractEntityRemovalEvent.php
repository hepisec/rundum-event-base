<?php

namespace Rundum\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author Hendrik Pilz <pilz@rundum.digital>
 */
abstract class AbstractEntityRemovalEvent extends Event {

    protected $entity;

    public function __construct($entity = null) {
        $this->entity = $entity;
    }

    protected function setEntity($entity) {
        $this->entity = $entity;
    }

    public function getEntity() {
        return $this->entity;
    }

    public static function from(AbstractEntityRemovalEvent $src) {
        $event = new static();
        $event->setEntity($src->getEntity());
        return $event;
    }

}
