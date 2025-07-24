<?php

declare(strict_types=1);

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct;


use BaksDev\Core\Entity\AbstractHandler;
use BaksDev\Manufacture\Part\Application\Entity\Event\ManufactureApplicationEvent;
use BaksDev\Manufacture\Part\Application\Entity\ManufactureApplication;
use BaksDev\Manufacture\Part\Application\Messenger\ManufactureApplicationMessage;

final class ManufactureApplicationHandler extends AbstractHandler
{
    /** @see ManufactureApplication */
    public function handle(ManufactureApplicationDTO $command): string|ManufactureApplication
    {
        $this
            ->setCommand($command)
            ->preEventPersistOrUpdate(ManufactureApplication::class, ManufactureApplicationEvent::class);

        /** Валидация всех объектов */
        if($this->validatorCollection->isInvalid())
        {
            return $this->validatorCollection->getErrorUniqid();
        }

        $this->flush();

        /* Отправляем сообщение в шину */
//        $this->messageDispatch->dispatch(
//            message: new ManufactureApplicationMessage($this->main->getId(), $this->main->getEvent(), $command->getEvent()),
//            transport: 'manufacture-application',
//        );

        return $this->main;
    }
}