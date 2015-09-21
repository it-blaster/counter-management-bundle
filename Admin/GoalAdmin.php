<?php

namespace ItBlaster\CounterManagementBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class GoalAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('Name')
            ->add('Code')
            ->add('Counter')
            ->add('Page')
            ->add('ButtonType')
            ->add('Vendor')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('Id')
            ->add('Name')
            ->add('Code')
            ->add('Counter')
            ->add('Page')
            ->add('ButtonType')
            ->add('Vendor')
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
            ->add('Id')
            ->add('Name')
            ->add('Code')
            ->add('Counter')
            ->add('Page')
            ->add('ButtonType')
            ->add('Vendor')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('Id')
            ->add('Name')
            ->add('Code')
            ->add('Counter')
            ->add('Page')
            ->add('ButtonType')
            ->add('Vendor')
        ;
    }
}
