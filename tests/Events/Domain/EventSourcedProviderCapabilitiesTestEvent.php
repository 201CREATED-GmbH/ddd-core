<?php

namespace C201\Ddd\Tests\Events\Domain;

use C201\Ddd\Events\Domain\AbstractDomainEvent;
use C201\Ddd\Identity\Domain\AggregateId;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2020-10-19
 */
class EventSourcedProviderCapabilitiesTestEvent extends AbstractDomainEvent
{
    public function aggregateId(): AggregateId
    {
        // TODO: Implement aggregateId() method.
    }

    public function aggregateType(): string
    {
        // TODO: Implement aggregateType() method.
    }
}
