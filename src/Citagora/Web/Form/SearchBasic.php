<?php

namespace Citagora\Web\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchBasic extends AbstractType
{
    /** @inherit */
    public function getName()
    {
       return 'SearchBasic';
    }

    // --------------------------------------------------------------

    /** @inherit */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('query', 'text', array(
            'label'       => 'Search Query',
            'required'    => true,
            'trim'        => true,
            'max_length'  => 255,
            'attr'       => array(
                'placeholder' => 'Enter Query, Keywords, or DOI'
            )
        ));
    }
}

/* EOF: SearchBasic.php */