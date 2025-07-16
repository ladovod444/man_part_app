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

namespace BaksDev\Manufacture\Part\Application\Messenger;

use BaksDev\Manufacture\Part\Application\Entity\Event\ManufactureApplicationEvent;
use BaksDev\Manufacture\Part\Application\Entity\ManufactureApplication;
use BaksDev\Manufacture\Part\Application\Entity\Product\ManufactureApplicationProduct;
use BaksDev\Manufacture\Part\Application\Type\Status\ManufactureApplicationStatus;
use BaksDev\Manufacture\Part\Application\Type\Status\ManufactureApplicationStatus\ManufactureApplicationStatusCompleted;
use BaksDev\Manufacture\Part\Messenger\ManufacturePartProduct\ManufacturePartProductMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Обработчик сообщения ManufacturePartProductMessage,
 * которое диспатчится при добавлении товар в Производственную партию
 * @see vendor/baks-dev/manufacture-part/Controller/Admin/Products/AddSelectedProductsController.php
 */

#[AsMessageHandler(priority: 10)]
class ManufacturePartApplicationProductHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(ManufacturePartProductMessage $message): void
    {

        if($message->getTotal() !== false)
        {

            //Получаем данные по заявке - manufactureApplicationProduct
            $ManufactureApplicationProduct = $this->entityManager->getRepository(ManufactureApplicationProduct::class)->findOneBy(
                [
                    'offer' => $message->getOffer(),
                    'variation' => $message->getVariation(),
                    'modification' => $message->getModification(),
                    'event' => $message->getManufactureApplicationProductEvent()
                ]
            );

            //            dump($message->getManufactureApplicationProductEvent());

            // TODO
            if($ManufactureApplicationProduct instanceof ManufactureApplicationProduct)
            {

                // Получаем данные по кол-ву товара, введенные в форме
                $manufacturePartTotal = $message->getTotal();

                // Получаем данные по кол-ву товара текущего товара производственной заявки
                $manufactureApplicationProductTotal = $ManufactureApplicationProduct->getTotal();

                $updated_total = $manufactureApplicationProductTotal - $manufacturePartTotal;

                // Получаем данные по Event производственной заявки
                $ManufactureApplicationEvent = $this->entityManager->getRepository(ManufactureApplicationEvent::class)->findOneBy(
                    [
                        'id' => $ManufactureApplicationProduct->getEvent()->getId(),
                    ]
                );


                dump($ManufactureApplicationEvent);

                // Создаем (клонируем) новый Event
                /** @var ManufactureApplicationEvent $Event $Event */
                $Event = $ManufactureApplicationEvent->cloneEntity();

                // Получаем данные по Производственной заявке (корневой сущности)
                $ManufactureApplication = $this->entityManager->getRepository(ManufactureApplication::class)->findOneBy(
                    [
                        'id' => $ManufactureApplicationEvent->getMain(),
                    ]
                );

                // Обновляем событие агрегата
                $ManufactureApplication->setEvent($Event);

                // Создаем (клонируем) новый товар Произв-ной партии
                $Product = $ManufactureApplicationProduct->cloneEntity();

                // Задаем статус - 'completed' указываем кол-во по завершению
                if($updated_total <= 0)
                {
                    $Product->setTotalCompleted($manufacturePartTotal);

                    $status = new ManufactureApplicationStatus(ManufactureApplicationStatusCompleted::class);
                    $Event->setStatus($status);
                }

                // Обновляем кол-во
                else
                {

                    $Product->setTotal($updated_total);
                    $this->entityManager->flush();
                    //                        return;
                }

                // Задать новое событие также и для товара произв-заявки
                $Product->setEvent($Event);

                $this->entityManager->persist($Event);
                $this->entityManager->persist($Product);
                $this->entityManager->flush();

            }

        }
    }
}