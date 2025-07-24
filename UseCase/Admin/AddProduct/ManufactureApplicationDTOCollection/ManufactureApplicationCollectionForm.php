<?php

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct\ManufactureApplicationDTOCollection;

use BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct\ManufactureApplicationForm;
use BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct\Product\ManufactureApplicationProductForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManufactureApplicationCollectionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //
        $builder->add('product_data', CollectionType::class, [
            /** Указать вложенные формы */
            'entry_type' => ManufactureApplicationProductForm::class,
            'entry_options' => [
                'attr' => ['class' => 'products-data-box'],
                //                'show_submit' => false
            ],
            'allow_add' => true,
        ]);


//        $builder->add('product', ManufactureApplicationProductsForm::class);

        $builder->add('priority', CheckboxType::class, [
            'label' => 'Высокий приоритет',
            'required' => false,
        ]);


        /* Сохранить ******************************************************/
        $builder->add(
            'manufacture_application_collection',
            SubmitType::class,
            ['label' => 'Save', 'label_html' => true, 'attr' => ['class' => 'btn-primary']]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            //            'data_class' => ManufactureApplicationDTO::class,
            'data_class' => ManufactureApplicationDTOCollection::class,
            'method' => 'POST',
            'attr' => ['class' => 'w-100'],
        ]);
    }
}