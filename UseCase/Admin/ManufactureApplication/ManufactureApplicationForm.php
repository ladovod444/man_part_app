<?php

declare(strict_types=1);

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\ManufactureApplication;


use BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct\ManufactureApplicationProductsForm;
use BaksDev\Manufacture\Part\Application\Entity\ManufactureApplication;
use BaksDev\Users\UsersTable\Type\Actions\Event\UsersTableActionsEventUid;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ManufactureApplicationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $builder->add('name', TextType::class, ['required' => false]);

        $builder->add('application_product_form_data', CollectionType::class, [
            /** Указать вложенные формы */
            'entry_type' => ManufactureApplicationProductsForm::class,
            'entry_options' => [
                'attr' => ['class' => 'products-data-box'],
//                'show_submit' => false
            ],
            'allow_add' => true,
        ]);


        $builder->add('priority', CheckboxType::class, [
            'label'    => 'Высокий приоритет',
            'required' => false,
        ]);

//        $builder->add('action', HiddenType::class);

//        $builder->add('action', TextType::class, ['required' => false]);

//        $builder->get('action')->addModelTransformer(
//            new CallbackTransformer(
//                function($action) {
//                    return $action instanceof UsersTableActionsEventUid ? $action->getValue() : $action;
//                },
//                function($action) {
//                    return $action ? new UsersTableActionsEventUid($action) : null;
//                }
//            )
//        );

        /* Сохранить ******************************************************/
        $builder->add(
            'manufacture_application',
            SubmitType::class,
            ['label' => 'Save', 'label_html' => true, 'attr' => ['class' => 'btn-primary']]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ManufactureApplicationDTO::class,
            'method' => 'POST',
            'attr' => ['class' => 'w-100'],
        ]);
    }
}