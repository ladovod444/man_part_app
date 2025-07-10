<?php

namespace BaksDev\Manufacture\Part\Application\Entity\Product;

use BaksDev\Products\Product\Type\Event\ProductEventUid;
use BaksDev\Products\Product\Type\Offers\Id\ProductOfferUid;
use BaksDev\Products\Product\Type\Offers\Variation\Id\ProductVariationUid;
use BaksDev\Products\Product\Type\Offers\Variation\Modification\Id\ProductModificationUid;

interface ManufactureApplicationProductInterface
{
    /**
     * Идентификатор События!!! продукта
     */
    public function getProduct(): ?ProductEventUid;


    /**
     * Идентификатор торгового предложения
     */

    public function getOffer(): ?ProductOfferUid;


    /**
     * Идентификатор множественного варианта торгового предложения
     */

//    public function getVariation(): ?ProductVariationUid;


    /**
     * Идентификатор модификации множественного варианта торгового предложения
     */

//    public function getModification(): ?ProductModificationUid;
}