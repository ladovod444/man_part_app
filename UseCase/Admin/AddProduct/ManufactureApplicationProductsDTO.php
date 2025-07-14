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

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct;

use BaksDev\Manufacture\Part\Application\Entity\Product\ManufactureApplicationProductInterface;
use BaksDev\Manufacture\Part\Entity\Products\ManufacturePartProductInterface;
use BaksDev\Products\Product\Type\Event\ProductEventUid;
use BaksDev\Products\Product\Type\Offers\Id\ProductOfferUid;
use BaksDev\Products\Product\Type\Offers\Variation\Id\ProductVariationUid;
use BaksDev\Products\Product\Type\Offers\Variation\Modification\Id\ProductModificationUid;
use BaksDev\Users\Profile\UserProfile\Type\Id\UserProfileUid;
use Symfony\Component\Validator\Constraints as Assert;

/** @see ManufactureApplicationProduct */
final class ManufactureApplicationProductsDTO implements ManufactureApplicationProductInterface
{
    /**
     * Идентификатор ответственного лица
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private readonly UserProfileUid $profile;

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

    /**
     * Порядок сортировки
     */
    private readonly int $sort;


    public function __construct()
    {
        $this->sort = time();
    }

    /**
     * Общее количество в заявке
     */
    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): void
    {
        $this->total = $total;
    }

    /**
     * Id
     */
    public function getIdentifier(): string
    {
        $identifier = $this->getProduct();

        if($this->getOffer())
        {
            $identifier = $this->getOffer();
        }

        if($this->getVariation())
        {
            $identifier = $this->getVariation();
        }

        if($this->getModification())
        {
            $identifier = $this->getModification();
        }

        return (string) $identifier;
    }

    /**
     * Идентификатор События!!! продукта
     */
    public function getProduct(): ?ProductEventUid
    {
        return $this->product;
    }

    public function setProduct(?ProductEventUid $product): self
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Идентификатор торгового предложения
     */

    public function getOffer(): ?ProductOfferUid
    {
        return $this->offer;
    }

    public function setOffer(?ProductOfferUid $offer): self
    {
        $this->offer = $offer;
        return $this;
    }

    /**
     * Идентификатор множественного варианта торгового предложения
     */

    public function getVariation(): ?ProductVariationUid
    {
        return $this->variation;
    }

    public function setVariation(?ProductVariationUid $variation): self
    {
        $this->variation = $variation;
        return $this;
    }

    /**
     * Идентификатор модификации множественного варианта торгового предложения
     */

    public function getModification(): ?ProductModificationUid
    {
        return $this->modification;
    }

    public function setModification(?ProductModificationUid $modification): self
    {
        $this->modification = $modification;
        return $this;
    }

    public function setProfile(UserProfileUid $profile)
    {
        if(false === (new \ReflectionProperty(self::class, 'profile')->isInitialized($this)))
        {
            $this->profile = $profile;
        }

        return $this;
    }

    /**
     * Profile
     */
    public function getProfile(): UserProfileUid
    {
        return $this->profile;
    }

    /**
     * Sort
     */
    public function getSort(): int
    {
        return $this->sort;
    }
}