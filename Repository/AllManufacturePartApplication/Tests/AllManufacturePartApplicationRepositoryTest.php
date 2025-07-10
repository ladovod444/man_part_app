<?php

namespace BaksDev\Manufacture\Part\Application\Repository\AllManufacturePartApplication\Tests;

use BaksDev\Manufacture\Part\Application\Repository\AllManufacturePartApplication\AllManufacturePartApplicationInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Attribute\When;

/**
 * @group manufacture-application-part
 */
#[When(env: 'test')]
class AllManufacturePartApplicationRepositoryTest extends KernelTestCase
{
    public function testfindAll() {
        /** @var AllManufacturePartApplicationInterface $repository */
        $repository = self::getContainer()->get(AllManufacturePartApplicationInterface::class);

        $result = $repository->findAll();

//        dd(iterator_to_array($result));

        self::assertTrue(true);
    }
}