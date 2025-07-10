<?php

declare(strict_types=1);

namespace BaksDev\Manufacture\Part\Application\Type\Id;

use BaksDev\Core\Type\UidType\Uid;
use Symfony\Component\Uid\AbstractUid;


final class ManufactureApplicationUid extends Uid
{
    /** Тестовый идентификатор */
    public const string TEST = '0197d561-fb34-7b4d-b2b4-99cc757d3abf';

    public const string TYPE = 'manufacture_application_uid';

    public const string ACTION_NAME = 'Производственная заявка';

    public const string ACTION_ID = '0197e515-2b3b-725d-8f14-94a6b73056f0';

}