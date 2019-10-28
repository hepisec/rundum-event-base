<?php

namespace Rundum\Event;

use Rundum\Event\AbstractEntityRemovalEvent;

/**
 * @author Hendrik Pilz <pilz@rundum.digital>
 */
class EntityRemovalIntendedEvent extends AbstractEntityRemovalEvent {
    const NAME = "entity.removal.intended";
}
