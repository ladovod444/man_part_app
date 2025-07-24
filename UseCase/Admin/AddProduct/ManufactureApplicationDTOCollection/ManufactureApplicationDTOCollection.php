<?php

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct\ManufactureApplicationDTOCollection;

use BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct\Product\ManufactureApplicationProductDTO;
use Doctrine\Common\Collections\ArrayCollection;

class ManufactureApplicationDTOCollection
{
    private ArrayCollection $product_data;

    private bool $priority;

    public function setPriority(bool $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    public function getPriority(): bool
    {
        return $this->priority;
    }

    public function __construct()
    {
        //        $this->manufactureApplicationsDTO = new ArrayCollection();
        $this->product_data = new ArrayCollection();
    }


    //        public function getManufactureApplicationsDTO(): ArrayCollection
    //        {
    //            return $this->manufactureApplicationsDTO;
    //        }
    //
    //        public function addManufactureApplicationsDTO(ManufactureApplicationDTO $dto): self
    //        {
    //            //        $product->setProfile($this->profile);
    //
    //            $this->manufactureApplicationsDTO->add($dto);
    //
    //            return $this;
    //        }
    //    //
    //        public function removeManufactureApplicationsDTO(ManufactureApplicationDTO $dto): self
    //        {
    //            $this->manufactureApplicationsDTO->removeElement($dto);
    //            return $this;
    //        }

    public function getProductData(): ArrayCollection
    {
        return $this->product_data;
    }

    public function addProductData(ManufactureApplicationProductDTO $product): self
    {
        //        $product->setProfile($this->profile);

        $this->product_data->add($product);

        return $this;
    }

    public function removeProductData(ManufactureApplicationProductDTO $product): self
    {
        $this->product_data->removeElement($product);
        return $this;
    }
}