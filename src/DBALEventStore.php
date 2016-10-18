<?php

namespace SamGreenwood\EventSourcing;

use Doctrine\DBAL\Connection as DBAL;

class DBALEventStore implements EventStore
{
    /**
     * @var DBAL
     */
    private $dbal;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * DBALEventStore constructor.
     *
     * @param DBAL            $dbal
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(DBAL $dbal, EventDispatcher $eventDispatcher)
    {
        $this->dbal = $dbal;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param $events
     *
     * @return mixed|void
     */
    public function storeEvents($events)
    {
        foreach ($events as $event) {
            if ($event instanceof DomainEvent) {
                $this->store($event);
            }
        }
    }

    /**
     * @param DomainEvent $event
     *
     * @return mixed|void
     */
    public function store(DomainEvent $event)
    {
        $name = get_class($event);
        $aggregateId = $event->getAggregateId();

        $this->dbal->beginTransaction();

        $data = [
            'name' => $name,
            'aggregateId' => $aggregateId,
            'data' => serialize($event),
            'storedAt' => new \DateTime(),
        ];

        $this->dbal->insert('events', $data);

        $this->dbal->commit();

        $this->eventDispatcher->dispatch($event);
    }

    /**
     * @param Identity $aggregateId
     *
     * @return AggregateHistory
     */
    public function eventsForAggregate(Identity $aggregateId) : AggregateHistory
    {
        $stmt = $this->dbal->prepare("SELECT * FROM events WHERE aggregateId = ':aggregateId' ORDER BY storedTime DESC");
        $stmt->bindParam('aggregateId', $aggregateId);
        $stmt->execute();

        return new AggregateHistory($aggregateId, array_map(function ($e) {
          return unserialize($e->data);
        }, $stmt->fetchAll()));
    }
}
