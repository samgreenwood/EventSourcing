<?php

namespace SamGreenwood\EventSourcing;

abstract class AggregateRoot
{
    /**
     * @var Identity
     */
    protected $id;

    /**
     * @var array
     */
    private $latestRecordedEvents = [];

    /**
     * AggregateRoot constructor.
     *
     * @param Identity $id
     */
    protected function __construct(Identity $id)
    {
        $this->id = $id;
    }

    /**
     * @param DomainEvent $event
     */
    public function apply(DomainEvent $event)
    {
        $functionName = 'apply'.(new \ReflectionClass($event))->getShortName();

        if (method_exists($this, $functionName)) {
            $this->{$functionName}($event);
        }
    }

    /**
     * @param AggregateHistory $aggregateHistory
     *
     * @return AggregateRoot
     */
    public static function reconstituteFrom(AggregateHistory $aggregateHistory) : AggregateRoot
    {
        $id = $aggregateHistory->getAggregateId();

        $aggregate = new static($id);

        foreach ($aggregateHistory->getEvents() as $event) {
            $aggregate->apply($event);
        }

        return $aggregate;
    }

    /**
     * @param DomainEvent $event
     */
    public function recordThat(DomainEvent $event)
    {
        $this->latestRecordedEvents[] = $event;

        $this->apply($event);
    }

    /**
     * @return array
     */
    public function getRecordedEvents()
    {
        return $this->latestRecordedEvents;
    }

    /**
     * Clear all the events for the aggregate.
     */
    public function clearRecordedEvents()
    {
        $this->latestRecordedEvents = [];
    }

    /**
     * @return Identity
     */
    public function getAggregateId() : Identity
    {
        return $this->id;
    }

    /**
     * @return Identity
     */
    public function getId() : Identity
    {
        return $this->getAggregateId();
    }
}
