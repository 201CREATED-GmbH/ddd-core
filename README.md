201created/ddd is a set of components used by 201Created GmbH for developing software using domain-driven design, event sourcing and CQRS. Support is included for:

- entity identity
- domain events and their handling
- event store and event sourcing
- transaction handling
- command handling

The components are designed to facilitate the following workflow:
- A command is dispatched through a command bus
- This initiates a transaction where a command handler processes the command and performs the desired action on an aggregate or executes a domain service
- If an unhandled exception occurs, the transaction is rolled back and all changes are discarded
- Otherwise, the transaction is committed, events raised by the aggregate or domain service are collected, persisted to the event store, and dispatched through an event bus
- Event subscribers listen to the events and dispatch new commands if other aggregates need to be modified in response, or if other kinds of actions need to be undertaken
- This repeats the "core transaction loop" until all events are resolved and no new commands are initiated, or in other words until eventual consistency is achieved. 

## Usage
201created/ddd-core provides the components mostly as abstract classes and interfaces to facilitate use independent of any specific technological infrastructure. Our choice is Symfony with Doctrine and SimpleBus, and we provide implementations tying everything together in the 201created/ddd-doctrine-bridge and 201created/ddd-symfony-bridge libraries. If you wish to use other technologies, you will need to provide your own implementations for the event store and event, transaction and command handling.

### Core Loop
Here's a more detailed explanation on how to achieve the 201created/ddd workflow:
- Dispatch a command via the `CommandBus`. Commands are plain PHP classes of your own creation, essentially DTOs, and they should contain all the data necessary to perform the desired action. At a minimum, they should contain the identity of the aggregate being manipulated.
- The `CommandBus` should route the command to its corresponding handler. The implementation provided by 201created/ddd-symfony-bridge does this automatically within a properly configured Symfony application. Otherwise, you will have to ensure this yourself.
- Process the command in a handler extending the abstract `CommandHandler` class:
	- Each command class must have exactly one handler class.
	- Your handler should have a public method accepting the command as its argument, and the method should call `CommandHandler::handleCommand`.
	- `handleCommand` will in turn call the abstract `execute` method in which you should implement your command handling logic:
		- Load an aggregate implementing the `EventProvider` interface (we recommend doing so from a repository).
		- Perform an action on the aggregate, raising domain events within it (you can use the `EventProviderCapabilities` or `EventSourcedProviderCapabilities` trait in the aggregate to facilitate this).
		- Return the aggregate fom the `execute` method.
	- `CommandHandler` will dequeue any events raised by the aggregate returned from `execute`, register them with the `EventRegistry` and commit the transaction through the `TransactionManager`.
	- Alternatively, a domain service may be called from `execute`. In such cases, the service should use the `EventRegistry` to dequeue and register any events raised by the affected aggregate, and `CommandHandler::execute` should return null.
- `TransactionManager::commit` should take care of any persistence concerns and call `EventManager::flush`. Similarly, `TransactionManager::rollback` should discard and changes and call `EventManager::clear`. A Doctrine implementation is provided by 201created/ddd-doctrine-bridge.
- Flushing the `EventManager` collects all events registered by the `EventRegistry` and dispatches them through the `EventBus`. Clearing the `EventManager` simply discards all events.
- The `EventBus` should dispatch the events to any subscribers subscribing to them. 201created/ddd-symfony-bridge provides a Symfony/SimpleBus implementation that does this automatically within a properly configured Symfony application.
- If any other aggregates need to react to changes made to the initial aggregate, subscribe to the relevant events through event subscribers:
	- Multiple subscribers may subscribe to any single event. It is not recommended that subscribers depend on the order in which they process events, nor that a subscriber should stop the propagation of an event. While we enforce these practices in 201created/ddd-symfony-bridge,  we do not impose any restrictions on your own implementations.
	- The subscribers shouldn't contain any domain logic, but should instead generally only dispatch new commands.

### Entities, Aggregates and Events
Entities must implement the `EventProvider` interface, and they must raise a domain event for every change to their state. The `EventProviderCapabilities` can be used by entities to facilitate this. Each entity must also have its corresponding identity class implementing the `EntityId` interface. `AbstractEntityId` is provided for a default implementation.

One entity in every aggregate serves as the aggregate root. Any and all interaction with the aggregate is allowed only through this entity, and thus only aggregate roots should have repositories. Aggregate root identities must implement the `AggregateId` interface instead of just `EntityId`. `AbstractAggregateId` is provided for a default implementation. When `dequeueEvents` is called on an aggregate root, it should in turn collect all of the events from other entities in the aggregate and return them along the events raised by the aggregate root.

Events must implement the `DomainEvent` interface and may do so extending the `AbstractDomainEvent` class. A domain event records a state change and must contain all the data necesary for it to be replayed from the previous state.

If using event sourcing, aggregates should use the `EventSourcedProviderCapabilities` trait instead of `EventProviderCapabilities`. While it is possible to implement repositories using the `EventStore` and its `getAggregateStream` method directly, this will likely result in low performance for most implementations if a large number of aggregates is fetched at once. For such scenarios we recommend using projections instead.

## Testing
We write our PHPUnit/Prophecy unit tests inspired by the BDD workflow of "given/when/then". To test the code interacting with components from this library, we have gathered various given/when/then helper methods into traits. You can find them within the Testing namespaces of individual subdomans present in the library, for example:
- `C201\Ddd\Commands\Testing\CommandHandlerTestTrait`
- `C201\Ddd\Events\Testing\DomainEventTestTrait`