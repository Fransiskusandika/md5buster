<?php

namespace MD5BusterBundle\Admin;

use MD5BusterBundle\Entity\MD5Decryption;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class MD5DecryptionAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('hash')
            ->add('decryption')
            ->add('userAdded')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('hash')
            ->add('decryption')
            ->add('userAdded')
            ->add('_action', null, array(
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
            ->add('id')
            ->add('hash')
            ->add('decryption')
            ->add('userAdded')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('hash')
            ->add('decryption')
            ->add('userAdded')
        ;
    }

    /**
     * @param MD5Decryption $object
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof MD5Decryption ? $object->getDecryption() : 'Decryption';
    }
}
