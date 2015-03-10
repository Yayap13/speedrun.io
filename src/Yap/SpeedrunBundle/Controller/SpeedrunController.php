<?php

namespace Yap\SpeedrunBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;

use Ob\HighchartsBundle\Highcharts\Highchart;
use Zend\Json\Expr;

use Yap\SpeedrunBundle\Entity\Game;
use Yap\SpeedrunBundle\Entity\Category;
use Yap\SpeedrunBundle\Entity\Level;
use Yap\SpeedrunBundle\Entity\Linker;
use Yap\SpeedrunBundle\Entity\Time;

use Yap\SpeedrunBundle\Form\GameType;
use Yap\SpeedrunBundle\Form\CategoryType;
use Yap\SpeedrunBundle\Form\LinkerType;
use Yap\SpeedrunBundle\Form\TimeType;

class SpeedrunController extends Controller
{
    public function indexAction()
    {
        return $this->render('YapSpeedrunBundle:Speedrun:index.html.twig');
    }

    public function howToAction()
    {
        return $this->render('YapSpeedrunBundle:Speedrun:howTo.html.twig');
    }

    public function addGameAction()
    {
        $game = new Game();

        $form = $this->createForm(new GameType, $game);

        $request = $this->get('request');

        if ( $request->getMethod() == 'POST' )
        {
            $form->bind($request);

            if ($form->isValid()) {
                $game->addUser($this->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($game);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Game succefully added!');
                return $this->redirect( $this->generateUrl('yapspeedrun_seegame', array('slug' => $game->getSlug())) );
            }
        }

        return $this->render('YapSpeedrunBundle:Speedrun:addGame.html.twig', array('form' => $form->createView()) );
    }

    public function editGameAction(Game $game)
    {
        $ranktester = $this->container->get('yap_speedrun.ranktester');
        
        if ($ranktester->isModo($this->getUser(), $game->getUsers()) == false) {
          throw new \Exception('You aren\'t allowed to access this page!');
        }

        $form = $this->createForm(new GameType(), $game);

        $request = $this->getRequest();

        $originalLevels = new ArrayCollection();
        foreach ($game->getLevels() as $level) {
            $originalLevels->add($level);
        }

        $originalDifficulties = new ArrayCollection();
        foreach ($game->getDifficulties() as $difficulty) {
            $originalDifficulties->add($difficulty);
        }

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                foreach ($originalLevels as $level) {
                    if ($game->getLevels()->contains($level) == false) {
                        $em->remove($level);
                    }
                }

                foreach ($originalDifficulties as $difficulty) {
                    if ($game->getDifficulties()->contains($difficulty) == false) {
                        $em->remove($difficulty);
                    }
                }

                $em->persist($game);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Game succefully edited');

                return $this->redirect( $this->generateUrl('yapspeedrun_seegame', array('slug' => $game->getSlug())) );
            }
        }

