<?php

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\UpdateManufactureApplicationProduct;

use BaksDev\Core\Entity\AbstractHandler;
use BaksDev\Manufacture\Part\Application\Entity\Event\ManufactureApplicationEvent;
use BaksDev\Manufacture\Part\Application\Entity\ManufactureApplication;
use BaksDev\Manufacture\Part\Application\Entity\Product\ManufactureApplicationProduct;
use BaksDev\Manufacture\Part\Application\Messenger\ManufactureApplicationProductComplete\ManufactureApplicationProductCompleteMessage;
use BaksDev\Manufacture\Part\Application\UseCase\Admin\Completed\ManufactureApplicationCompletedDTO;
use BaksDev\Manufacture\Part\Application\UseCase\Admin\UpdateManufactureApplicationProduct\Product\UpdateManufactureApplicationProductDTO;

class UpdateManufactureApplicationProductHandler extends AbstractHandler
{
//    public function handle(UpdateManufactureApplicationProductDTO $command): string|ManufactureApplication
    public function handle(UpdateManufactureApplicationDTO $command, bool $is_completed = true): string|ManufactureApplication
    {

//        dd($command->getEvent());
//        dd($command);

        $this
            ->setCommand($command)
            ->preEventPersistOrUpdate(ManufactureApplication::class, ManufactureApplicationEvent::class);
//            ->preEventPersistOrUpdate(ManufactureApplicationProduct::class, ManufactureApplicationEvent::class);

        /* Валидация всех объектов */
        if($this->validatorCollection->isInvalid())
        {
            return $this->validatorCollection->getErrorUniqid();
        }

        $this->flush();

        /** @note Важно!!! Не отправляем сообщение в шину */
        $this->messageDispatch->addClearCacheOther('manufacture-part-application');

//        dd($is_completed);
        if ($is_completed) {
            // TODO Отправляем сообщение для закрытия заявки
            /* Отправляем сообщение в шину */
            $this->messageDispatch
                ->addClearCacheOther('wildberries-manufacture')
                ->addClearCacheOther('wildberries-package')
                ->dispatch(
                    message: new ManufactureApplicationProductCompleteMessage($this->main->getId(), $this->main->getEvent()),
                    transport: 'manufacture-part-application'
                );
        }

        return $this->main;

    }
}