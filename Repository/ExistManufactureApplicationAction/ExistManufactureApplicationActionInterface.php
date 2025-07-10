<?php

namespace BaksDev\Manufacture\Part\Application\Repository\ExistManufactureApplicationAction;

interface ExistManufactureApplicationActionInterface
{
    /**
     * Метод проверяет, имеется ли производстенный процесс с указаным id
     */
    public function isExistManufactureApplicationAction(string $id): bool;
}