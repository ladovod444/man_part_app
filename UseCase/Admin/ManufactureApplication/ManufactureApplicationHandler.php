<?php
/*
 *  Copyright 2025.  Baks.dev <admin@baks.dev>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

declare(strict_types=1);

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\ManufactureApplication;

use BaksDev\Core\Entity\AbstractHandler;
use BaksDev\Manufacture\Part\Application\Entity\Event\ManufactureApplicationEvent;
use BaksDev\Manufacture\Part\Application\Entity\ManufactureApplication;
use BaksDev\Manufacture\Part\Application\Entity\Product\ManufactureApplicationProduct;
use BaksDev\Manufacture\Part\Application\Messenger\ManufactureApplicationMessage;
use BaksDev\Manufacture\Part\Application\Repository\ActionByMain\ActionByMainInterface;
use BaksDev\Manufacture\Part\Entity\ManufacturePart;

final class ManufactureApplicationHandler extends AbstractHandler
{
    /** @see ManufacturePart */
    public function handle(
        ManufactureApplicationDTO $command,
        ActionByMainInterface $actionByMain // TODO удалить
    ): string|ManufactureApplication
    {

//                dd($command->getPriority());

        /** @var ManufactureApplicationEvent $event */

        $this
            ->setCommand($command)
            ->preEventPersistOrUpdate(ManufactureApplication::class, ManufactureApplicationEvent::class);

        /** Валидация всех объектов */
        if($this->validatorCollection->isInvalid())
        {
            return $this->validatorCollection->getErrorUniqid();
        }

        $this->flush();


        // TODO
        $event = $this->getRepository(ManufactureApplicationEvent::class)->findOneBy(
            [
                'id' => $this->main->getEvent(),
            ]
        );

//        $event->setPriority(true);

        /**
         * Добавляем к заявке продукт
         */
        $ManufacturePartProduct = new ManufactureApplicationProduct($event);

        foreach($command->getApplicationProductFormData() as $productFormData)
        {

            $ManufacturePartProduct->setEntity($productFormData);

            // Валидация всех объектов
            if($this->validatorCollection->isInvalid())
            {
                return $this->validatorCollection->getErrorUniqid();
            }

            $this->persist($ManufacturePartProduct);

        }
        $this->flush();


        /* Отправляем сообщение в шину */


        $this->messageDispatch
            //            ->addClearCacheOther('wildberries-manufacture')
            //            ->addClearCacheOther('wildberries-package')
            ->dispatch(
                message: new ManufactureApplicationMessage($this->main->getId(), $this->main->getEvent(), $command->getEvent()),
                transport: 'manufacture-part'
            );

        return $this->main;
    }
}