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

use BaksDev\Core\Doctrine\DBALQueryBuilder;
use BaksDev\Core\Form\Search\SearchDTO;
use BaksDev\Core\Services\Paginator\PaginatorInterface;
use BaksDev\Manufacture\Part\Application\Entity\Event\ManufactureApplicationEvent;
use BaksDev\Manufacture\Part\Application\Entity\ManufactureApplication;
use BaksDev\Manufacture\Part\Application\Entity\Product\ManufactureApplicationProduct;
use BaksDev\Products\Product\Entity\Offers\Image\ProductOfferImage;
use BaksDev\Products\Product\Entity\Offers\ProductOffer;
use BaksDev\Products\Product\Entity\Offers\Variation\Image\ProductVariationImage;
use BaksDev\Products\Product\Entity\Offers\Variation\Modification\Image\ProductModificationImage;
use BaksDev\Products\Product\Entity\Offers\Variation\Modification\ProductModification;
use BaksDev\Products\Product\Entity\Offers\Variation\ProductVariation;
use BaksDev\Products\Product\Entity\Photo\ProductPhoto;
use BaksDev\Products\Product\Entity\Trans\ProductTrans;
use BaksDev\Users\Profile\UserProfile\Entity\Personal\UserProfilePersonal;
use BaksDev\Users\Profile\UserProfile\Entity\UserProfile;
use BaksDev\Users\Profile\UserProfile\Repository\UserProfileTokenStorage\UserProfileTokenStorageInterface;
use BaksDev\Users\UsersTable\Entity\Actions\Trans\UsersTableActionsTrans;

final class AllManufacturePartApplicationRepository implements AllManufacturePartApplicationInterface
{
    private ?SearchDTO $search = null;

    public function __construct(
        private readonly DBALQueryBuilder $DBALQueryBuilder,
        private readonly UserProfileTokenStorageInterface $UserProfileTokenStorage,
        private readonly PaginatorInterface $paginator,
    ) {}

    public function search(SearchDTO $search): self
    {
        $this->search = $search;
        return $this;
    }

