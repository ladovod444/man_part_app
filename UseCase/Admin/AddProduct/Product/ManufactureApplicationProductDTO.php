<?php

declare(strict_types=1);

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct\Product;

use BaksDev\Manufacture\Part\Application\Entity\Event\ManufactureApplicationEvent;
use BaksDev\Manufacture\Part\Application\Entity\Product\ManufactureApplicationProduct;
use BaksDev\Manufacture\Part\Application\Entity\Product\ManufactureApplicationProductInterface;
use BaksDev\Manufacture\Part\Application\Type\Event\ManufactureApplicationEventUid;
use BaksDev\Manufacture\Part\Application\Type\Product\ManufactureApplicationProductUid;
use BaksDev\Products\Product\Type\Event\ProductEventUid;
use BaksDev\Products\Product\Type\Offers\Id\ProductOfferUid;
use BaksDev\Products\Product\Type\Offers\Variation\Id\ProductVariationUid;
use BaksDev\Products\Product\Type\Offers\Variation\Modification\Id\ProductModificationUid;
use Symfony\Component\Validator\Constraints as Assert;

/** @see ManufactureApplicationProduct */
final class ManufactureApplicationProductDTO implements ManufactureApplicationProductInterface
{
    /**
     * Идентификатор События!!! продукта
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private ?ProductEventUid $product = null;

    /**
     * Идентификатор торгового предложения
     */
    #[Assert\Uuid]
    private ?ProductOfferUid $offer = null;

    /**
     * Идентификатор множественного варианта торгового предложения
     */
    #[Assert\Uuid]
    private ?ProductVariationUid $variation = null;

    /**
     * Идентификатор модификации множественного варианта торгового предложения
     */
    #[Assert\Uuid]
    private ?ProductModificationUid $modification = null;

    /**
     * Количество данного товара в заявке
     */
    #[Assert\NotBlank]
    #[Assert\Range(min: 1)]
    private ?int $total = null;




    public function getProduct(): ?ProductEventUid
    {
        return $this->product;
    }

    public function getOffer(): ?ProductOfferUid
    {
        return $this->offer;
    }

    public function getVariation(): ?ProductVariationUid
    {
        return $this->variation;
    }

    public function getModification(): ?ProductModificationUid
    {
        return $this->modification;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }


    public function setProduct(?ProductEventUid $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function setOffer(?ProductOfferUid $offer): self
    {
        $this->offer = $offer;
        return $this;
    }

    public function setVariation(?ProductVariationUid $variation): self
    {
        $this->variation = $variation;
        return $this;
    }

    public function setModification(?ProductModificationUid $modification): self
    {
        $this->modification = $modification;
        return $this;
    }

    public function setTotal(?int $total): self
    {
        $this->total = $total;
        return $this;
    }



}