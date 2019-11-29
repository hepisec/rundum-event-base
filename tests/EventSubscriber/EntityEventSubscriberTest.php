<?php

namespace Rundum\EventSubscriber;

use Doctrine\Common\Persistence\ManagerRegistry;
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

    public function testOnChangeWithNew() {
        $entityManager = $this->createMock(EntityManager::class);
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

    public function testOnRemove() {
        $entityManager = $this->createMock(EntityManager::class);
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

}
