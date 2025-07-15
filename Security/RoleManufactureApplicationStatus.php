<?php

namespace BaksDev\Manufacture\Part\Application\Security;


use BaksDev\Users\Profile\Group\Security\RoleInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('baks.security.role')]
class RoleManufactureApplicationStatus implements RoleInterface
{
    public const ROLE = 'ROLE_MANUFACTURE_APPLICATION_STATUS';

    public function getRole(): string
    {
        return self::ROLE;
    }
}