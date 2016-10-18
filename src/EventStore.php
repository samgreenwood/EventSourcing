<?php

namespace SamGreenwood\EventSourcing;

interface EventStore
{
    /**
     * @param DomainEvent $event
     *
     * @return mixed
     */
    public function store(DomainEvent $event);

    /**
     * @param DomainEvent[] $events
     *
     * @return mixed
     */
    public function storeEvents($events);

    /**
     * @param Identity $aggregateId
     *
     * @return AggregateHistory
     */
    public function eventsForAggregate(Identity $aggregateId) : AggregateHistory;
}
