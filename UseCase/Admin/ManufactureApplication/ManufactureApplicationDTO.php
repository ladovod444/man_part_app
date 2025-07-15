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

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\ManufactureApplication;

use BaksDev\Manufacture\Part\Application\Repository\ActionByMain\ActionByMainInterface;
use BaksDev\Manufacture\Part\Application\Type\Event\ManufactureApplicationEventUid;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct\ManufactureApplicationProductsDTO;
use BaksDev\Users\Profile\UserProfile\Type\Id\UserProfileUid;
use BaksDev\Users\UsersTable\Type\Actions\Event\UsersTableActionsEventUid;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/** @see ManufactureApplicationEvent */
final class ManufactureApplicationDTO /*implements ManufactureApplicationEventInterface */
{

    /**
     * Идентификатор события
     */
    #[Assert\Uuid]
    private ?ManufactureApplicationEventUid $id = null;

    /**
     * Профиль ответственного.
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private UserProfileUid $fixed;

    /**
     * Приоритет
     */
    private bool $priority = false;

    /**
     * Идентификатор события
     */
    public function getEvent(): ?ManufactureApplicationEventUid
    {
        return $this->id;
    }

    public function setId(ManufactureApplicationEventUid $eventUid): self
    {
        $this->id = $eventUid;

        return $this;
    }


    /**
     * Идентификатор процесса производства
     */
    //    private UsersTableActionsEventUid $action;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    private UsersTableActionsEventUid $action;

    private ArrayCollection $application_product_form_data;

    public function __construct(UserProfileUid $profile, ActionByMainInterface $actionByMain)
    {
        $this->application_product_form_data = new ArrayCollection();
        $this->fixed = $profile;

        $action = $actionByMain->findUsersTableActionByMain(new ManufactureApplicationUid(ManufactureApplicationUid::ACTION_ID));
        $this->action = $action;

    }

    public function getApplicationProductFormData(): ArrayCollection
    {
        return $this->application_product_form_data;
    }

    public function addApplicationProductFormData(ManufactureApplicationProductsDTO $product): self
    {
        //        $product->setProfile($this->profile);

        $this->application_product_form_data->add($product);

        return $this;
    }

    public function removeApplicationProductFormData(ManufactureApplicationProductsDTO $product): self
    {
        $this->application_product_form_data->removeElement($product);
        return $this;
    }

    /**
     * Fixed
     */
    public function getFixed(): UserProfileUid
    {
        return $this->fixed;
    }

    public function setFixed(UserProfileUid $fixed): self
    {
        $this->fixed = $fixed;
        return $this;
    }

    /**
     * Action
     */
    public function getAction(): UsersTableActionsEventUid
    {
        return $this->action;
    }

    public function setAction(UsersTableActionsEventUid $action): self
    {
        $this->action = $action;
        return $this;
    }

    public function getPriority(): bool
    {
        return $this->priority;
    }

    public function setPriority(bool $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

}