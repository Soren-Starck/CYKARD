<?php

namespace App\Form;

use App\Entity\Carte;
use App\Entity\Colonne;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titrecarte')
            ->add('descriptifcarte')
            ->add('couleurcarte')
            ->add('idcolonne')
            ->add('colonne', EntityType::class, [
                'class' => Colonne::class,
                'choice_label' => 'id',
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carte::class,
        ]);
    }
}
