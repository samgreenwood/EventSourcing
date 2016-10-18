<?php

namespace SamGreenwood\EventSourcing;

abstract class DomainEvent extends Event
{
    abstract public function getAggregateId() : Identity;
}