        return $this->render('YapSpeedrunBundle:Speedrun:editGame.html.twig', array(
                'form' => $form->createView(),
                'game' => $game
            ));
    }

    public function seeGameAction(Game $game)
    {
        if ($game->getVisible() == false) {
            return $this->redirect( $this->generateUrl('yapspeedrun_validategame', array('slug' => $game->getSlug())));
        }
        return $this->render('YapSpeedrunBundle:Speedrun:seeGame.html.twig', array('game' => $game));
    }

    public function seeLevelAction(Game $game, $levelSlug)
    {
        if ($game->getVisible() == false) {
            return $this->redirect( $this->generateUrl('yapspeedrun_validategame', array('slug' => $game->getSlug())));
        }
        $repository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('YapSpeedrunBundle:Level');
        $level = $repository->findBy(
                                    array('slug' => $levelSlug)
                                );

        $repository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('YapSpeedrunBundle:Time');
        $times = $repository->findBy(
                                    array('level' => $level[0]->getId()),
                                    array('time' => 'ASC'),
                                    '20'//limit
                                );

        return $this->render('YapSpeedrunBundle:Speedrun:seeLevel.html.twig', array('game' => $game, 'level' => $level[0], 'times' => $times));
    }

    public function latestTimesAction()
    {
        $repository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('YapSpeedrunBundle:Time');
        $times = $repository->findBy(
                                    array(),
                                    array('date' => 'DESC'),
                                    '20'//limit
                                );
        return $this->render('YapSpeedrunBundle:Speedrun:latestTimes.html.twig', array('times' => $times));
    }

    public function listGamesAction()
    {
        $repository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('YapSpeedrunBundle:Game');
        $listGames = $repository->findBy(
                                    array('visible' => true),
                                    array('name' => 'ASC')
                                );

        return $this->render('YapSpeedrunBundle:Speedrun:listGames.html.twig', array('listGames' => $listGames));
    }

    public function watchAction(Game $game, $video)
    {
        $repository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('YapSpeedrunBundle:Time');
        $time = $repository->find($video);
        
        $timeManager = $this->container->get('yap_speedrun.timemanager');
        $timeSaved = $timeManager->getTimeSaved($video);

        return $this->render('YapSpeedrunBundle:Speedrun:watch.html.twig', array('game' => $game, 'time' => $time, 'timeSaved' => $timeSaved));
    }

    public function validateGameAction(Game $game)
    {
        if ($game->getVisible() == true) {
            return $this->redirect( $this->generateUrl('yapspeedrun_seegame', array('slug' => $game->getSlug())));
        }

        $repository = $this->getDoctrine()->getRepository('YapSpeedrunBundle:Rating');
        $rating = $repository->isGameValid($game->getId());

        //Need to show the page once to create rating object, so with this no error the first time!
        if (is_object($rating)) {
            if (($rating->getNumVotes() > 9) AND ($rating->getRate() > 2.4)) {
                $this->get('session')->getFlashBag()->add('info', 'This game is valid and now accessible.');
                
                $game->setVisible(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($game);
                $em->flush();
                
                return $this->redirect( $this->generateUrl('yapspeedrun_seegame', array('slug' => $game->getSlug())));
            }
        }
        $this->get('session')->getFlashBag()->add('info', 'This game isn\'t validated yet.');
        return $this->render('YapSpeedrunBundle:Speedrun:validateGame.html.twig', array('game' => $game));
    }

    public function addModoAction(Game $game, $user)
    {
        $ranktester = $this->container->get('yap_speedrun.ranktester');
        
        if ($ranktester->isModo($this->getUser(), $game->getUsers()) == false) {
          throw new \Exception('You aren\'t allowed to access this page!');
        }

        if(isset($user)) {
            $userManager = $this->container->get('fos_user.user_manager');
            $user = $userManager->findUserByUsername($user);
            $game->addUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush();
        }
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        return $this->render('YapSpeedrunBundle:Speedrun:addModo.html.twig', array('game' => $game, 'users' => $users));
    }

    public function validateListGamesAction()
    {
        $repository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('YapSpeedrunBundle:Game');
        $listGames = $repository->findBy(
                                    array('visible' => false)
                                );

        return $this->render('YapSpeedrunBundle:Speedrun:listGames.html.twig', array('listGames' => $listGames));
    }

    public function addCategoryAction()
    {
        $category = new Category();

        $form = $this->createForm(new CategoryType, $category);

        $request = $this->get('request');

        if ( $request->getMethod() == 'POST' )
        {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Category succefully added!');
                return $this->redirect( $this->generateUrl('yapspeedrun_index'));
            }
        }

        return $this->render('YapSpeedrunBundle:Speedrun:addCategory.html.twig', array('form' => $form->createView()) );
    }

    public function submitTimeAction(Game $game)
    {
        return $this->render('YapSpeedrunBundle:Speedrun:submitTime.html.twig', array('game' => $game));
    }

    public function createLinkerAction(Game $game)
    {
        $ranktester = $this->container->get('yap_speedrun.ranktester');
        
        if ($ranktester->isModo($this->getUser(), $game->getUsers()) == false) {
          throw new \Exception('You aren\'t allowed to access this page!');
        }
        
        $linker = new Linker();

        $linker->setGame($game);
        
        $form = $this->createForm(new LinkerType(), $linker);
        
        $request = $this->get('request');

        if ( $request->getMethod() == 'POST' )
        {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                /*$linkerExist = $this->getDoctrine()->getManager()->getRepository('YapSpeedrunBundle:Linker')->linkerExist($linker);

                if($linkerExist == null) {
                    throw $this->createNotFoundException(
                        'This configuration already exist!'
                    );
                }*/
                $em->persist($linker);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Run type succefully created!');
                return $this->redirect( $this->generateUrl('yapspeedrun_seegame', array('slug' => $game->getSlug())));
            }
        }

        return $this->render('YapSpeedrunBundle:Speedrun:createLinker.html.twig', array('form' => $form->createView(), 'game' => $game));
    }


    public function getByGameIdAction()
    {
        $this->em = $this->get('doctrine')->getEntityManager();
        $this->repository = $this->em->getRepository('YapSpeedrunBundle:Difficulty');

        $gameId = $this->get('request')->query->get('data');
         
        $difficulties = $this->repository->findByGame($gameId);
         
        $html = '';
        foreach($difficulties as $difficulty)
        {
            $html = $html . sprintf("<option value=\"%d\">%s</option>",$difficulty->getId(), $difficulty->getName());
        }
         
        return new Response($html);
    }

    public function addTimeAction()
    {
        $request = $this->get('request');
        
        if (($request->request->get('time') != null) AND ($request->request->get('video') != null)) {
        
            $time = new Time();

            $linker = $this->getDoctrine()
                           ->getRepository('YapSpeedrunBundle:Linker')
                           ->find($request->request->get('linker'));

            $level = $this->getDoctrine()
                          ->getRepository('YapSpeedrunBundle:Level')
                          ->find($request->request->get('level'));

            $time->setTime($request->request->get('time'));
            $time->setVideo($request->request->get('video'));
            $time->setNote($request->request->get('note'));

            $time->setLinker($linker);
            $time->setLevel($level);
            $time->setUser($this->getUser());

            $repository = $this->getDoctrine()->getRepository('YapSpeedrunBundle:Time');
            $oldTime = $repository->getOldTime($request->request->get('linker'), $request->request->get('level'), $this->getUser()->getId());
            $oldWrTime = $repository->getOldWrTime($request->request->get('linker'), $request->request->get('level'));

            $em = $this->getDoctrine()->getManager();

            if ($oldTime != null) {
                if ($oldTime->getTime() > $request->request->get('time')) {
                    $oldTime->setPb(false);
                    $time->setPb(true);
                    $em->persist($oldTime);
                } else {
                    $time->setPb(false);
                }
            } else {
                $time->setPb(true);
            }

            if ($oldWrTime != null) {
                if ($oldWrTime->getTime() > $request->request->get('time')) {
                    $time->setWr(true);
                    $time->setOldWr(true);
                    $oldWrTime->setWr(false);
                } else {
                    $time->setWr(false);
                    $time->setOldWr(false);
                }
            } else {
                $time->setWr(true);
                $time->setOldWr(true);
            }

            $em->persist($time);
            $em->flush();

            $html = '200';
            return new Response($html);
        }

        $html ='empty';
        return new Response($html);
    }

    public function levelGraphAction(Game $game, $linker, $level)
    {
        return $this->render('YapSpeedrunBundle:Speedrun:levelGraph.html.twig', array('game' => $game) );
    }

    public function chartAction($linker, $level)
    {
        $repository = $this->getDoctrine()->getRepository('YapSpeedrunBundle:Time');
        //$bestTimes = $repository->getLevelTime($linker, $level);
        $bestTimes = $repository->getLevelOldWrTime($linker, $level);
        //var_dump($bestTimes);
        
        //var_dump($bestTimes[0]->getUser()->getUsername());
        //var_dump(date('H:i:s', mktime(0, 0, $bestTimes[0]->getTime())));

        $timeArray = array();
        $dateArray = array();

        foreach ($bestTimes as $time) {
            $timeArray[] = array(
                                "y" => $time->getTime(),
                                "author" => $time->getUser()->getUsername(),
                                "video" => $time->getVideo(),
                            ); //convert to ms
            $dateArray[] = array($time->getDate()->format('d M Y'));
        }

        $divName = "chart-$linker-$level";


        $chart = new Highchart();
        $chart->chart->renderTo($divName);
        $chart->chart->type('line');
        $chart->series(array(
            array(
                "name" => $bestTimes[0]->getLevel()->getName(),
                "data" => $timeArray,
                "color" => '#3945ED',
            ),
        ));


        // Header
        $chart->title->text('Records for the level: '.$bestTimes[0]->getLevel()->getName());
        //$chart->subtitle->text('');

        // X-Axis
        $chart->xAxis->categories($dateArray);

        // Y-Axis

        $yData = array(
            'labels' => array(
                'rotation' => '30',
            ),
            'title' => array(
                'text'  => 'Run time'
            ),
            'type' => 'datetime',
            'dateTimeLabelFormats' => array(
                'millisecond' => '%H:%M:%S',
                'second' => '%H:%M:%S',
                'minute' => '%H:%M:%S',
                'hour' => '%H:%M:%S',
                'day' => '%H:%M:%S',
                'week' => '%H:%M:%S',
                'month' => '%H:%M:%S',
                'year' => '%H:%M:%S',
            ),
        );

        $chart->yAxis($yData);

        //return <span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.y}</b><br/>;
        //Highcharts.dateFormat('%Y-%B-%d %H:%M:%S.',  this.x)

        $formatter = new Expr('function () {
                         return "<span style=\"color:" + this.series.color + "\">\u25CF</span> <a style=\"color:#3945ED;\" href=\"http://"+ this.point.video +"\">"+ Highcharts.dateFormat("%Hh%Mm%Ss%Lms", this.y) +"</a> set by</br><a style=\"color:#3945ED;\" href=\"/profile/"+ this.point.author +"\">"+ this.point.author +"</a> on "+ this.x;
                     }');

        $chart->tooltip->formatter($formatter);
        $chart->tooltip->useHTML(true);

        //var_dump($chart);

        return $this->render('YapSpeedrunBundle:Speedrun:chart.html.twig', array(
            'chart' => $chart,
            'divName' => $divName
        ));
    }

    public function CatWrAction($linker)
    {
        $repository = $this->getDoctrine()->getRepository('YapSpeedrunBundle:Time');
        $bestTimes = $repository->getCatWr($linker);

        return $this->render('YapSpeedrunBundle:Speedrun:table.html.twig', array('bestTimes' => $bestTimes, 'linker' => $linker) );
    }

    public function seeProfileAction($username)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);

        return $this->render('YapSpeedrunBundle:Speedrun:seeProfile.html.twig', array('user' => $user));
    }
}

/* To Do
linkerExist in createLinkerAction() pour proteger l'evoi du meme modele


*/