<?php

namespace App\Form;

use App\Entity\Organisms;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('phoneNumber')
            ->add('address')
            ->add('postCode')
            ->add('city')
            ->add(
                'organism',
                EntityType::class,
                [
                    'class' => Organisms::class,
                    'required' => false,
                    'choice_label' => 'organismName',
                    'expanded' => false,
                    'multiple' => false,
                    'attr' => ['class' => 'selectpicker'],
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('o')
                            ->where('o.organismStatus = \'Collecteur privé\'')
                            ->orderBy('o.organismName', 'ASC')
                            ;
                    },
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
