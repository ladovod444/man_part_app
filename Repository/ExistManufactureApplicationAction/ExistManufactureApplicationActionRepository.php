<?php

namespace BaksDev\Manufacture\Part\Application\Repository\ExistManufactureApplicationAction;

use BaksDev\Core\Doctrine\DBALQueryBuilder;
use BaksDev\Users\UsersTable\Entity\Actions\UsersTableActions;
use BaksDev\Users\UsersTable\Type\Actions\Id\UsersTableActionsUid;

class ExistManufactureApplicationActionRepository implements ExistManufactureApplicationActionInterface
{

    public function __construct(private readonly DBALQueryBuilder $DBALQueryBuilder) {}

    public function isExistManufactureApplicationAction($id): bool
    {
        $dbal = $this->DBALQueryBuilder
            ->createQueryBuilder(self::class)
            ->bindLocal();

        $dbal
            ->from(UsersTableActions::class, 'actions')
            ->where('actions.id = :id')
            ->setParameter(
                key: 'id',
                value: $id,
                type: UsersTableActionsUid::TYPE
            );

        return $dbal->fetchExist();
    }
}