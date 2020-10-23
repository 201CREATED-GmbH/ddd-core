<?php

namespace C201\Ddd\Tests\Commands\Application;

use C201\Ddd\Commands\Application\CommandHandler;
use C201\Ddd\Events\Domain\EventProvider;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-06-27
 */
class CommandHandlerTestDouble extends CommandHandler
{
    private CommandHandlerTestExecuteExecutor $executeExecutor;
    private CommandHandlerTestPostRollbackExecutor $postRollbackExecutor;

    public function __construct(CommandHandlerTestExecuteExecutor $executeExecutor, CommandHandlerTestPostRollbackExecutor $postRollbackExecutor)
    {
        $this->executeExecutor = $executeExecutor;
        $this->postRollbackExecutor = $postRollbackExecutor;
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

    protected function postRollback(\Throwable $e, $command): \Throwable
    {
        return $this->postRollbackExecutor->execute($e, $command);
    }
}
