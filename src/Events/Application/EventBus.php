<?php

namespace C201\Ddd\Events\Application;

use C201\Ddd\Events\Domain\DomainEvent;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-06-05
 */
interface EventBus
{
    public function dispatch(DomainEvent $event): void;
}
