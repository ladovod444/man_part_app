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

namespace BaksDev\Manufacture\Part\Application\Messenger\ManufactureApplicationProductComplete;

use BaksDev\Manufacture\Part\Application\UseCase\Admin\Completed\ManufactureApplicationCompletedDTO;
use BaksDev\Manufacture\Part\Application\UseCase\Admin\Completed\ManufactureApplicationCompletedHandler;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Обработчик сообщения ManufacturePartProductMessage,
 * которое диспатчится при добавлении товара в Производственную партию, если is_completed true
 * @see vendor/baks-dev/manufacture-part/Controller/Admin/Products/AddSelectedProductsController.php
 */
#[AsMessageHandler(priority: 10)]
final class ManufactureApplicationProductCompleteHandler
{
    public function __construct(
        private readonly ManufactureApplicationCompletedHandler $applicationCompletedHandler,
    ) {}

    public function __invoke(ManufactureApplicationProductCompleteMessage $message): void
    {

        $ManufactureApplicationCompletedDTO = new ManufactureApplicationCompletedDTO();

        $event_uid = $message->getEvent();
        $ManufactureApplicationCompletedDTO->setId($event_uid);
        $this->applicationCompletedHandler->handle($ManufactureApplicationCompletedDTO);

    }
}