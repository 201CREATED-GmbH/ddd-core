<?php

namespace C201\Ddd\Events\Domain;

use C201\Support\Collections\IterableToCollectionConstructionTrait;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-06-05
 */
abstract class AbstractDomainEvent implements DomainEvent
{
    use IterableToCollectionConstructionTrait;

    protected EventId $id;

    protected \DateTimeImmutable $raisedTs;

    public function __construct(EventId $id, \DateTimeImmutable $raisedTs)
    {
        $this->id = $id;
        $this->raisedTs = $raisedTs;
    }

    public function id(): EventId
    {
        return $this->id;
    }

    public function raisedTs(): \DateTimeImmutable
    {
        return $this->raisedTs;
    }
}
