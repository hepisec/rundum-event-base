<?php

namespace Rundum\Util;

/**
 * Provides event priority constants
 *
 * @author Hendrik Pilz <pilz@rundum.digital>
 */
class EventPriority {
    const FIRST = PHP_INT_MAX;
    const DEFAULT = 0;
    const LAST = PHP_INT_MIN;
}
