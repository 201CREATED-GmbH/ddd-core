<?php

namespace C201\Ddd\Tests\Commands\Application;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-06-27
 */
interface CommandHandlerTestPostRollbackExecutor
{
    public function execute(\Throwable $e, $command): \Throwable;
}
