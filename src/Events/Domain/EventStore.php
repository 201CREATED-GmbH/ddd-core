<?php

namespace C201\Ddd\Events\Domain;

use C201\Ddd\Identity\Domain\AggregateId;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-08-21
 */
interface EventStore
{
    public function append(DomainEvent $event): void;

    public function getAggregateStream(AggregateId $aggregateId): AggregateEventStream;
}
