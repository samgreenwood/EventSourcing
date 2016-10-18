<?php

namespace SamGreenwood\EventSourcing;

class EventDispatcher
{
    /**
     * @var array
     */
    protected $listeners;

    /**
     * @param Event $event
     * @param $listener
     */
    public function addListener(Event $event, $listener)
    {
        $eventName = str_replace('\\', '.', (new \ReflectionClass($event))->getName());

        $this->listeners[$eventName][] = $listener;
    }

    /**
     * @param Event[]
     */
    public function dispatch($events)
    {
        if (!is_array($events)) {
            $events[] = $events;
        }

        array_filter($events, function ($event) {
            return $event instanceof Event;
        });

        foreach ($events as $event) {
            $name = (new \ReflectionClass($event))->getName();

            $method = 'when'.$name;

            if (array_key_exists($this->listeners, $name) && is_array($this->listeners[$name])) {
                foreach ($this->listeners[$name] as $listener) {
                    $listener->{$method}($event);
                }
            }
        }
    }
}
