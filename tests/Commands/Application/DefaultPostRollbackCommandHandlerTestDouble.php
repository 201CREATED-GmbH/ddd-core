<?php

namespace C201\Ddd\Tests\Commands\Application;

use C201\Ddd\Commands\Application\CommandHandler;
use C201\Ddd\Events\Domain\EventProvider;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2020-10-19
 */
class DefaultPostRollbackCommandHandlerTestDouble extends CommandHandler
{
    private CommandHandlerTestExecuteExecutor $executeExecutor;

    public function __construct(CommandHandlerTestExecuteExecutor $executeExecutor)
    {
        $this->executeExecutor = $executeExecutor;
    }

    public function handle(CommandHandlerTestCommand $command): void
    {
        $this->handleCommand($command);
    }

    /**
     * @param CommandHandlerTestCommand $command
     */
    protected function execute($command): ?EventProvider
    {
        return $this->executeExecutor->execute($command->getArgument());
    }
}
