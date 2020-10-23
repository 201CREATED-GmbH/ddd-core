<?php

namespace C201\Ddd\Events\Domain;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-06-05
 */
interface EventProvider
{
    /**
     * Removes all raised events from the provider and returns them.
     *
     * @return DomainEvent[]
     */
    public function dequeueEvents(): array;
}
