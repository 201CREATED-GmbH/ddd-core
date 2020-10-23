<?php

namespace C201\Ddd\Tests\Events\Domain;

use C201\Ddd\Events\Domain\EventProvider;
use C201\Ddd\Events\Domain\EventSourcedProviderCapabilities;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2020-10-19
 */
class EventSourcedProviderCapabilitiesTestDouble implements EventProvider
{
    use EventSourcedProviderCapabilities;

    private bool $stateChanged = false;

    public function stateChanged(): bool
    {
        return $this->stateChanged;
    }

    public function raiseAndApplyEventSourcedProviderCapabilitiesTestEvent(): void
    {
        $this->raiseAndApplyEvent(new EventSourcedProviderCapabilitiesTestEvent($this->nextEventIdentity(), new \DateTimeImmutable()));
    }

    private function applyEventSourcedProviderCapabilitiesTestEvent(EventSourcedProviderCapabilitiesTestEvent $event): void
    {
        $this->stateChanged = true;
    }
}
