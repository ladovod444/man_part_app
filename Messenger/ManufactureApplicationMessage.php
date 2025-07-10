<?php

declare(strict_types=1);

namespace BaksDev\Manufacture\Part\Application\Messenger;


use BaksDev\Manufacture\Part\Application\Type\Event\ManufactureApplicationEventUid;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;

final class ManufactureApplicationMessage
{
    /**
     * Идентификатор
     */
    private string $id;

    /**
     * Идентификатор события
     */
    private string $event;

    /**
     * Идентификатор предыдущего события
     */
    private ?string $last;


    public function __construct(
        ManufactureApplicationUid|string $id,
        ManufactureApplicationEventUid|string $event,
        ManufactureApplicationEventUid|string|null $last = null
    )
    {
        $this->id = (string) $id;
        $this->event = (string) $event;
        $this->last = (string) $last;
    }


    /**
     * Идентификатор
     */
    public function getId(): ManufactureApplicationUid
    {
        return new ManufactureApplicationUid($this->id);
    }


    /**
     * Идентификатор события
     */
    public function getEvent(): ManufactureApplicationEventUid
    {
        return new ManufactureApplicationEventUid($this->event);
    }


    /**
     * Идентификатор предыдущего события
     */
    public function getLast(): ?ManufactureApplicationEventUid
    {
        return $this->last ? new ManufactureApplicationEventUid($this->last) : null;
    }

}
