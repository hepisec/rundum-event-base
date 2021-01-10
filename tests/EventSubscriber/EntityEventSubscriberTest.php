<?php

namespace Rundum\EventSubscriber;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Rundum\Event\EntityChangeIntendedEvent;
use Rundum\Event\EntityRemovalIntendedEvent;
use Rundum\EventSubscriber\EntityEventSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Tests of EntityEventSubscriber
 *
 * @covers \Rundum\Event\AbstractEntityChangeEvent
 * @covers \Rundum\Event\AbstractEntityRemovalEvent
 * @covers \Rundum\EventSubscriber\EntityEventSubscriber
 * @author hendrik
 */
class EntityEventSubscriberTest extends TestCase {

    public function testOnChange() {
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->once())
                ->method('flush');

        $doctrine = $this->createStub(ManagerRegistry::class);
        $doctrine->method('getManager')->willReturn($entityManager);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
                ->method('info')
                ->with($this->stringStartsWith('Updating entity of type'));

        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        $entity = new \stdClass();
        $event = new EntityChangeIntendedEvent($entity);

        $entityEventSubscriber = new EntityEventSubscriber($logger, $doctrine, $dispatcher);
        $entityEventSubscriber->onChange($event);
    }

    public function testOnChangeArray() {
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->once())
                ->method('flush');

        $doctrine = $this->createStub(ManagerRegistry::class);
        $doctrine->method('getManager')->willReturn($entityManager);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))
                ->method('info')
                ->with($this->stringStartsWith('Updating entity of type'));

        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        $entity1 = new \stdClass();
        $entity2 = new \stdClass();
        $event = new EntityChangeIntendedEvent([$entity1, $entity2]);

        $entityEventSubscriber = new EntityEventSubscriber($logger, $doctrine, $dispatcher);
        $entityEventSubscriber->onChange($event);
    }

    public function testOnChangeWithNew() {
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->once())
                ->method('flush');

        $doctrine = $this->createStub(ManagerRegistry::class);
        $doctrine->method('getManager')->willReturn($entityManager);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
                ->method('info')
                ->with($this->stringStartsWith('Persisting new entity of type'));

        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        $entity = new \stdClass();
        $event = new EntityChangeIntendedEvent($entity, true);

        $entityEventSubscriber = new EntityEventSubscriber($logger, $doctrine, $dispatcher);
        $entityEventSubscriber->onChange($event);
    }

    public function testOnChangeWithNewArray() {
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->once())
                ->method('flush');

        $doctrine = $this->createStub(ManagerRegistry::class);
        $doctrine->method('getManager')->willReturn($entityManager);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))
                ->method('info')
                ->with($this->stringStartsWith('Persisting new entity of type'));

        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        $entity1 = new \stdClass();
        $entity2 = new \stdClass();
        $event = new EntityChangeIntendedEvent([$entity1, $entity2], true);

        $entityEventSubscriber = new EntityEventSubscriber($logger, $doctrine, $dispatcher);
        $entityEventSubscriber->onChange($event);
    }

    public function testOnRemove() {
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->once())
                ->method('flush');

        $doctrine = $this->createStub(ManagerRegistry::class);
        $doctrine->method('getManager')->willReturn($entityManager);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
                ->method('info')
                ->with($this->stringStartsWith('Removing entity of type'));

        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        $entity = new \stdClass();
        $event = new EntityRemovalIntendedEvent($entity);

        $entityEventSubscriber = new EntityEventSubscriber($logger, $doctrine, $dispatcher);
        $entityEventSubscriber->onRemove($event);
    }

    public function testOnRemoveArray() {
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->once())
                ->method('flush');

        $doctrine = $this->createStub(ManagerRegistry::class);
        $doctrine->method('getManager')->willReturn($entityManager);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))
                ->method('info')
                ->with($this->stringStartsWith('Removing entity of type'));

        $dispatcher = $this->createMock(EventDispatcherInterface::class);

        $entity1 = new \stdClass();
        $entity2 = new \stdClass();
        $event = new EntityRemovalIntendedEvent([$entity1, $entity2]);

        $entityEventSubscriber = new EntityEventSubscriber($logger, $doctrine, $dispatcher);
        $entityEventSubscriber->onRemove($event);
    }

}
