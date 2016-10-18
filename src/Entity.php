<?php

namespace SamGreenwood\EventSourcing;

abstract class Entity
{
    /**
     * @var Identity
     */
    protected $id;

    /**
     * @var AggregateRoot
     */
    protected $aggregateRoot;

    /**
     * @param AggregateRoot $aggregateRoot
     */
    public function registerAggregateRoot(AggregateRoot $aggregateRoot)
    {
        $this->aggregateRoot = $aggregateRoot;
    }

    /**
     * @return AggregateRoot
     */
    public function getAggregateRoot()
    {
        return $this->aggregateRoot;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param DomainEvent $domainEvent
     */
    protected function apply(DomainEvent $domainEvent)
    {
        $this->aggregateRoot->apply($domainEvent);
    }

    /**
     * @param DomainEvent $event
     */
    public function recordThat(DomainEvent $event)
    {
        $this->aggregateRoot->recordThat($event);
    }
}
