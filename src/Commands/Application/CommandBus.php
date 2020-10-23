<?php

namespace C201\Ddd\Commands\Application;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-06-05
 */
interface CommandBus
{
    public function dispatch($command): void;
}
