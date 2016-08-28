<?php

namespace PSB\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WebsocketController extends Controller
{
	public function indexAction()
	{
		$data["events"] = $this->getWebsocketStatus();

		return $this->render('PSBAdminBundle:Websocket:websocketstatus.html.twig', $data);
	}

	public function getWebsocketStatus()
	{
		$em = $this->getDoctrine()->getManager();

		$result = $em->getRepository('PSBAdminBundle:Events')->findBy(array(), array('startTime' => "DESC"));

		foreach($result as $match)
		{
			if ($match->getMatch() != null) {
				try {
					$match->getMatch()->getStatus(); // Init the match data so we can use it
					$match->getMatch()->getServersmashdata();
					$match->getMatch()->getInstance();
				} catch(Exception $e) {

				}
			}
		}

		return $result;
	}
}