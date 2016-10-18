<?php

namespace SamGreenwood\EventSourcing;

class AggregateHistory
{
    /**
     * @var IdentifiesAggregate
     */
    private $aggregateId;

    /**
     * @var array
     */
    private $events;

    /**
     * AggregateHistory constructor.
     *
     * @param $aggregateId
     * @param $events
     */
    public function __construct(Identity $aggregateId, $events)
    {
        $this->aggregateId = $aggregateId;
        $this->events = $events;
    }

    /**
     * @return Event[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @return Identity
     */
    public function getAggregateId() : Identity
    {
        return $this->aggregateId;
    }
}
