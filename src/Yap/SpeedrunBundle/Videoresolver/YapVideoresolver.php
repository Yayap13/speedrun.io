<?php

namespace Yap\SpeedrunBundle\Videoresolver;

class YapVideoresolver
{
	public function videoHost($url) {
		if (preg_match('/https?:\/\/([^\/]+)\//i', $url, $matches)) {
			$domain = $matches[1];
			if($domain == "www.youtube.com" OR "www.youtu.be") {
				return "Youtube";
			} elseif($domain == "www.twitch.tv") {
				return "Twitch";
			} else {
				return "???";
			}
		}
	}
	public function getYoutubeId($url) {
		if (preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches)) {
			return $matches[1];
		}
	}
	public function getTwitchId($url) {
		
	}
}