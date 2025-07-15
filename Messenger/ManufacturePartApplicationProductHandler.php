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

use BaksDev\Manufacture\Part\Application\Repository\ManufactureApplicationProduct\ManufactureApplicationProductInterface;
use BaksDev\Manufacture\Part\Application\Repository\UpdateManufactureApplicationTotal\UpdateManufactureApplicationTotalInterface;
use BaksDev\Manufacture\Part\Messenger\ManufacturePartProduct\ManufacturePartProductMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(priority: 10)]
class ManufacturePartApplicationProductHandler
{
    public function __construct(
        private readonly ManufactureApplicationProductInterface $manufactureApplicationProduct,
        private readonly UpdateManufactureApplicationTotalInterface $updateManufactureApplicationTotal,
    ) {}

    public function __invoke(ManufacturePartProductMessage $message): void
    {

        if($message->getTotal() !== false)
        {

            // Получаем данные по заявке - manufactureApplicationProduct
            $manufactureApplicationProduct = $this->manufactureApplicationProduct->findApplicationProduct($message->getEvent(), $message->getOffer(), $message->getVariation());

            // Если такая заявка есть - то обновляем количество данной заявки - уменьшаем на кол-во
            // добавляемого в производственную партию товара
            if ($manufactureApplicationProduct)
            {
                $manufacturePartTotal = $message->getTotal();
                $manufactureApplicationProductTotal = $manufactureApplicationProduct['product_total']; // product_total

                $updated_total = $manufactureApplicationProductTotal - $manufacturePartTotal;

//                dump($updated_total);
                $this->updateManufactureApplicationTotal->updateApplicationProductTotal($manufactureApplicationProduct['product_id'], $updated_total);
            }

        }
    }
}