<?php

namespace C201\Ddd\Identity\Domain;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2020-04-02
 */
interface AggregateId extends EntityId
{
    public function aggregateType(): string;
}
