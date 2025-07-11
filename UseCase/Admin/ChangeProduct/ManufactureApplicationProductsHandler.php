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

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\ChangeProduct;

use BaksDev\Core\Entity\AbstractHandler;
use BaksDev\Core\Messenger\MessageDispatchInterface;
use BaksDev\Core\Validator\ValidatorCollectionInterface;
use BaksDev\Files\Resources\Upload\File\FileUploadInterface;
use BaksDev\Files\Resources\Upload\Image\ImageUploadInterface;
use BaksDev\Manufacture\Part\Application\Entity\Product\ManufactureApplicationProduct;
use BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct\ManufactureApplicationProductsDTO;
use BaksDev\Manufacture\Part\Entity\Products\ManufacturePartProduct;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ManufactureApplicationProductsHandler extends AbstractHandler
{
    public function __construct(
        #[Target('manufacturePartLogger')] private readonly LoggerInterface $logger,
//        private readonly OpenManufacturePartByActionInterface $openManufacturePartByAction,

        EntityManagerInterface $entityManager,
        MessageDispatchInterface $messageDispatch,
        ValidatorCollectionInterface $validatorCollection,
        ImageUploadInterface $imageUpload,
        FileUploadInterface $fileUpload,
    )
    {
        parent::__construct($entityManager, $messageDispatch, $validatorCollection, $imageUpload, $fileUpload);

    }


    /** @see ManufacturePart */
    public function handle(ManufactureApplicationProductsDTO $command): string|ManufactureApplicationProduct
    {

        $ManufacturePartProduct = new ManufacturePartProduct(null);

        return $ManufacturePartProduct;
    }
}
