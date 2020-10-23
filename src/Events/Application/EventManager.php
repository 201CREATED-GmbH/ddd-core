<?php

namespace C201\Ddd\Events\Application;

use C201\Ddd\Events\Domain\EventRegistry;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-06-05
 */
class EventManager
{
    private EventRegistry $eventRegistry;

    private EventBus $eventBus;

    public function __construct(EventRegistry $eventRegistry, EventBus $eventBus)
    {
        $this->eventRegistry = $eventRegistry;
        $this->eventBus = $eventBus;
    }

    /**
     * Dequeues all events from the registry and dispatches them to the event bus. To be used after an application transaction is committed.
     */
    public function flush(): void
    {
        foreach ($this->eventRegistry->dequeueEvents() as $event) {
            $this->eventBus->dispatch($event);
        }
    }

    /**
     * Dequeues all events from the registry but does nothing with them, effectively deleting them. To be used after an application transaction is rolled back.
     */
    public function clear(): void
    {
        $this->eventRegistry->dequeueEvents();
    }
}
