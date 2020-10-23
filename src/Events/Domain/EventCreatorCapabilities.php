<?php

namespace C201\Ddd\Events\Domain;

use Ramsey\Uuid\Uuid;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-07-29
 */
trait EventCreatorCapabilities
{
    protected function nextEventIdentity(): EventId
    {
        return EventId::fromString(Uuid::uuid4());
    }
}
