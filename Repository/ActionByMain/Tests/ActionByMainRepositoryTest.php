<?php

namespace BaksDev\Manufacture\Part\Application\Repository\ActionByMain\Tests;

use BaksDev\Manufacture\Part\Application\Repository\ActionByMain\ActionByMainInterface;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Attribute\When;

/**
 * @group manufacture-application-part-main
 */
#[When(env: 'test')]
class ActionByMainRepositoryTest extends KernelTestCase
{
    public function testActionByMain ()
    {
        /** @var ActionByMainInterface $repository */
        $repository = self::getContainer()->get(ActionByMainInterface::class);

        $result = $repository->findUsersTableActionByMain( new ManufactureApplicationUid(ManufactureApplicationUid::ACTION_ID));

        dd($result);
    }
}