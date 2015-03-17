<?php

namespace Yap\SpeedrunBundle\Timemanager;
use Doctrine\ORM\EntityManager;

class YapTimemanager
{
	private $em;
	
	public function __construct(EntityManager $entityManager) {
		$this->em = $entityManager;
	}
	
	public function getTimeSaved($time) {
		$repository = $this->em->getRepository('YapSpeedrunBundle:Time');
        $currentTime = $repository->find($time);
		
		if($currentTime->getWr()) {
			$oldTime = $repository->findBy(
                                    array('linker' => $currentTime->getLinker(), 'level' => $currentTime->getLevel()),
                                    array('time' => 'ASC'),
									'2', //Limit
									'1' //Start
                                );
			$timeSaved = $currentTime->getTime() - $oldTime[0]->getTime();
		} else {
			$oldTime = $repository->findBy(
                                    array('linker' => $currentTime->getLinker(), 'level' => $currentTime->getLevel(), 'wr' => true)
                                );
			$timeSaved = $currentTime->getTime() - $oldTime[0]->getTime();
		}

		return $timeSaved;
	}
}