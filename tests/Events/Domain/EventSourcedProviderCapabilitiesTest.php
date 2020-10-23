<?php

namespace C201\Ddd\Tests\Events\Domain;

use C201\Ddd\Events\Domain\AggregateEventStream;
use C201\Ddd\Events\Testing\DomainEventTestTrait;
use C201\Ddd\Identity\Domain\AbstractAggregateId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tightenco\Collect\Support\Collection;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2020-10-19
 */
class EventSourcedProviderCapabilitiesTest extends TestCase
{
    use DomainEventTestTrait;

    public function testRaiseAndApplyEventRaisesAndAppliesTheEvent(): void
    {
        $fixture = $this->givenAnEventSourcedProvider();
        $this->givenTheEventSourcedProviderStateHasNotBeenChanged($fixture);
        $this->whenRaiseAndApplyEventSourcedProviderCapabilitiesTestEventIsCalled($fixture);
        $this->thenStateShouldHaveBeenChangedOnTheEventSourcedProvider($fixture);
        $this->thenEventSourcedProviderCapabilitiesTestEventShouldHaveBeenRaised($fixture);
    }

    private function givenAnEventSourcedProvider(): EventSourcedProviderCapabilitiesTestDouble
    {
        return new EventSourcedProviderCapabilitiesTestDouble;
    }

    private function givenTheEventSourcedProviderStateHasNotBeenChanged(EventSourcedProviderCapabilitiesTestDouble $fixture): void
    {
        $this->assertFalse($fixture->stateChanged());
    }

    private function whenRaiseAndApplyEventSourcedProviderCapabilitiesTestEventIsCalled(EventSourcedProviderCapabilitiesTestDouble $fixture): void
    {
        $fixture->raiseAndApplyEventSourcedProviderCapabilitiesTestEvent();
    }

    private function thenStateShouldHaveBeenChangedOnTheEventSourcedProvider(EventSourcedProviderCapabilitiesTestDouble $fixture): void
    {
        $this->assertTrue($fixture->stateChanged());
    }

    private function thenEventSourcedProviderCapabilitiesTestEventShouldHaveBeenRaised(EventSourcedProviderCapabilitiesTestDouble $fixture): void
    {
        $events = $fixture->dequeueEvents();
        $this->assertCount(1, $events);
        $this->assertContainsOnlyInstancesOf(EventSourcedProviderCapabilitiesTestEvent::class, $events);
    }

    public function testReconstituteAppliesEventsFromStreamButDoesNotRaiseThem(): void
    {
        $eventStream = $this->givenAggregateEventStreamContainingEventSourcedProviderCapabilitiesTestEvent();
        $fixture = $this->whenReconsituteIsCalledForTheEventSourcedProviderWithTheEventStream($eventStream);
        $this->thenTheEventSourcedProviderShouldHaveBeenReconsitutedWithItsStateChanged($fixture);
        $this->thenNoEventsShouldHaveBeenRaisedOnTheEventSourcedProvcider($fixture);
    }

    private function givenAggregateEventStreamContainingEventSourcedProviderCapabilitiesTestEvent(): AggregateEventStream
    {
        return new AggregateEventStream(
            $this->getMockForAbstractClass(AbstractAggregateId::class, [], '', false),
            Collection::make([new EventSourcedProviderCapabilitiesTestEvent($this->givenAnEventId(), $this->givenARaisedTs())])
        );
    }

    private function whenReconsituteIsCalledForTheEventSourcedProviderWithTheEventStream(
        AggregateEventStream $eventStream
    ): EventSourcedProviderCapabilitiesTestDouble {
        return EventSourcedProviderCapabilitiesTestDouble::reconstitute($eventStream);
    }

    private function thenTheEventSourcedProviderShouldHaveBeenReconsitutedWithItsStateChanged(EventSourcedProviderCapabilitiesTestDouble $fixture): void
    {
        $this->assertTrue($fixture->stateChanged());
    }

    private function thenNoEventsShouldHaveBeenRaisedOnTheEventSourcedProvcider(EventSourcedProviderCapabilitiesTestDouble $fixture): void
    {
        $this->assertEmpty($fixture->dequeueEvents());
    }
}
