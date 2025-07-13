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

namespace BaksDev\Manufacture\Part\Application\Entity\Product;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Manufacture\Part\Application\Entity\Event\ManufactureApplicationEvent;
use BaksDev\Manufacture\Part\Application\Type\Product\ManufactureApplicationProductUid;
use BaksDev\Products\Product\Type\Event\ProductEventUid;
use BaksDev\Products\Product\Type\Offers\Id\ProductOfferUid;
use BaksDev\Products\Product\Type\Offers\Variation\Id\ProductVariationUid;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'manufacture_application_product')]
#[ORM\Index(columns: ['event'])]
//#[ORM\Index(columns: ['product', 'offer', 'variation', 'modification'])]
#[ORM\Index(columns: ['product', 'offer', 'variation'])]
class ManufactureApplicationProduct extends EntityEvent
{
    /**
     * Идентификатор
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: ManufactureApplicationProductUid::TYPE)]
    private ManufactureApplicationProductUid $id;

    /** Связь на событие */
    #[Assert\NotBlank]
//    #[ORM\ManyToOne(targetEntity: ManufactureApplicationEvent::class, inversedBy: "product")]
//    #[ORM\OneToOne(targetEntity: ManufactureApplicationEvent::class, inversedBy: "product", cascade: ['persist'] )]
    #[ORM\OneToOne(targetEntity: ManufactureApplicationEvent::class, inversedBy: "product", cascade: ['all'] )]
    #[ORM\JoinColumn(name: 'event', referencedColumnName: "id")]
    private ManufactureApplicationEvent $event;

//    public function getEvent(): ManufactureApplicationEvent
//    {
//        return $this->event;
//    }

    /**
     * Идентификатор События!!! продукта
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Column(type: ProductEventUid::TYPE)]
    private ProductEventUid $product;

    public function getProduct(): ProductEventUid
    {
        return $this->product;
    }

    /**
     * Идентификатор торгового предложения
     */
    #[Assert\Uuid]
    #[ORM\Column(type: ProductOfferUid::TYPE, nullable: true)]
    private ?ProductOfferUid $offer;

    /**
     * Идентификатор торгового предложения
     */
    #[Assert\Uuid]
    #[ORM\Column(type: ProductVariationUid::TYPE, nullable: true)]
    private ?ProductVariationUid $variation;

    /**
     * Количество в заявке
     */
    #[Assert\NotBlank]
    #[Assert\Range(min: 1)]
    #[ORM\Column(type: Types::INTEGER)]
    private int $total;

    public function __construct(?ManufactureApplicationEvent $event)
    {
        $this->id = new ManufactureApplicationProductUid();
        $this->event = $event;
    }

    public function __clone()
    {
        $this->id = clone $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }


    public function getDto($dto): mixed
    {
        $dto = is_string($dto) && class_exists($dto) ? new $dto() : $dto;

//        if($dto instanceof ManufacturePartProductInterface)
        if($dto instanceof ManufactureApplicationProductInterface)
        {
            return parent::getDto($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }

    public function setEntity($dto): mixed
    {
        if($dto instanceof ManufactureApplicationProductInterface || $dto instanceof self)
        {
            return parent::setEntity($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }
}