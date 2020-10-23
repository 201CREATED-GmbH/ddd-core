<?php

namespace C201\Ddd\Events\Domain;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-07-23
 */
trait EventSourcedProviderCapabilities
{
    use EventProviderCapabilities;

    protected function raiseAndApplyEvent(DomainEvent $event): void
    {
        $this->applyEvent($event);
        $this->raiseEvent($event);
    }

    private function applyEvent(DomainEvent $event): void
    {
        $applicator = 'apply' . substr(get_class($event), strrpos(get_class($event), '\\') + 1);
        $this->$applicator($event);
    }

    public static function reconstitute(AggregateEventStream $eventStream): self
    {
        $provider = new static();
        foreach ($eventStream->events() as $event) {
            $provider->applyEvent($event);
        }

        return $provider;
    }
}
