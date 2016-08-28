<?php

namespace PSB\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use PSB\AdminBundle\Entity\Events;

class EventsController extends Controller
{
	public function indexAction(Request $request)
	{
		$event = new Events();
		$event->setWorld('19');

		$form = $this->createFormBuilder($event)
			->add('title', 'text', array(
				'label' => 'Title *'
				))
			->add('description', 'textarea', array(
				'required' => false,
				'attr' => array(
					'class' => 'editor'
					)
				))
			->add('startTime', 'text', array(
				'label' => 'Start Time *',
				'attr' => array(
					'class' => 'datetimepicker'
					)
				))
			->add('endTime', 'text', array(
				'label' => 'End Time *',
				'attr' => array(
					'class' => 'datetimepicker'
					)
				))
			->add('type', 'choice', array(
				'label' => 'Match Type *',
				'choices' => array(
					'1' => 'Server Smash',
					'2' => 'PSBL (NOT READY)',
					)
				))
			->add('world', 'choice', array(
				'label' => 'World to Monitor *',
				'choices' => array(
					'19' => 'Jaeger',
					'25' => 'Briggs',
					'1'  => 'Connery',
					'13' => 'Cobalt',
					'17' => 'Emerald',
					'10' => 'Miller'
					)
				))
			->add('zone', 'choice', array(
				'label' => 'Continent to Monitor *',
				'choices' => array(
					'6' => 'Amerish',
					'8' => 'Esamir',
					'4' => 'Hossin',
					'2' => 'Indar',
					)
				))
			->add('server1', 'choice', array(
				'label' => 'Participant Server 1 *',
				'choices' => array(
					'25' => 'Briggs',
					'1'  => 'Connery',
					'13' => 'Cobalt',
					'17' => 'Emerald',
					'10' => 'Miller'
					)
				))
			->add('server1Faction', 'choice', array(
				'label' => 'Server 1 Faction *',
				'choices' => array(
					'1' => 'VS',
					'2' => 'NC',
					'3' => 'TR',
					)
				))
			->add('server2', 'choice', array(
				'label' => 'Participant Server 2 *',
				'choices' => array(
					'25' => 'Briggs',
					'1'  => 'Connery',
					'13' => 'Cobalt',
					'17' => 'Emerald',
					'10' => 'Miller'
					)
				))
			->add('server2Faction', 'choice', array(
				'label' => 'Server 2 Faction *',
				'choices' => array(
					'1' => 'VS',
					'2' => 'NC',
					'3' => 'TR',
					)
				))
			->add('server3', 'choice', array(
				'label' => 'Participant Server 3',
				'required' => false,
				'choices' => array(
					'25' => 'Briggs',
					'1'  => 'Connery',
					'13' => 'Cobalt',
					'17' => 'Emerald',
					'10' => 'Miller'
					)
				))
			->add('server3Faction', 'choice', array(
				'label' => 'Server 3 Faction',
				'required' => false,
				'choices' => array(
					'1' => 'VS',
					'2' => 'NC',
					'3' => 'TR',
					)
				))
			->add('Create Event', 'submit', array(
				'attr' => array(
					'class' => 'btn-primary')
				))
			->getForm();

		$form->handleRequest($request);

		if ($form->isValid()) {

			$data = $form->getData();

			$data->setStartTime( date("U", strtotime($data->getStarttime())) );
			$data->setEndtime( date("U", strtotime($data->getEndtime())) );

			$data->setApproved(1);
			$data->setProcessed(0);
			$data->setMap(0);
			$data->setFinished(0);

			$session = new Session();
			$session->getFlashBag()->add('message', 'Event successfully added!');

			$em = $this->getDoctrine()->getManager();
			$em->persist($data);
			$em->flush();

			return $this->redirectToRoute('admin_websocket');
		}

		return $this->render('PSBAdminBundle:Events:eventsindex.html.twig', array(
			'form' => $form->createView(),
		));
	}
}