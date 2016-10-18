<?php

namespace SamGreenwood\EventSourcing\Testing;

use PHPUnit_Framework_TestCase;
use SamGreenwood\EventSourcing\Identity;
use SamGreenwood\EventSourcing\AggregateRoot;
use SamGreenwood\EventSourcing\AggregateHistory;

/**
 * 1) given(): Initialize the aggregate root using a history of events
 * 2) when():  A callable that calls a method on the event sourced aggregate root
 * 3) then():  Events that should have been applied.
 */
class Scenario
{
    /**
     * @var PHPUnit_Framework_TestCase
     */
    private $testCase;

    /**
     * @var string
     */
    private $aggregateRootClass;

    /**
     * @var AggregateRoot
     */
    private $aggregateRootInstance;

    /**
     * @var Identity
     */
    private $aggregateId;

    /**
     * @param PHPUnit_Framework_TestCase $testCase
     * @param string                     $aggregateRootClass
     *
     * @internal param PHPUnit_Framework_TestCase $testcase
     */
    public function __construct(PHPUnit_Framework_TestCase $testCase, $aggregateRootClass)
    {
        $this->testCase = $testCase;
        $this->aggregateRootClass = $aggregateRootClass;
    }

    /**
     * @param Identity $aggregateId
     *
     * @return Scenario
     */
    public function withAggregateId(Identity $aggregateId)
    {
        $this->aggregateId = $aggregateId;

        return $this;
    }

    /**
     * @param array $events
     *
     * @return Scenario
     */
    public function given(array $events = null)
    {
        if ($events === null) {
            return $this;
        }

        $aggregateHistory = new AggregateHistory($this->aggregateId, $events);

        $this->aggregateRootInstance = call_user_func($this->aggregateRootClass.'::reconstituteFrom', $aggregateHistory);

        return $this;
    }

    /**
     * @param callable $when
     *
     * @return Scenario
     */
    public function when(callable $when)
    {
        if ($this->aggregateRootInstance === null) {
            $this->aggregateRootInstance = $when($this->aggregateRootInstance);

            $this->testCase->assertInstanceOf($this->aggregateRootClass, $this->aggregateRootInstance);
        } else {
            $when($this->aggregateRootInstance);
        }

        return $this;
    }

    /**
     * @param array $thens
     *
     * @return Scenario
     */
    public function then(array $thens)
    {
        $events = $this->getEvents();
        $this->testCase->assertEquals($thens, $events);
        $this->testCase->assertCount(count($events), $thens);

        return $this;
    }

    /**
     * @return array Payloads of the recorded events
     */
    private function getEvents()
    {
        return $this->aggregateRootInstance->getRecordedEvents();
    }

    /**
     * @return AggregateRoot
     */
    public function getAggregateRootInstance(): AggregateRoot
    {
        return $this->aggregateRootInstance;
    }
}
