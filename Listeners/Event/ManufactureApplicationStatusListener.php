<?php

namespace BaksDev\Manufacture\Part\Application\Listeners\Event;

use BaksDev\Manufacture\Part\Application\Type\Status\ManufactureApplicationStatus\Collection\ManufactureApplicationStatusCollection;
use BaksDev\Manufacture\Part\Application\Type\Status\ManufactureApplicationStatusType;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

#[AsEventListener(event: ControllerEvent::class)]
#[AsEventListener(event: ConsoleCommandEvent::class)]
class ManufactureApplicationStatusListener
{
    public function __construct(private ManufactureApplicationStatusCollection $collection) {}

    public function onKernelController(ControllerEvent $event): void
    {
        if(in_array(ManufactureApplicationStatusType::class, get_declared_classes(), true))
        {
            $this->collection->cases();
        }
    }

    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $this->collection->cases();
    }
}