<?php

namespace BaksDev\Manufacture\Part\Application\Repository\ManufactureApplicationProduct;

use BaksDev\Core\Doctrine\DBALQueryBuilder;
use BaksDev\Manufacture\Part\Application\Entity\Event\ManufactureApplicationEvent;
use BaksDev\Manufacture\Part\Application\Entity\ManufactureApplication;
use BaksDev\Manufacture\Part\Application\Entity\Product\ManufactureApplicationProduct;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use BaksDev\Manufacture\Part\Entity\Products\ManufacturePartProduct;
use BaksDev\Products\Product\Type\Event\ProductEventUid;
use BaksDev\Products\Product\Type\Offers\Id\ProductOfferUid;
use BaksDev\Products\Product\Type\Offers\Variation\Id\ProductVariationUid;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Types\IntegerType;

class ManufactureApplicationProductRepository implements ManufactureApplicationProductInterface
{
    public function __construct(private readonly DBALQueryBuilder $DBALQueryBuilder) {}

//    public function findApplicationProduct(ManufacturePartProduct $manufacturePartProduct): array
    public function findApplicationProduct(
        string|ProductEventUid $product,
        string|ProductOfferUid $offer,
        string|ProductVariationUid $variation
    ): array|false
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
            ->addSelect('manufacture_application_product.id as product_id')
            ->addSelect('manufacture_application_product.product as product_uid')
            ->addSelect('manufacture_application_product.offer as product_offer_uid')
            ->addSelect('manufacture_application_product.total as product_total')
            ->leftJoin(
                'manufacture_application_event',
                ManufactureApplicationProduct::class,
                'manufacture_application_product',
                'manufacture_application_event.id = manufacture_application_product.event'
            );

//        dump($dbal->fetchAssociative());

        $dbal->where('manufacture_application_product.product=:product')
            ->setParameter('product', $product, ProductEventUid::TYPE)
        ;

        $dbal
            ->andWhere('manufacture_application_product.offer = :offer')
            ->setParameter('offer', $offer, ProductOfferUid::TYPE);

        $dbal
            ->andWhere('manufacture_application_product.variation = :variation')
            ->setParameter('variation', $variation, ProductVariationUid::TYPE);


//        dump($dbal->getSQL());   +
        //        $dbal->analyze();

        //        $dbal->



        return $dbal->fetchAssociative();

//        dump($dbal->fetchAssociative());
//        dd(1);
//        $res = $dbal->fetchAssociative();
//        return $res;
    }


//    public function updateApplicationProduct(string|ProductEventUid $product): int|string {
    public function updateApplicationProduct(string|ManufactureApplicationUid $id, int $updated_total): int|string {
        $dbal = $this->DBALQueryBuilder
            ->createQueryBuilder(self::class)
            ->bindLocal();

        $dbal->update(ManufactureApplicationProduct::class);

        // TODO test

        // TODO передвать $total

        $dbal
            ->set('total', ':total')
            ->setParameter('total', $updated_total, ParameterType::INTEGER);

        //TEST
        /*
        $product = $product instanceof ProductEventUid ? $product : new ProductEventUid($product);
        $dbal
            ->where('product = :product')
            ->setParameter('product', $product, ProductEventUid::TYPE);
        */

        // test
//        $id = '0197f562-2566-7a1d-81e2-f2887d0d118f';

        $id = $id instanceof ManufactureApplicationUid ? $id : new ManufactureApplicationUid($id);
        $dbal
            ->where('id = :id')
            ->setParameter('id', $id, ManufactureApplicationUid::TYPE);

       return $dbal->executeStatement();
    }

}