    public function findPaginator(): PaginatorInterface
    {
        $dbal = $this->DBALQueryBuilder
            ->createQueryBuilder(self::class)
            ->bindLocal();

        $dbal
            ->select('manufacture_application.id')
            ->addSelect('manufacture_application.event')
            ->from(ManufactureApplication::class, 'manufacture_application');

        $dbal

            ->addSelect('manufacture_application_event.priority')
            ->leftJoin(
                'manufacture_application',
                ManufactureApplicationEvent::class,
                'manufacture_application_event',
                'manufacture_application_event.id = manufacture_application.event'
            );

        $dbal

            ->addSelect('manufacture_application_product.product as product_uid')
            ->addSelect('manufacture_application_product.offer as product_offer_uid')
            ->addSelect('manufacture_application_product.total as product_total')
            ->leftJoin(
                'manufacture_application_event',
                ManufactureApplicationProduct::class,
                'manufacture_application_product',
                'manufacture_application_event.id = manufacture_application_product.event'
            );

        $dbal
            ->addSelect('product_trans.name AS product_name')
            ->leftJoin(
                'manufacture_application_product',
                ProductTrans::class,
                'product_trans',
                'product_trans.event = manufacture_application_product.product AND product_trans.local = :local',
            );



        $dbal->leftJoin(
            'manufacture_application_product',
            ProductOffer::class,
            'product_offer',
            'product_offer.event = manufacture_application_product.product',
        );

        $dbal->leftJoin(
            'product_offer',
            ProductVariation::class,
            'product_variation',
            'product_variation.offer = product_offer.id',
        );

        $dbal->leftJoin(
            'product_variation',
            ProductModification::class,
            'product_modification',
            'product_modification.variation = product_variation.id',
        );


        // Фото продукта

        $dbal->leftJoin(
            'product_modification',
            ProductModificationImage::class,
            'product_modification_image',
            '
                product_modification_image.modification = product_modification.id AND
                product_modification_image.root = true
			',
        );


        $dbal->leftJoin(
            'product_offer',
            ProductVariationImage::class,
            'product_variation_image',
            '
                product_variation_image.variation = product_variation.id AND
                product_variation_image.root = true
			',
        );


        $dbal->leftJoin(
            'product_offer',
            ProductOfferImage::class,
            'product_offer_images',
            '
			product_variation_image.name IS NULL AND
			product_offer_images.offer = product_offer.id AND
			product_offer_images.root = true
			',
        );


        $dbal->leftJoin(
            'product_offer',
            ProductPhoto::class,
            'product_photo',
            '
                product_offer_images.name IS NULL AND
                product_photo.event = manufacture_application_product.product AND
                product_photo.root = true
			');


        $dbal->addSelect("
                CASE
    
                    WHEN product_modification_image.name IS NOT NULL THEN
                        CONCAT ( '/upload/".$dbal->table(ProductModificationImage::class)."' , '/', product_modification_image.name)
                    WHEN product_variation_image.name IS NOT NULL THEN
                        CONCAT ( '/upload/".$dbal->table(ProductVariationImage::class)."' , '/', product_variation_image.name)
                    WHEN product_offer_images.name IS NOT NULL THEN
                        CONCAT ( '/upload/".$dbal->table(ProductOfferImage::class)."' , '/', product_offer_images.name)
                    WHEN product_photo.name IS NOT NULL THEN
                        CONCAT ( '/upload/".$dbal->table(ProductPhoto::class)."' , '/', product_photo.name)
                    ELSE NULL
                   
                END AS product_image
            ");


        // Расширение файла
        $dbal->addSelect(
            '
            COALESCE(
                product_modification_image.ext,
                product_variation_image.ext,
                product_offer_images.ext,
                product_photo.ext
            ) AS product_image_ext',
        );


        $dbal->addSelect(
            '
            COALESCE(
                product_modification_image.cdn,
                product_variation_image.cdn,
                product_offer_images.cdn,
                product_photo.cdn
            ) AS product_image_cdn',
        );


        //////////////////////////////////////////////////////




        /**
         * Производственный процесс
         */
        $dbal
            ->addSelect('action_trans.name AS action_name')
            ->leftJoin(
                'manufacture_application_event',
                UsersTableActionsTrans::class,
                'action_trans',
                //                'action_trans.event = manufacture_application_event.action AND action_trans.local = :local'
                'action_trans.event = manufacture_application_event.action AND action_trans.local = :local'
            );

        /** Ответственное лицо (Профиль пользователя) */

        $dbal->leftJoin(
            'manufacture_application_event',
            UserProfile::class,
            'users_profile',
            'users_profile.id = manufacture_application_event.fixed'
        );

        $dbal
            ->addSelect('users_profile_personal.username AS users_profile_username')
            ->leftJoin(
                'users_profile',
                UserProfilePersonal::class,
                'users_profile_personal',
                'users_profile_personal.event = users_profile.event'
            );


        if($this->search?->getQuery())
        {
            $dbal
                ->createSearchQueryBuilder($this->search, true)
                ->addSearchEqualUid('manufacture_application.id')
                ->addSearchEqualUid('manufacture_application.event')
                ->addSearchEqualUid('manufacture_application_product.id')
                ->addSearchEqualUid('product_variation.id')
                ->addSearchEqualUid('product_modification.id')
                ->addSearchLike('product_trans.name');
                //->addSearchLike('product_trans.preview')
//                ->addSearchLike('product_info.article')
//                ->addSearchLike('product_offer.article')
//                ->addSearchLike('product_modification.article')
//                ->addSearchLike('product_modification.article')
//                ->addSearchLike('product_variation.article');
        }

        $dbal->addOrderBy('manufacture_application_event.priority DESC');
        $dbal->addOrderBy('manufacture_application.id ASC');

        $dbal->allGroupByExclude();

//        dd($dbal->fetchAllAssociative());


//        dd($this->paginator->fetchAllAssociative($dbal)->getData());

        return $this->paginator->fetchAllAssociative($dbal);

    }

//    public function findAll(): false|\Generator
//    {
//        $dbal = $this->DBALQueryBuilder
//            ->createQueryBuilder(self::class)
//            ->bindLocal();
//
//        $dbal
//            ->select('manufacture_application.id')
//            ->addSelect('manufacture_application.event')
//            ->from(ManufactureApplication::class, 'manufacture_application');
//
//        $dbal
//
//            ->addSelect('manufacture_application_event.priority')
//            ->leftJoin(
//            'manufacture_application',
//            ManufactureApplicationEvent::class,
//            'manufacture_application_event',
//            'manufacture_application_event.id = manufacture_application.event'
//        );
//
//        $dbal
//
//            ->addSelect('manufacture_application_product.product as product_uid')
//            ->addSelect('manufacture_application_product.offer as product_offer_uid')
//            ->addSelect('manufacture_application_product.total as product_total')
//            ->leftJoin(
//            'manufacture_application_event',
//            ManufactureApplicationProduct::class,
//            'manufacture_application_product',
//            'manufacture_application_event.id = manufacture_application_product.event'
//        );
//
//        $dbal
//            ->addSelect('product_trans.name AS product_name')
//            ->leftJoin(
//                'manufacture_application_product',
//                ProductTrans::class,
//                'product_trans',
//                'product_trans.event = manufacture_application_product.product AND product_trans.local = :local',
//            );
//
//
//
//
//
//
//        //////////////////////////////////////////////////////
//
//        $dbal->leftJoin(
//            'manufacture_application_product',
//            ProductOffer::class,
//            'product_offer',
//            'product_offer.event = manufacture_application_product.product',
//        );
//
//        $dbal->leftJoin(
//            'product_offer',
//            ProductVariation::class,
//            'product_variation',
//            'product_variation.offer = product_offer.id',
//        );
//
//        $dbal->leftJoin(
//            'product_variation',
//            ProductModification::class,
//            'product_modification',
//            'product_modification.variation = product_variation.id',
//        );
//
//
//        // Фото продукта
//
//
//        $dbal->leftJoin(
//            'product_modification',
//            ProductModificationImage::class,
//            'product_modification_image',
//            '
//                product_modification_image.modification = product_modification.id AND
//                product_modification_image.root = true
//			',
//        );
//
//
//        $dbal->leftJoin(
//            'product_offer',
//            ProductVariationImage::class,
//            'product_variation_image',
//            '
//                product_variation_image.variation = product_variation.id AND
//                product_variation_image.root = true
//			',
//        );
//
//
//        $dbal->leftJoin(
//            'product_offer',
//            ProductOfferImage::class,
//            'product_offer_images',
//            '
//			product_variation_image.name IS NULL AND
//			product_offer_images.offer = product_offer.id AND
//			product_offer_images.root = true
//			',
//        );
//
//
//        $dbal->leftJoin(
//            'product_offer',
//            ProductPhoto::class,
//            'product_photo',
//            '
//                product_offer_images.name IS NULL AND
//                product_photo.event = manufacture_application_product.product AND
//                product_photo.root = true
//			');
//
//
//        $dbal->addSelect("
//                CASE
//
//                    WHEN product_modification_image.name IS NOT NULL THEN
//                        CONCAT ( '/upload/".$dbal->table(ProductModificationImage::class)."' , '/', product_modification_image.name)
//                    WHEN product_variation_image.name IS NOT NULL THEN
//                        CONCAT ( '/upload/".$dbal->table(ProductVariationImage::class)."' , '/', product_variation_image.name)
//                    WHEN product_offer_images.name IS NOT NULL THEN
//                        CONCAT ( '/upload/".$dbal->table(ProductOfferImage::class)."' , '/', product_offer_images.name)
//                    WHEN product_photo.name IS NOT NULL THEN
//                        CONCAT ( '/upload/".$dbal->table(ProductPhoto::class)."' , '/', product_photo.name)
//                    ELSE NULL
//
//                END AS product_image
//            ");
//
//
//        // Расширение файла
//        $dbal->addSelect(
//            '
//            COALESCE(
//                product_modification_image.ext,
//                product_variation_image.ext,
//                product_offer_images.ext,
//                product_photo.ext
//            ) AS product_image_ext',
//        );
//
//
//        $dbal->addSelect(
//            '
//            COALESCE(
//                product_modification_image.cdn,
//                product_variation_image.cdn,
//                product_offer_images.cdn,
//                product_photo.cdn
//            ) AS product_image_cdn',
//        );
//
//
//        //////////////////////////////////////////////////////
//
//
//
//
//        /**
//         * Производственный процесс
//         */
//        $dbal
//            ->addSelect('action_trans.name AS action_name')
//            ->leftJoin(
//                'manufacture_application_event',
//                UsersTableActionsTrans::class,
//                'action_trans',
////                'action_trans.event = manufacture_application_event.action AND action_trans.local = :local'
//                'action_trans.event = manufacture_application_event.action AND action_trans.local = :local'
//            );
//
//        /** Ответственное лицо (Профиль пользователя) */
//
//        $dbal->leftJoin(
//            'manufacture_application_event',
//            UserProfile::class,
//            'users_profile',
//            'users_profile.id = manufacture_application_event.fixed'
//        );
//
//        $dbal
//            ->addSelect('users_profile_personal.username AS users_profile_username')
//            ->leftJoin(
//                'users_profile',
//                UserProfilePersonal::class,
//                'users_profile_personal',
//                'users_profile_personal.event = users_profile.event'
//            );
//
//        $dbal->addOrderBy('manufacture_application_event.priority DESC');
//        $dbal->addOrderBy('manufacture_application.id ASC');
//
//        $dbal->allGroupByExclude();
//
//        $result = $dbal->fetchAllHydrate(AllManufacturePartApplicationResult::class);
//
//        return ($result->valid() === true) ? $result : false;
//    }

}