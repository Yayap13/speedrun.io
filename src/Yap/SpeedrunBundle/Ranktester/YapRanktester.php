<?php

namespace Yap\SpeedrunBundle\Ranktester;

class YapRanktester
{
	public function isModo($user, $modos) {
		$roles = $user->getRoles();
		$username = $user->getUsername();
		if(in_array('ROLE_SUPER_ADMIN', $roles)) {
			return true;
		}
		if(in_array('ROLE_ADMIN', $roles)) {
			return true;
		}
		if(in_array('ROLE_MODO', $roles)) {
			return true;
		}
		foreach($modos as $modo) {
			if($username == $modo->getUsername()) {
				return true;
			}
		}
	}
}