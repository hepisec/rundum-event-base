<?php

namespace Rundum\Event;

use Rundum\Event\AbstractEntityRemovalEvent;

/**
 * @author Hendrik Pilz <pilz@rundum.digital>
 */
class EntityRemovalCompletedEvent extends AbstractEntityRemovalEvent {
    const NAME = "entity.removal.completed";
}
