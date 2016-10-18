<?php

namespace SamGreenwood\EventSourcing\Testing;

use PHPUnit_Framework_TestCase as TestCase;
use Mockery as m;

/**
 * Base test case that can be used to set up a command handler scenario.
 */
abstract class AggregateRootScenarioTestCase extends TestCase
{
    /**
     * @var Scenario
     */
    protected $scenario;

    public function setUp()
    {
        $this->scenario = $this->createScenario();
    }

    public function tearDown()
    {
        m::close();
    }

    /**
     * @return Scenario
     */
    protected function createScenario()
    {
        $aggregateRootClass = $this->getAggregateRootClass();

        return new Scenario($this, $aggregateRootClass);
    }

    /**
     * Returns a string representing the aggregate root.
     *
     * @return string AggregateRoot
     */
    abstract protected function getAggregateRootClass();
}
