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
use BaksDev\Manufacture\Part\Application\Repository\ManufactureApplicationProduct\ManufactureApplicationProductInterface;
use BaksDev\Manufacture\Part\Application\Repository\UpdateManufactureApplicationTotal\UpdateManufactureApplicationTotalInterface;
use BaksDev\Manufacture\Part\Application\Type\Status\ManufactureApplicationStatus;
use BaksDev\Manufacture\Part\Application\Type\Status\ManufactureApplicationStatus\ManufactureApplicationStatusCompleted;
use BaksDev\Manufacture\Part\Application\Type\Status\ManufactureApplicationStatus\ManufactureApplicationStatusNew;
use BaksDev\Manufacture\Part\Messenger\ManufacturePartProduct\ManufacturePartProductMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(priority: 10)]
class ManufacturePartApplicationProductHandler
{
    public function __construct(
        private readonly ManufactureApplicationProductInterface $manufactureApplicationProduct,
        private readonly UpdateManufactureApplicationTotalInterface $updateManufactureApplicationTotal,

        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(ManufacturePartProductMessage $message): void
    {

//        dd($message->getTotal());

        if($message->getTotal() !== false)
        {

            // Получаем данные по заявке - manufactureApplicationProduct
            $manufactureApplicationProduct = $this->manufactureApplicationProduct->findApplicationProduct(
                $message->getEvent(),
                $message->getOffer(),
                $message->getVariation(),
                $message->getModification(),
            );


            dump(2);

            dump($message->getEvent(),
                $message->getOffer());

//            die();

            $ManufactureApplicationProduct = $this->entityManager->getRepository(ManufactureApplicationProduct::class)->findOneBy(
                [
//                    'product' => $message->getEvent(),
                    'offer' => $message->getOffer(),
                    'variation' => $message->getVariation(),
                    'modification' => $message->getModification(),
                ]
            );

//            dd($ManufactureApplicationProduct);

            if ($ManufactureApplicationProduct instanceof ManufactureApplicationProduct) {

                $manufacturePartTotal = $message->getTotal();
                $manufactureApplicationProductTotal = $manufactureApplicationProduct['product_total'];

                $updated_total = $manufactureApplicationProductTotal - $manufacturePartTotal;

                // Обновляем только total
                if ($updated_total > 0)
                {
//                    dd($updated_total);

                    $ManufactureApplicationProduct->setTotal($updated_total);
                    $this->entityManager->flush();
                }
                else { // Задать total_completed
                       // и Задать статус completed ManufactureApplicationEvent

                    $ManufactureApplicationProduct->setTotalCompleted($manufacturePartTotal);
//                    $this->entityManager->flush();

//                    dd($manufactureApplicationProduct['product_event']);

                    // TODO Получить
                    $ManufactureApplicationEvent = $this->entityManager->getRepository(ManufactureApplicationEvent::class)->findOneBy(
                        [
                           'id' => $manufactureApplicationProduct['product_event'],
                        ]
                    );

                    $status = new ManufactureApplicationStatus(ManufactureApplicationStatusCompleted::class);
                    $ManufactureApplicationEvent->setStatus($status);

                    $this->entityManager->flush();

//                    dd($ManufactureApplicationEvent);






//                    $ManufactureApplication = $this->entityManager->getRepository(ManufactureApplication::class)->findOneBy(
//                        [
//                            'event' => $ManufactureApplicationEvent->getId(),
//                        ]
//                    );
//                    /** @var ManufactureApplicationEvent $Event $Event */
//                    $Event = $ManufactureApplicationEvent->cloneEntity();
//
////                    $Event->setStatus( new ManufactureApplicationStatus (ManufactureApplicationStatusCompleted::STATUS));
//                    //ManufactureApplicationEvent
//
//                    dd($Event);
//
//                    $ManufactureApplication->setEvent($Event);
//
//                    $this->entityManager->flush();
//
//                    dd(1);



                }
            }

//            // Если такая заявка есть - то обновляем количество данной заявки - уменьшаем на кол-во
//            // добавляемого в производственную партию товара
//            if ($manufactureApplicationProduct)
//            {
//                $manufacturePartTotal = $message->getTotal();
//                $manufactureApplicationProductTotal = $manufactureApplicationProduct['product_total']; // product_total
//
//
////                $this->updateManufactureApplicationTotal->updateApplicationProductTotal($manufactureApplicationProduct['product_id'], $manufactureApplicationProductTotal, $manufacturePartTotal);
//                $this->updateManufactureApplicationTotal->updateApplicationProductTotal($manufactureApplicationProduct['product_event'], $manufactureApplicationProductTotal, $manufacturePartTotal);
//            }

        }
    }
}