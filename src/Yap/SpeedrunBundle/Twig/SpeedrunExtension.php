<?php
namespace Yap\SpeedrunBundle\Twig;

class SpeedrunExtension extends \Twig_Extension
{
    /*public function __construct(SecurityService $speedrunService= null)
    {
        $this->speedrunService = $speedrunService;
    }

    public function formatMsFormController($milliseconds)
    {
       // do something
       $this->speedrunService->formatMs($milliseconds);
    }*/

	
	public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('secToHms', array($this, 'secToHms')),
            new \Twig_SimpleFilter('formatMs', array($this, 'formatMs')),
            new \Twig_SimpleFilter('getHost', array($this, 'getHost')),
            new \Twig_SimpleFilter('getYoutubeId', array($this, 'getYoutubeId')),
            new \Twig_SimpleFilter('getTwitchId', array($this, 'getTwitchId')),
            new \Twig_SimpleFilter('getTwitchChannel', array($this, 'getTwitchChannel')),
        );
    }

    public function secToHms($seconds)
    {
        return gmdate("H:i:s", $seconds);
    }
	
	function formatMs($milliseconds) {
		$seconds = floor($milliseconds / 1000);
		$minutes = floor($seconds / 60);
		$hours = floor($minutes / 60);
		$milliseconds = $milliseconds % 1000;
		$seconds = $seconds % 60;
		$minutes = $minutes % 60;
		
		if($hours == 0) {
			$format = '%02um %02us %03ums';
			$time = sprintf($format, $minutes, $seconds, $milliseconds);
			if($minutes == 0) {
				$format = '%02us %03ums';
				$time = sprintf($format, $seconds, $milliseconds);
				if($seconds == 0) {
					$format = '%03ums';
					$time = sprintf($format, $milliseconds);
				}
			}
		} else {
			$format = '%uh %02um %02us %03ums';
			$time = sprintf($format, $hours, $minutes, $seconds, $milliseconds);
		}
		
		$time = str_replace(' 000ms', '', $time);
		return rtrim($time, '0');
	}
	
	public function getHost($url) {
		if (preg_match('/https?:\/\/([^\/]+)\//i', $url, $matches)) {
			$domain = $matches[1];
			if($domain == "www.youtube.com") {
				return "Youtube";
			} else if($domain == "www.twitch.tv") {
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
		if (preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:twitch\.tv\/)(.*)(?:\/c\/)(.*)/", $url, $matches)) {
			return $matches[2];
		}
	}
	
	public function getTwitchChannel($url) {
		if (preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:twitch\.tv\/)(.*)(?:\/c\/)(.*)/", $url, $matches)) {
			return $matches[1];
		}
	}

    public function getName()
    {
        return 'yap_extension';
    }
}