<?php
/**
 * Created by PhpStorm.
 * User: kpapachristou
 * Date: 6/23/17
 * Time: 2:52 PM
 */

namespace AppBundle\Form;

use AppBundle\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('nationality', CheckboxType::class, array('mapped' => false, 'required' => false, 'data' => true))
            ->add('age', CheckboxType::class, array('mapped' => false, 'required' => false, 'data' => true ))
            ->add('gender', CheckboxType::class, array('mapped' => false, 'required' => false, 'data' => true ))
            ->add('languages', CheckboxType::class, array('mapped' => false, 'required' => false, 'data' => true))
            ->add('match', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Employee::class,
        ));
    }

}