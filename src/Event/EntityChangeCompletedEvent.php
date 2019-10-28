<?php

namespace Rundum\Event;

use Rundum\Event\AbstractEntityChangeEvent;

/**
 * @author Hendrik Pilz <pilz@rundum.digital>
 */
class EntityChangeCompletedEvent extends AbstractEntityChangeEvent {
    const NAME = 'entity.change.completed';
}
