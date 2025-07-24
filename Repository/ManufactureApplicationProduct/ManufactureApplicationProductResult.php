<?php

namespace BaksDev\Manufacture\Part\Application\Repository\ManufactureApplicationProduct;

use BaksDev\Manufacture\Part\Application\Type\Event\ManufactureApplicationEventUid;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use BaksDev\Manufacture\Part\Application\Type\Product\ManufactureApplicationProductUid;
use BaksDev\Products\Product\Type\Event\ProductEventUid;
use BaksDev\Products\Product\Type\Id\ProductUid;

class ManufactureApplicationProductResult
{
    public function __construct(

        private string|null $id,
        private string|null $event,

//        private string|null $product_id,
        private ?string $product_product_uid,
//        private string|null $product_event,

//        private ?string $product_offer_uid = null,

        private int|null $product_total,
        private int|null $product_total_completed,
        private bool|null $priority = false,

    ) {}

    public function getManufactureApplicationId(): ManufactureApplicationUid {
        return new ManufactureApplicationUid($this->id);
    }

    public function getManufactureApplicationEvent(): ManufactureApplicationEventUid {
        return new ManufactureApplicationEventUid($this->event);
    }

    public function getPriority(): ?bool
    {
        return $this->priority;
    }

    public function getProductTotal(): ?int {
        return $this->product_total;
    }

    public function getProductTotalCompleted(): ?int {
        return $this->product_total_completed;
    }

//    public function getProductId(): ManufactureApplicationProductUid
//    {
//        return new ManufactureApplicationProductUid($this->product_id);
//    }

    public function getProductProductId(): ProductEventUid
    {
        return new ProductEventUid($this->product_product_uid);
    }

}