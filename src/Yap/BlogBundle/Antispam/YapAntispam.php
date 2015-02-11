<?php
namespace Yap\BlogBundle\Antispam;
class YapAntispam
{
	public function isSpam($text)
	{
		return ($this->countLinks($text) + $this->countMails($text)) >= 3;
	}

	private function countLinks($text)
	{
		preg_match_all('#(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?#i', $text, $matches);
		return count($matches[0]);
	}

	private function countMails($text)
	{
		preg_match_all('#[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}#i', $text, $matches);
		return count($matches[0]);
	}
}