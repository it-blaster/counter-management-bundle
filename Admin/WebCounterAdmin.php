<?php

namespace ItBlaster\CounterManagementBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints\NotBlank;

class WebCounterAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('Name', null, array(
                'label' => 'Название'
            ))
            ->add('Published', null, array(
                'label' => 'Публикация'
            ))
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);

        $collection
            ->remove('show');

        $collection
            ->add('install-counter',
                'install/counter/{id}',
                array('_controller' => 'WebCounterAdminController:installCounter'),
                array('id' => '\d+')
            )
            ->add('install-goals',
                'install/goals/{id}',
                array('_controller' => 'ItBlasterCounterManagementBundle:WebCounterAdmin:installGoals'),
                array('id' => '\d+')
            )
        ;
    }


    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('Name', null, array(
                'label' => 'Название'
            ))
            ->add('Number', null, array(
                'label' => 'Номер'
            ))
            ->add('TypeKey', null, array(
                'label' => 'Тип',
            ))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('Name', null, array(
                'label' => 'Название',
                'required' => true,
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('Number', null, array(
                'label' => 'Номер счетчика',
                'required' => false,
                'help' => 'оставте пустым что бы создать новый',
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('Code', null, array(
                'label' => 'Код счетчика',
                'disabled' => false,
            ))
            ->add('TypeKey', 'choice', array(
                'label' => 'Тип счетчика',
                'choices' => $this->getConfigurationPool()->getContainer()
                    ->get('counter_management.manager')->getProvidersChoices(),
                'constraints' => array(
                    new NotBlank()
                )
            ));
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('Id')
            ->add('Name')
            ->add('Published')
            ->add('Code')
        ;
    }


}
