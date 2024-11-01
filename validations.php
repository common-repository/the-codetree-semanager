<?php
class validate {

	var $hourType = NULL;
	var $dateFormat = NULL;
	var $defaultProducer = NULL;
	var $defaultTitle = NULL;

	function vetDate($start,$end,$time) {
		($this->hourType == 12)?$hrFormat='g:i A':$hrFormat='H:i';
		foreach ($time as $k=>$v) {
			if ($v == 'hr') { $time[$k]=16; }
			if ($v == 'min') { $time[$k]=00; }
		}
		if (empty($start) OR $start == '' OR $start == 'Click Here') {
			$start = time();
		}
		else {
			$start = explode("/", $start);
			$start = mktime($time['sem_start_hr'],$time['sem_start_min'],00,$start[0],$start[1],$start[2]);
		}
		if (empty($end) OR $end == '' OR $end == 'Click Here') {
			$end = time() + 3600;
		}
		else {
			$end = explode("/", $end);
			$end = mktime($time['sem_end_hr'],$time['sem_end_min'],00,$end[0],$end[1],$end[2]);
		}
		if ($start > $end) {
			return array('start'=>date($this->dateFormat . ' ' . $hrFormat,$end),'end'=>date($this->dateFormat . ' ' . $hrFormat,$start));
		}
		else {
			return array('start'=>date($this->dateFormat . ' ' . $hrFormat,$start),'end'=>date($this->dateFormat . ' ' . $hrFormat,$end));
		}
	}
	function vetTitle($title) {
		$title = trim(stripslashes(strip_tags($title)));
		if (empty($title) OR $title == '' OR is_null($title)) {
			$title = $this->defaultTitle;
		}
		return $title;
	}
	function vetLocation($location) {
		$location = trim(stripslashes(strip_tags($location)));
		return $location;
	}
	function vetProducer($prodid) {
		$prodid = trim(stripslashes(strip_tags($prodid)));
		if (empty($prodid) OR $prodid == '' OR is_null($prodid)) {
			$prodid = $this->defaultProducer;
		}
		return $prodid;
	}
	function vetUrl($url) {
		$url = trim(stripslashes(strip_tags($url)));
		return $url;
	}
	function vetUrlName($urlName) {
		$urlName = trim(stripslashes(strip_tags($urlName)));
		return $urlName;
	}
	function vetDescription($description) {
		$description = str_replace("\"", "'", $description);
		$description = addslashes(stripslashes($description));
		return $description;
	}
}
$validate = new validate();
?>