<?php

namespace Yap\SpeedrunBundle\Ranktester;

class YapRanktester
{
	public function isGameModo($user, $modos) {
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

	public function isModo($user) {
		$roles = $user->getRoles();
		if(in_array('ROLE_SUPER_ADMIN', $roles)) {
			return true;
		}
		if(in_array('ROLE_ADMIN', $roles)) {
			return true;
		}
		if(in_array('ROLE_MODO', $roles)) {
			return true;
		}
	}

	public function isAdmin($user) {
		$roles = $user->getRoles();
		if(in_array('ROLE_SUPER_ADMIN', $roles)) {
			return true;
		}
		if(in_array('ROLE_ADMIN', $roles)) {
			return true;
		}
	}

	public function isSuperAdmin($user) {
		$roles = $user->getRoles();
		if(in_array('ROLE_SUPER_ADMIN', $roles)) {
			return true;
		}
	}
}