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

namespace BaksDev\Manufacture\Part\Application\Repository\UpdateManufactureApplicationTotal;

use BaksDev\Core\Doctrine\DBALQueryBuilder;
use BaksDev\Manufacture\Part\Application\Entity\Product\ManufactureApplicationProduct;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use Doctrine\DBAL\ParameterType;

class UpdateManufactureApplicationTotalRepository implements UpdateManufactureApplicationTotalInterface
{
    public function __construct(private readonly DBALQueryBuilder $DBALQueryBuilder) {}

    /**
     * Обновляем кол-во товара в производственной заявке
     */
    public function updateApplicationProductTotal(string|ManufactureApplicationUid $id, int $updated_total): int|string
    {

        $dbal = $this->DBALQueryBuilder
            ->createQueryBuilder(self::class)
            ->bindLocal();


        // TODO soft delete ??
        // удалить заявку если кол-во <= 0
        if($updated_total <= 0)
        {
            $dbal->delete(ManufactureApplicationProduct::class);

//            // TODO delete event, appl.
//
//            status - new и complete
//            tags

           // если заявка < то задать КОЛ-во заявке

            $dbal
                ->where('id = :id')
                ->setParameter('id', $id, ManufactureApplicationUid::TYPE);
        }

        else
        {
            $dbal->update(ManufactureApplicationProduct::class);
            // Передаем измененное кол-во
            $dbal
                ->set('total', ':total')
                ->setParameter('total', $updated_total, ParameterType::INTEGER);


            $id = $id instanceof ManufactureApplicationUid ? $id : new ManufactureApplicationUid($id);
            $dbal
                ->where('id = :id')
                ->setParameter('id', $id, ManufactureApplicationUid::TYPE);
        }

        return $dbal->executeStatement();
    }
}