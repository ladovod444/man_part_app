<?php

namespace BaksDev\Manufacture\Part\Application\Repository\ActionByMain;

use BaksDev\Core\Doctrine\DBALQueryBuilder;
use BaksDev\Core\Doctrine\ORMQueryBuilder;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use BaksDev\Products\Category\Type\Id\CategoryProductUid;
use BaksDev\Users\Profile\UserProfile\Repository\UserProfileTokenStorage\UserProfileTokenStorageInterface;
use BaksDev\Users\UsersTable\Entity\Actions\Event\UsersTableActionsEvent;
use BaksDev\Users\UsersTable\Entity\Actions\Products\UsersTableActionsProduct;
use BaksDev\Users\UsersTable\Entity\Actions\UsersTableActions;
use BaksDev\Users\UsersTable\Type\Actions\Event\UsersTableActionsEventUid;

final class ActionByMainRepository implements ActionByMainInterface
{

    public function __construct(
        private readonly DBALQueryBuilder $DBALQueryBuilder,
//        private readonly UserProfileTokenStorageInterface $UserProfileTokenStorage
//        private readonly ORMQueryBuilder $ORMQueryBuilder
    ) {}

    public function findUsersTableActionByMain(ManufactureApplicationUid $main): UsersTableActionsEventUid|string
//    public function findUsersTableActionByMain(ManufactureApplicationUid $main): array|null|object
    {
//        $qb = $this->ORMQueryBuilder->createQueryBuilder(self::class);
//        $qb = $this->dbal->createQueryBuilder(self::class);

        $dbal = $this->DBALQueryBuilder
            ->createQueryBuilder(self::class)
            ->bindLocal();

//        $select = sprintf('new %s(action.event)', UsersTableActionsEventUid::class);

//        $qb->select($select);
        $dbal->select('event.id');

        $dbal
            ->from(UsersTableActionsEvent::class, 'event')
//            ->addSelect()
            ->where('event.main = :main')
            ->setParameter(
                key: 'main',
                value: $main,
                type: ManufactureApplicationUid::TYPE
            );

//        dd($dbal->fetchAssociative());

        $result = $dbal->fetchAssociative();

//        $dbal->update(UsersTableActionsEvent::class);

        return new UsersTableActionsEventUid($result['id']);
    }
}