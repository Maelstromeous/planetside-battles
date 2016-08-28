<?php

namespace PSB\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PSB\AdminBundle\Entity\Pages;
use PSB\AdminBundle\Form\PagesType;

/**
 * Pages controller.
 *
 */
class PagesController extends Controller
{

    /**
     * Lists all Pages entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PSBAdminBundle:Pages')->findBy(array(), array('section' => 'ASC'));

        return $this->render('PSBAdminBundle:Pages:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Pages entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Pages();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('pages_show', array('id' => $entity->getId())));
        }

        return $this->render('PSBAdminBundle:Pages:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Pages entity.
     *
     * @param Pages $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Pages $entity)
    {
        $form = $this->createForm(new PagesType(), $entity, array(
            'action' => $this->generateUrl('pages_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('formnovalidate' => true)));

        return $form;
    }

    /**
     * Displays a form to create a new Pages entity.
     *
     */
    public function newAction()
    {
        $entity = new Pages();
        $form   = $this->createCreateForm($entity);

        return $this->render('PSBAdminBundle:Pages:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Pages entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PSBAdminBundle:Pages')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pages entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PSBAdminBundle:Pages:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Pages entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PSBAdminBundle:Pages')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pages entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PSBAdminBundle:Pages:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Pages entity.
    *
    * @param Pages $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Pages $entity)
    {
        $form = $this->createForm(new PagesType(), $entity, array(
            'action' => $this->generateUrl('pages_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('novalidateform' => 'novalidateform', 'class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Pages entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PSBAdminBundle:Pages')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pages entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $redis = $this->container->get('snc_redis.cache');
            $deleted = $redis->flushDB();

            if ($deleted)
            {
                $this->get('session')->getFlashBag()->add('message', 'Page updated! Cache Purged! All pages should now be current.');
            }

            return $this->redirect($this->generateUrl('pages'));
        }

        return $this->render('PSBAdminBundle:Pages:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Pages entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PSBAdminBundle:Pages')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pages entity.');
            }

            $em->remove($entity);
            $em->flush();

            $redis = $this->container->get('snc_redis.cache');
            $deleted = $redis->flushDB();

            if ($deleted)
            {
                $this->get('session')->getFlashBag()->add('message', 'Page Deleted! Cache Purged! All pages should now be current.');
            }
        }

        return $this->redirect($this->generateUrl('pages'));
    }

    /**
     * Creates a form to delete a Pages entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pages_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => "btn-danger")))
            ->getForm()
        ;
    }
}
