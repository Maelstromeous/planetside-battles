<?php

namespace PSB\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function indexAction()
	{
		return $this->render('PSBAdminBundle:Default:index.html.twig');
	}

	public function deleteCacheAction()
	{
		$redis = $this->container->get('snc_redis.cache');
		$deleted = $redis->flushDB();

		if ($deleted)
		{
			$this->get('session')->getFlashBag()->add('message', 'Cache deleted! All pages should now be current.');

			return $this->render('PSBAdminBundle:Default:index.html.twig');
		}
	}
}