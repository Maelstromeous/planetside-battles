<?php

namespace PSB\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PSB\AdminBundle\Entity\ArticlesAuthor;
use PSB\AdminBundle\Form\ArticlesAuthorType;

/**
 * ArticlesAuthor controller.
 *
 */
class ArticlesAuthorController extends Controller
{

    /**
     * Lists all ArticlesAuthor entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PSBAdminBundle:ArticlesAuthor')->findAll();

        return $this->render('PSBAdminBundle:ArticlesAuthor:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new ArticlesAuthor entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ArticlesAuthor();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('articlesauthor_show', array('id' => $entity->getId())));
        }

        return $this->render('PSBAdminBundle:ArticlesAuthor:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ArticlesAuthor entity.
     *
     * @param ArticlesAuthor $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ArticlesAuthor $entity)
    {
        $form = $this->createForm(new ArticlesAuthorType(), $entity, array(
            'action' => $this->generateUrl('articlesauthor_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ArticlesAuthor entity.
     *
     */
    public function newAction()
    {
        $entity = new ArticlesAuthor();
        $form   = $this->createCreateForm($entity);

        return $this->render('PSBAdminBundle:ArticlesAuthor:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ArticlesAuthor entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PSBAdminBundle:ArticlesAuthor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticlesAuthor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PSBAdminBundle:ArticlesAuthor:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ArticlesAuthor entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PSBAdminBundle:ArticlesAuthor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticlesAuthor entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PSBAdminBundle:ArticlesAuthor:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ArticlesAuthor entity.
    *
    * @param ArticlesAuthor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ArticlesAuthor $entity)
    {
        $form = $this->createForm(new ArticlesAuthorType(), $entity, array(
            'action' => $this->generateUrl('articlesauthor_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ArticlesAuthor entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PSBAdminBundle:ArticlesAuthor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticlesAuthor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('articlesauthor_edit', array('id' => $id)));
        }

        return $this->render('PSBAdminBundle:ArticlesAuthor:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ArticlesAuthor entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PSBAdminBundle:ArticlesAuthor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ArticlesAuthor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('articlesauthor'));
    }

    /**
     * Creates a form to delete a ArticlesAuthor entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('articlesauthor_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
