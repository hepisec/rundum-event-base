<?php

namespace Rundum\EventSubscriber;

use Rundum\Event\EntityChangeCompletedEvent;
use Rundum\Event\EntityChangeFailedEvent;
use Rundum\Event\EntityChangeIntendedEvent;
use Rundum\Event\EntityRemovalCompletedEvent;
use Rundum\Event\EntityRemovalFailedEvent;
use Rundum\Event\EntityRemovalIntendedEvent;
use Rundum\Util\EventPriority;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Handles EntityChangedIntendedEvent and EntityRemovalIntendedEvent
 *
 * Dispatches EntityChangeCompletedEvent, EntityChangeFailedEvent, EntityRemovalCompletedEvent, EntityRemovalFailedEvent
 *
 * @author Hendrik Pilz <pilz@rundum.digital>
 */
class EntityEventSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents(): array {
        return [
            EntityChangeIntendedEvent::NAME => [
                ['onChange', EventPriority::FIRST]
            ],
            EntityRemovalIntendedEvent::NAME => [
                ['onRemove', EventPriority::FIRST]
            ]
        ];
    }

    private $logger;
    private $doctrine;
    private $dispatcher;

    public function __construct(
            LoggerInterface $logger,
            ManagerRegistry $doctrine,
            EventDispatcherInterface $dispatcher
    ) {
        $this->logger = $logger;
        $this->doctrine = $doctrine;
        $this->dispatcher = $dispatcher;
    }

    private function getEntityManager(): EntityManager {
        return $this->doctrine->getManager();
    }

    /**
     * Persist or merge an entity
     *
     * Dispatches EntityChangeCompletedEvent on success or EntityChangeFailedEvent on failure
     *
     * @param EntityChangeIntendedEvent $event
     * @return void
     */
    public function onChange(EntityChangeIntendedEvent $event): void {
        $entity = $event->getEntity();
        $entities = is_array($entity) ? $entity : [$entity];

        $em = $this->getEntityManager();

        try {
            foreach ($entities as $entity) {
                if ($event->isNew()) {
                    $this->logger->info('Persisting new entity of type ' . get_class($entity));
                    $em->persist($entity);
                } else {
                    $this->logger->info('Updating entity of type ' . get_class($entity));
                }
            }

            $em->flush();
            $this->dispatcher->dispatch(EntityChangeCompletedEvent::from($event), EntityChangeCompletedEvent::NAME);
        } catch (\Exception $ex) {
            $this->logger->warning('Operation failed: ' . $ex->getMessage());
            $this->logger->warning($ex->getTraceAsString());
            $this->dispatcher->dispatch(EntityChangeFailedEvent::from($event), EntityChangeFailedEvent::NAME);
            $event->stopPropagation();
        }
    }

    /**
     * Remove an entity
     *
     * Dispatches EntityRemovalCompletedEvent on success or EntityRemovalFailedEvent on failure
     *
     * @param EntityRemovalIntendedEvent $event
     * @return void
     */
    public function onRemove(EntityRemovalIntendedEvent $event): void {
        $entity = $event->getEntity();
        $entities = is_array($entity) ? $entity : [$entity];

        $em = $this->getEntityManager();

        try {
            foreach ($entities as $entity) {
                $this->logger->info('Removing entity of type ' . get_class($entity));
                $em->remove($entity);
            }
            
            $em->flush();
            $this->dispatcher->dispatch(EntityRemovalCompletedEvent::from($event), EntityRemovalCompletedEvent::NAME);
        } catch (\Exception $ex) {
            $this->logger->warning('Operation failed: ' . $ex->getMessage());
            $this->logger->warning($ex->getTraceAsString());
            $this->dispatcher->dispatch(EntityRemovalFailedEvent::from($event), EntityRemovalFailedEvent::NAME);
            $event->stopPropagation();
        }
    }

}
