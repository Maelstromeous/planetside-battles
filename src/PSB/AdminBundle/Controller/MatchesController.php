<?php

namespace PSB\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use PSB\AdminBundle\Entity\Matches;
use PSB\AdminBundle\Form\MatchesType;

/**
 * Matches controller.
 *
 */
class MatchesController extends Controller
{

    /**
     * Lists all Matches entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PSBAdminBundle:Matches')->findBy(array(), array('id' => "DESC"));

        return $this->render('PSBAdminBundle:Matches:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Matches entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Matches();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('matches_show', array('id' => $entity->getId())));
        }

        return $this->render('PSBAdminBundle:Matches:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Matches entity.
     *
     * @param Matches $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Matches $entity)
    {
        $form = $this->createForm(new MatchesType(), $entity, array(
            'action' => $this->generateUrl('matches_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Matches entity.
     *
     */
    public function newAction()
    {
        $entity = new Matches();
        $form   = $this->createCreateForm($entity);

        return $this->render('PSBAdminBundle:Matches:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Matches entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PSBAdminBundle:Matches')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Matches entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PSBAdminBundle:Matches:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Matches entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PSBAdminBundle:Matches')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Matches entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PSBAdminBundle:Matches:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Matches entity.
    *
    * @param Matches $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Matches $entity)
    {
        $form = $this->createForm(new MatchesType(), $entity, array(
            'action' => $this->generateUrl('matches_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Matches entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PSBAdminBundle:Matches')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Matches entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('matches_edit', array('id' => $id)));
        }

        return $this->render('PSBAdminBundle:Matches:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Matches entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PSBAdminBundle:Matches')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Matches entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('matches'));
    }

    /**
     * Creates a form to delete a Matches entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('matches_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
