<?php

namespace Citagora\Web\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserLogin extends AbstractType
{
    /** @inherit */
    public function getName()
    {
       return 'UserLogin';
    }

    // --------------------------------------------------------------

    /** @inherit */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'text', array(
            'label'       => 'Email Address',
            'required'    => true,
            'trim'        => true,
            'max_length'  => 255,
            'attr'       => array(
                'placeholder' => 'user@example.com'
            )
        ));

        $builder->add('password', 'password', array(
            'label'       => 'Password',
            'required'    => true,
            'trim'        => true,
            'max_length'  => 255
        ));
    }
}

/* EOF: UserLogin.php */