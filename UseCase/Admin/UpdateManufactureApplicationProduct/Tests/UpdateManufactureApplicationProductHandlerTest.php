<?php

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\UpdateManufactureApplicationProduct\Tests;

use BaksDev\Manufacture\Part\Application\Entity\Event\ManufactureApplicationEvent;
use BaksDev\Manufacture\Part\Application\Entity\ManufactureApplication;
use BaksDev\Manufacture\Part\Application\Type\Event\ManufactureApplicationEventUid;
use BaksDev\Manufacture\Part\Application\UseCase\Admin\UpdateManufactureApplicationProduct\Product\UpdateManufactureApplicationProductDTO;
use BaksDev\Manufacture\Part\Application\UseCase\Admin\UpdateManufactureApplicationProduct\UpdateManufactureApplicationDTO;
use BaksDev\Manufacture\Part\Application\UseCase\Admin\UpdateManufactureApplicationProduct\UpdateManufactureApplicationProductHandler;
use BaksDev\Products\Product\Type\Event\ProductEventUid;
use BaksDev\Products\Product\Type\Offers\Id\ProductOfferUid;
use BaksDev\Products\Product\Type\Offers\Variation\Id\ProductVariationUid;
use BaksDev\Products\Product\Type\Offers\Variation\Modification\Id\ProductModificationUid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\Attribute\When;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\DependsOnClass;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @group manufacture-part-application
 * @depends BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct\Tests\ManufactureApplicationHandlerTest::class
 */
#[Group('manufacture-part-application')]
#[When(env: 'test')]
class UpdateManufactureApplicationProductHandlerTest extends KernelTestCase
{
    public function testUseCase(): void
    {
        // Бросаем событие консольной комманды
        $dispatcher = self::getContainer()->get(EventDispatcherInterface::class);
        $event = new ConsoleCommandEvent(new Command(), new StringInput(''), new NullOutput());
        $dispatcher->dispatch($event, 'console.command');


        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);

        $ManufactureApplicationEvent = $em->getRepository(ManufactureApplicationEvent::class)
            ->find(ManufactureApplicationEventUid::TEST);

//        $UpdateManufactureApplicationProductDTO = new UpdateManufactureApplicationProductDTO();


//        $ManufactureApplicationProductDTO = $ManufactureApplicationDTO
//            ->getProduct();

        /** @see $UpdateManufactureApplicationDTO */
        $UpdateManufactureApplicationDTO = new UpdateManufactureApplicationDTO();
        $UpdateManufactureApplicationProductDTO = $UpdateManufactureApplicationDTO->getProduct();

        $UpdateManufactureApplicationProductDTO->setTotal(20);
        $UpdateManufactureApplicationDTO->setId(new ManufactureApplicationEventUid(ManufactureApplicationEventUid::TEST));


        $ManufactureApplicationHandler = self::getContainer()->get(UpdateManufactureApplicationProductHandler::class);
        $handle = $ManufactureApplicationHandler->handle($UpdateManufactureApplicationDTO);

        self::assertTrue(($handle instanceof ManufactureApplication), $handle.': Ошибка ManufactureApplication');

//        $UpdateManufactureApplicationDTO
//            ->setProduct(new ProductEventUid(ProductEventUid::TEST))
//            ->setOffer(new ProductOfferUid(ProductOfferUid::TEST))
//            ->setVariation(new ProductVariationUid(ProductVariationUid::TEST))
//            ->setModification(new ProductModificationUid(ProductModificationUid::TEST))
//            ->setTotal(10);

//        $UpdateManufactureApplicationProductDTO->setTotal(33);
//        $UpdateManufactureApplicationProductDTO->setCompleted(33);
//
//        $UpdateManufactureApplicationProductDTO->setId($ManufactureApplicationProductResult->getManufactureApplicationEvent());



//        $UpdateManufactureApplicationDTO->setId($ManufactureApplicationProductResult->getManufactureApplicationEvent());

//        $UpdateManufactureApplicationDTO->setProduct($UpdateManufactureApplicationProductDTO);

    }
}