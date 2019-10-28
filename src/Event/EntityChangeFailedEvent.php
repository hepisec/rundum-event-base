<?php

namespace Rundum\Event;

use Rundum\Event\AbstractEntityChangeEvent;

/**
 * @author Hendrik Pilz <pilz@rundum.digital>
 */
class EntityChangeFailedEvent extends AbstractEntityChangeEvent {
    const NAME = 'entity.change.failed';
}
