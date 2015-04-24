<?php

namespace Yap\SpeedrunBundle\Timemanager;
use Doctrine\ORM\EntityManager;
use Yap\SpeedrunBundle\Entity\Time;
use Yap\UserBundle\Entity\User;


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

	public function addTime($timeSubmited, $linker, $level, $video, $note, $user) {
		if (($timeSubmited != null) AND ($video != null)) {
        
            $time = new Time();

            $linker = $this->em->getRepository('YapSpeedrunBundle:Linker')
                           ->find($linker);

            $level = $this->em->getRepository('YapSpeedrunBundle:Level')
                          ->find($level);

            $time->setTime($timeSubmited);
            $time->setVideo($video);
            $time->setNote($note);

            $time->setLinker($linker);
            $time->setLevel($level);
            $time->setUser($user);

            $repository = $this->em->getRepository('YapSpeedrunBundle:Time');
            $oldTime = $repository->getOldTime($linker, $level, $user->getId());
            $oldWrTime = $repository->getOldWrTime($linker, $level);

            if ($oldTime != null) {
                if ($oldTime->getTime() > $timeSubmited) {
                    $oldTime->setPb(false);
                    $time->setPb(true);
                    $this->em->persist($oldTime);
                } else {
                    $time->setPb(false);
                }
            } else {
                $time->setPb(true);
            }

            if ($oldWrTime != null) {
                if ($oldWrTime->getTime() > $timeSubmited) {
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

            $this->em->persist($time);
            $this->em->flush();
            return true;
        } else {
        	return false;
        }
	}

	public function addUnverifiedTime($timeSubmited, $linker, $level, $video, $note, $user) {
		if (($timeSubmited != null) AND ($video != null)) {
        
            $time = new Time();

            $linker = $this->em->getRepository('YapSpeedrunBundle:Linker')
                           ->find($linker);

            $level = $this->em->getRepository('YapSpeedrunBundle:Level')
                          ->find($level);

            $time->setTime($timeSubmited);
            $time->setVideo($video);
            $time->setNote($note);

            $time->setLinker($linker);
            $time->setLevel($level);
            $time->setUser($user);

            $time->setWr(false);
            $time->setPb(false);
            $time->setOldWr(false);

            $this->em->persist($time);
            $this->em->flush();
            return true;
        } else {
        	return false;
        }
	}

	public function validateTime($time) {
        
        $timeSubmited = $time->getTime();
        $user = $time->getUser()->getId();
        $linker = $time->getLinker();
        $level = $time->getLevel();

        $repository = $this->em->getRepository('YapSpeedrunBundle:Time');
        $oldTime = $repository->getOldTime($linker, $level, $user);
        $oldWrTime = $repository->getOldWrTime($linker, $level);

        if ($oldTime != null) {
            if ($oldTime->getTime() > $timeSubmited) {
                $oldTime->setPb(false);
                $time->setPb(true);
                $this->em->persist($oldTime);
            } else {
                $time->setPb(false);
            }
        } else {
            $time->setPb(true);
        }

        if ($oldWrTime != null) {
            if ($oldWrTime->getTime() > $timeSubmited) {
                $time->setWr(true);
                $time->setOldWr(true);
                $oldWrTime->setWr(false);
                $this->em->persist($oldWrTime);
            } else {
                $time->setWr(false);
                $time->setOldWr(false);
            }
        } else {
            $time->setWr(true);
            $time->setOldWr(true);
        }

        $time->setVerified(true);

        $this->em->persist($time);
        $this->em->flush();
        return true;
    }
}