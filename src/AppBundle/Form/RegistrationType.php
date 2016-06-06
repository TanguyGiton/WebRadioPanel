<?php

namespace AppBundle\Form;

use AppBundle\Utils\RolesHelper;
use libphonenumber\PhoneNumberFormat;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    /**
     * @var RolesHelper
     */
    protected $rolesHelper;

    public function __construct(RolesHelper $rolesHelper)
    {
        $this->rolesHelper = $rolesHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', null, array('label' => 'label.first_name'));
        $builder->add('lastName', null, array('label' => 'label.last_name'));
        $builder->add('birthdate', BirthdayType::class, array('label' => 'label.birthdate', 'years' => range((int)date('Y') - 100, (int)date('Y'), 1)));
        $builder->add('mobilePhoneNumber', PhoneNumberType::class, array('label' => 'label.mobile_phone_number', 'default_region' => 'FR', 'format' => PhoneNumberFormat::NATIONAL));
        $builder->add('address', null, array('label' => 'label.address'));
        $builder->add('postcode', null, array('label' => 'label.postcode'));
        $builder->add('city', null, array('label' => 'label.city'));
        $builder->add('roles', ChoiceType::class, array(
            'label' => 'label.roles',
            'multiple' => true,
            'expanded' => true,
            'choices' => $this->rolesHelper->getRoles(),
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}