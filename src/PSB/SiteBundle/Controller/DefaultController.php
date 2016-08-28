<?php
namespace PSB\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('PSBAdminBundle:Matches')
                 ->createQueryBuilder('c')
                 ->where('c.id >= :id')
                 ->setParameter('id', 46);

        $query = $qb->getQuery();
        $query->useResultCache(true, 86400); // Cached 24 hours

        $data['matches'] = $query->getResult();

        $now = date('U');
        $data['twitch'] = false;

        foreach ($data['matches'] as $match => $info) {
            $twitchDeadlineStart = $info->getStarttime() - 600; // 10 mins before start
            $twitchDeadlineEnd = $info->getEndtime() + 600; // 10 mins after end

            if ($now > $twitchDeadlineStart && $now < $twitchDeadlineEnd) {
                $data['twitch'] = true;
            }

            if ($info->getStarttime() < $now) {
                unset($data['matches'][$match]);
            }
        }

        return $this->render('PSBSiteBundle:Site:home.html.twig', array('data' => $data));
    }

    public function pageAction($section = null, $path)
    {
        $em = $this->getDoctrine()->getManager();

        if ($section != null) {
            $qb = $em->getRepository('PSBAdminBundle:Pages')
                ->createQueryBuilder('c')
                ->where('c.path = :path AND c.section = :section')
                ->setParameter('path', $path)
                ->setParameter('section', $section);
        } else {
            $qb = $em->getRepository('PSBAdminBundle:Pages')
                ->createQueryBuilder('c')
                ->where('c.path = :path')
                ->setParameter('path', $path);
        }

        $query = $qb->getQuery();
        $query->useResultCache(true, 86400); // Cached 24 hours

        $page = $query->getResult();

        if ($page) {
            return $this->render('PSBSiteBundle::Site/page.html.twig', array('page' => $page[0]));
        }

        return $this->render('PSBSiteBundle::Site/Common/errornotfound.html.twig');
    }

    public function articleListAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('PSBAdminBundle:Articles')->findAll();

        if ($articles) {
            return $this->render('PSBSiteBundle::Site/articleList.html.twig', array('articles' => $articles));
        }

        return $this->render('PSBSiteBundle::Site/Common/errornotfound.html.twig');
    }

    public function articleAction($path)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('PSBAdminBundle:Articles')->findOneByPath($path);

        $article->getCategory()->getName();
        $article->getAuthor()->getName();
        $article->getMatch()->getId();

        if ($article) {
            return $this->render('PSBSiteBundle::Site/articles.html.twig', array('article' => $article));
        }

        return $this->render('PSBSiteBundle::Site/Common/errornotfound.html.twig');
    }

    public function articleCategoryAction($path)
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('PSBAdminBundle:ArticlesCategory')->findOneByPath($path);

        if ($articles) {
            return $this->render('PSBSiteBundle::Site/articleList.html.twig', array('articles' => $articles));
        }

        return $this->render('PSBSiteBundle::Site/Common/errornotfound.html.twig');
    }

    public function youTubeAction()
    {
        return $this->render('PSBSiteBundle::Site/youtube.html.twig');
    }

    public function mediaWRAction()
    {
        return $this->render('PSBSiteBundle::Site/mediaWR.html.twig');
    }

    public function donateAction()
    {
        return $this->render('PSBSiteBundle::Site/donate.html.twig');
    }
}
