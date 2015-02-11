<?php

namespace Yap\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Yap\BlogBundle\Entity\Article;
use Yap\BlogBundle\Form\ArticleType;
use Yap\BlogBundle\Form\ArticleEditType;

class BlogController extends Controller
{
	public function indexAction($page)
	{
		$articles = $this->getDoctrine()
							->getManager()
							->getRepository('YapBlogBundle:Article')
							->getArticles(5, $page);

		return $this->render('YapBlogBundle:Blog:index.html.twig', array( 
				'articles' => $articles,
				'page' => $page,
				'nbPage' => ceil(count($articles)/5)
			));
	}

	public function seeAction(Article $article)
	{
		return $this->render('YapBlogBundle:Blog:see.html.twig', array('article' => $article) );
	}

	public function addAction()
	{
		$article = new Article();

		$form = $this->createForm(new ArticleType, $article);

		$request = $this->get('request');

		if ( $request->getMethod() == 'POST' )
		{
			$form->bind($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($article);
				$em->flush();

				$this->get('session')->getFlashBag()->add('info', 'Article bien enregistré');
				return $this->redirect( $this->generateUrl('yapblog_see',array('slug' => $article->getSlug())) );
			}
		}

		return $this->render('YapBlogBundle:Blog:add.html.twig', array('form' => $form->createView()) );
	}

	public function modifyAction(Article $article)
	{
		$form = $this->createForm(new ArticleEditType(), $article);

		$request = $this->getRequest();

		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($article);
				$em->flush();

				$this->get('session')->getFlashBag()->add('info', 'Article bien modifié');

				return $this->redirect($this->generateUrl('yapblog_see', array('slug' => $article->getSlug())));
			}
		}

		return $this->render('YapBlogBundle:blog:modify.html.twig', array(
				'form' => $form->createView(),
				'article' => $article
			));
	}

	public function deleteAction(Article $article)
	{
		$form = $this->createFormBuilder()->getForm();

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') 
		{
			$form->bind($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->remove($article);
				$em->flush();

				$this->get('session')->getFlashBag()->add('info', 'Article bien supprimé');

				return $this->redirect($this->generateUrl('yapblog_index'));
			}
			
			return $this->redirect( $this->generateUrl('sdzblog_accueil') );
		}


		return $this->render('YapBlogBundle:blog:delete.html.twig', array(
				'article' => $article,
				'form' => $form->createView()
			));
	}
	
	public function getTokenAction()
	{
		return new Response($this->container->get('form.csrf_provider')->generateCsrfToken('authenticate'));
	}
}