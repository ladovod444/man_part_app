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

use BaksDev\Manufacture\Part\Application\Entity\ManufactureApplication;
use BaksDev\Manufacture\Part\Application\Type\Event\ManufactureApplicationEventUid;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use BaksDev\Manufacture\Part\Type\Event\ManufacturePartEventUid;
use BaksDev\Manufacture\Part\Type\Id\ManufacturePartUid;

class ManufactureApplicationProductCompleteMessage
{
    /**
     * Идентификатор
     */
    private string $id;

    /**
     * Идентификатор события
     */
    private string $event;

//    /**
//     * Идентификатор предыдущего события
//     */
//    private ?string $last;
//
//    /**
//     * Количество
//     */
//    private int $total;


    public function __construct(
        ManufactureApplicationUid|string $id,
        ManufactureApplicationEventUid|string $event,
//        ManufacturePartEventUid|string|null $last = null,
//        int $total = 0
    )
    {
        $this->id = (string) $id;
        $this->event = (string) $event;
//        $this->last = $last ? (string) $last : null;
//
//        $this->total = $total;
    }

    /**
     * Идентификатор
     */
    public function getId(): ManufactureApplicationUid
    {
        return new ManufactureApplicationUid($this->id);
    }

    /**
     * Идентификатор события
     */
    public function getEvent(): ManufactureApplicationEventUid
    {
        return new ManufactureApplicationEventUid($this->event);
    }

//    /**
//     * Идентификатор предыдущего события
//     */
//    public function getLast(): ?ManufacturePartEventUid
//    {
//        return $this->last ? new ManufacturePartEventUid($this->last) : null;
//    }
//
//    /**
//     * Total
//     */
//    public function getTotal(): int
//    {
//        return $this->total;
//    }
}