# rundum/event-base

This library provides basic events for Symfony 4 + Doctrine ORM based applications.

## Installation

```bash
composer require rundum/event-base
```

If you don't use Symfony flex, add the following configuration (`config/packages/rundum_event_base.yaml`)

```yaml
services:
    _defaults:
        autowire: true
        autoconfigure: true
        public: false
    
    rundum.event.entity.subscriber:
        class: Rundum\EventSubscriber\EntityEventSubscriber
```

## Usage

```php
class ExampleService {

    private $dispatcher;

    // Constructor with Dependency Injection
    function __construct(
            EventDispatcherInterface $dispatcher,
    ) {
        $this->dispatcher = $dispatcher;
    }
    
    function saveEntity($entity) {
        // -> INSERT ...
        $this->dispatcher->dispatch(new EntityChangeIntendedEvent($entity, true), EntityChangeIntendedEvent::NAME);
    }
    
    function updateEntity($entity) {
        // -> UPDATE ...
        $this->dispatcher->dispatch(new EntityChangeIntendedEvent($entity), EntityChangeIntendedEvent::NAME);
    }
    
    function deleteEntity($entity) {
        // -> DELETE FROM ...
        $this->dispatcher->dispatch(new EntityRemovalIntendedEvent($entity), EntityRemovalIntendedEvent::NAME);
    }
}
```