<?php

namespace BaksDev\Manufacture\Part\Application\Messenger;

use BaksDev\Manufacture\Part\Application\Entity\Product\ManufactureApplicationProduct;
use BaksDev\Manufacture\Part\Application\Repository\ManufactureApplicationProduct\ManufactureApplicationProductInterface;
use BaksDev\Manufacture\Part\Messenger\CentrifugoPublish\ManufacturePartCentrifugoPublishMessage;
use BaksDev\Manufacture\Part\Messenger\ManufacturePartProduct\ManufacturePartProductMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(priority: 10)]
class ManufacturePartApplicationProductHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
//        private readonly DBALQueryBuilder $DBALQueryBuilder,
        private readonly ManufactureApplicationProductInterface $manufactureApplicationProduct,
    )
    {

    }

    public function __invoke(ManufacturePartProductMessage $message): void
    {

        if ($message->getTotal() !== false) {
//            dd($message->getTotal());
            
            dump($message->getEvent()); // product в таблицах mpp и map
            dump($message->getOffer());
            dump($message->getVariation());


          $manufactureApplicationProduct =  $this->manufactureApplicationProduct->findApplicationProduct($message->getEvent(), $message->getOffer(), $message->getVariation());



          // TODO test
//            $this->manufactureApplicationProduct->updateApplicationProduct('3123123');

//            $this->manufactureApplicationProduct->updateApplicationProduct($manufactureApplicationProduct['id']);


          dd($manufactureApplicationProduct);

//            $manufactureApplicationProduct = $this->entityManager->getRepository(ManufactureApplicationProduct::class)->findOneBy([
//                'product' => $message->getEvent()
//            ]);
//


        }
    }
}