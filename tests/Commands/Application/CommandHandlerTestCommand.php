<?php

namespace C201\Ddd\Tests\Commands\Application;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-06-27
 */
class CommandHandlerTestCommand
{
    private $argument;

    public function __construct($argument)
    {
        $this->argument = $argument;
    }

    public function getArgument()
    {
        return $this->argument;
    }
}
