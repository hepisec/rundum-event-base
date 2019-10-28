<?php

namespace Rundum\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author Hendrik Pilz <pilz@rundum.digital>
 */
abstract class AbstractEntityChangeEvent extends Event {

    protected $entity;
    protected $isNew;

    public function __construct($entity = null, bool $isNew = false) {
        $this->entity = $entity;
        $this->isNew = $isNew;
    }

    protected function setEntity($entity): void {
        $this->entity = $entity;
    }

    public function getEntity() {
        return $this->entity;
    }

    protected function setNew(bool $isNew): void {
        $this->isNew = $isNew;
    }

    public function isNew(): bool {
        return $this->isNew;
    }

    /**
     * Return a new event based on $src
     *
     * @param AbstractEntityChangeEvent $src
     * @return \static
     */
    public static function from(AbstractEntityChangeEvent $src) {
        $event = new static();
        $event->setEntity($src->getEntity());
        $event->setNew($src->isNew());
        return $event;
    }

}
