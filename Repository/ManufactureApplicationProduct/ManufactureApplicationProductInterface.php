<?php

namespace BaksDev\Manufacture\Part\Application\Repository\ManufactureApplicationProduct;

use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use BaksDev\Manufacture\Part\Entity\Products\ManufacturePartProduct;
use BaksDev\Products\Product\Type\Event\ProductEventUid;

interface ManufactureApplicationProductInterface
{
//    public function findApplicationProduct(ManufacturePartProduct $manufacturePartProduct): array;
    public function findApplicationProduct(string $product, string $offer, string $variation): array|false;

//    public function updateApplicationProduct(string|ProductEventUid $product): int|string;
    public function updateApplicationProduct(string|ManufactureApplicationUid $id, int $updated_total): int|string;
}