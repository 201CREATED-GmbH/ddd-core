<?php

namespace C201\Ddd\Identity\Domain;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2020-04-03
 */
abstract class AbstractAggregateId extends AbstractEntityId implements AggregateId
{
    public function aggregateType(): string
    {
        return $this->entityType();
    }
}
