<?php

namespace Yap\SpeedrunBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Yap\SpeedrunBundle\Entity\Game;
use Yap\SpeedrunBundle\Entity\Category;
use Yap\SpeedrunBundle\Entity\Difficulty;
use Yap\SpeedrunBundle\Entity\Level;
use Yap\SpeedrunBundle\Entity\Linker;
use Yap\SpeedrunBundle\Entity\Platform;
use Yap\SpeedrunBundle\Entity\Time;

class RestController extends FOSRestController{
	public function getGameAction(Game $game){
		if(!is_object($game)){
      		throw new NotFoundHttpException();
    	}
		return array('game' => $game);
	}

	public function getCategoryAction(Category $category){
		if(!is_object($category)){
      		throw new NotFoundHttpException();
    	}
		return array('category' => $category);
	}

	public function getDifficultyAction(Difficulty $difficulty){
		if(!is_object($difficulty)){
      		throw new NotFoundHttpException();
    	}
		return array('difficulty' => $difficulty);
	}

	public function getLevelAction(Level $level){
		if(!is_object($level)){
      		throw new NotFoundHttpException();
    	}
		return array('level' => $level);
	}

	public function getLinkerAction(Linker $linker){
		if(!is_object($linker)){
      		throw new NotFoundHttpException();
    	}
		return array('linker' => $linker);
	}

	public function getPlatformAction(Platform $platform){
		if(!is_object($platform)){
      		throw new NotFoundHttpException();
    	}
		return array('platform' => $platform);
	}

	public function getTimeAction(Time $time){
		if(!is_object($time)){
      		throw new NotFoundHttpException();
    	}
		return array('time' => $time);
	}
}