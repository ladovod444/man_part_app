<?php
/*
 *  Copyright 2025.  Baks.dev <admin@baks.dev>
 *  
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *  
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *  
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

declare(strict_types=1);

namespace BaksDev\Manufacture\Part\Application\Security;

use BaksDev\Menu\Admin\Command\Upgrade\MenuAdminInterface;
use BaksDev\Menu\Admin\Type\SectionGroup\Group\Collection\MenuAdminSectionGroupCollectionInterface;
use BaksDev\Products\Category\Security\MenuGroupProducts;
use BaksDev\Users\Profile\Group\Security\RoleInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('baks.security.role')]
#[AutoconfigureTag('baks.menu.admin')]
final class Role implements RoleInterface, MenuAdminInterface
{
    public const string ROLE = 'ROLE_MANUFACTURE_PART_APPLICATION';

    public const string KEY = 'e2XiA2faM';

    /**
     * Метод возвращает позицию, в которую располагается ссылка в секции меню.
     */
    public static function getSortMenu(): int
    {
        return 801;
    }


    /**
     * Добавляем раздел в меню администрирования.
     */

    public function getRole(): string
    {
        return self::ROLE;
    }

    /** Метод возвращает PATH раздела */
    public function getPath(): string
    {
        return 'manufacture-part-application:admin.index';
    }

    /**
     * Метод возвращает ключ раздела (для меню телеграм)
     */
    public function getPathKey(): string
    {
        return self::KEY;
    }

    /**
     * Метод возвращает секцию, в которую помещается ссылка на раздел.
     */
    public function getGroupMenu(): MenuAdminSectionGroupCollectionInterface|bool
    {
        return new MenuGroupProducts();
    }

    /**
     * Метод возвращает флаг "Показать в выпадающем меню".
     */
    public function getDropdownMenu(): bool
    {
        return true;
    }

    /**
     * Метод возвращает флаг "Модальное окно".
     */
    public function getModal(): bool
    {
        return false;
    }

}
