<?php

namespace C201\Ddd\Events\Domain;

use C201\Ddd\Identity\Domain\AggregateId;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-06-05
 */
interface DomainEvent
{
    public function id(): EventId;

    public function raisedTs(): \DateTimeImmutable;

    public function aggregateId(): AggregateId;

    public function aggregateType(): string;
}
