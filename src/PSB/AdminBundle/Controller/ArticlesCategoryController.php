<?php

namespace PSB\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PSB\AdminBundle\Entity\ArticlesCategory;
use PSB\AdminBundle\Form\ArticlesCategoryType;

/**
 * ArticlesCategory controller.
 *
 */
class ArticlesCategoryController extends Controller
{

    /**
     * Lists all ArticlesCategory entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PSBAdminBundle:ArticlesCategory')->findAll();

        return $this->render('PSBAdminBundle:ArticlesCategory:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new ArticlesCategory entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ArticlesCategory();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('articlescategory_show', array('id' => $entity->getId())));
        }

        return $this->render('PSBAdminBundle:ArticlesCategory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ArticlesCategory entity.
     *
     * @param ArticlesCategory $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ArticlesCategory $entity)
    {
        $form = $this->createForm(new ArticlesCategoryType(), $entity, array(
            'action' => $this->generateUrl('articlescategory_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ArticlesCategory entity.
     *
     */
    public function newAction()
    {
        $entity = new ArticlesCategory();
        $form   = $this->createCreateForm($entity);

        return $this->render('PSBAdminBundle:ArticlesCategory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ArticlesCategory entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PSBAdminBundle:ArticlesCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticlesCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PSBAdminBundle:ArticlesCategory:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ArticlesCategory entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PSBAdminBundle:ArticlesCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticlesCategory entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PSBAdminBundle:ArticlesCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ArticlesCategory entity.
    *
    * @param ArticlesCategory $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ArticlesCategory $entity)
    {
        $form = $this->createForm(new ArticlesCategoryType(), $entity, array(
            'action' => $this->generateUrl('articlescategory_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ArticlesCategory entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PSBAdminBundle:ArticlesCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticlesCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('articlescategory_edit', array('id' => $id)));
        }

        return $this->render('PSBAdminBundle:ArticlesCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ArticlesCategory entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PSBAdminBundle:ArticlesCategory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ArticlesCategory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('articlescategory'));
    }

    /**
     * Creates a form to delete a ArticlesCategory entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('articlescategory_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
