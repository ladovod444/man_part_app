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

namespace BaksDev\Manufacture\Part\Application\Repository\AllManufacturePartApplication;

use BaksDev\Manufacture\Part\Application\Type\Event\ManufactureApplicationEventUid;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use BaksDev\Products\Product\Type\Id\ProductUid;
use BaksDev\Products\Product\Type\Offers\Id\ProductOfferUid;

class AllManufacturePartApplicationResult
{
    public function __construct(
        private string|null $id,
        private string|null $event,

        private string|null $product_uid,
        private string|null $product_offer_uid,

        private string|null $action_name,

        private string|null $users_profile_username,

        private string|null $product_name,

        private bool|null $priority,

        private int|null $product_total,
    ) {}

    public function getPriority(): ?bool
    {
        return $this->priority;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function getUsersProfileUsername(): ?string
    {
        return $this->users_profile_username;
    }

    public function getActionName(): ?string
    {
        return $this->action_name;
    }

    public function getManufactureApplicationId(): ManufactureApplicationUid {
        return new ManufactureApplicationUid($this->id);
    }

    public function getManufactureApplicationEvent(): ManufactureApplicationEventUid {
        return new ManufactureApplicationEventUid($this->event);
    }

    public function getProductId(): ProductUid
    {
        return new ProductUid($this->product_uid);
    }

    public function getProductOfferUid(): ProductOfferUid
    {
//        if(is_null($this->product_offer_uid))
//        {
//            return null;
//        }

        return new ProductOfferUid($this->product_offer_uid);
    }

    public function getProductTotal(): ?int {
        return $this->product_total;
    }

}