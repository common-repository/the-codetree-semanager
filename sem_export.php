<?php
require_once('iCalcreator.class.php');
class exports {
	var $eventData = NULL;
	var $urlSnags = array('/',':',' ');
	var $urlReplace = array('%2F','%3A','%20');
	var $gCalCheck = NULL;
	var $iCalCheck = NULL;
	function addToGoogle($cSideOffset,$eventTz) {
		if ($this->gCalCheck == 'no') { return false;}
		if (strlen($this->eventData->sem_description) <= 200) {
			$this->eventData->sem_description = $this->eventData->sem_description;
		}
		else {
			$this->eventData->sem_description = wordwrap($this->eventData->sem_description, 200);
			$this->eventData->sem_description = substr($this->eventData->sem_description, 0, strpos($this->eventData->sem_description, "\n"));
		}
		$ts_start = strtotime($this->eventData->sem_start);
		$ts_end = strtotime($this->eventData->sem_end);
		if (isset($cSideOffset) && !empty($cSideOffset) && $cSideOffset !='') {
			$eventTz = sem_get_target($eventTz, '(GMT ', ')');
			if (substr($eventTz,0,1) != '-' && substr($cSideOffset,0,1) == '-') { $eventTz = '-'.$eventTz;  }
			if (substr($cSideOffset,0,1) == '-') {
				$cSideOffset = $cSideOffset - $eventTz;
			}
			else {
				$cSideOffset = $cSideOffset + $eventTz;
			}
			//echo $cSideOffset;
			$ts_start = $ts_start + ($cSideOffset * 36);
			$ts_end = $ts_end + ($cSideOffset * 36);
		}
		$start = date('Ymd', $ts_start) . "T" . date('His', $ts_start);
		$end = date('Ymd', $ts_end) . "T" . date('His', $ts_end);
		$details = str_replace($this->urlSnags,$this->urlReplace,$this->eventData->sem_description) . '...';
		$this->eventData->sem_url = str_replace($this->urlSnags,$this->urlReplace,$this->eventData->sem_url);
		$this->eventData->sem_url_name = str_replace($this->urlSnags,$this->urlReplace,$this->eventData->sem_url_name);
		$output ="
            <a href=\"http://www.google.com/calendar/event?action=TEMPLATE&text=" . $this->eventData->sem_title . "&dates=$start/$end&details=$details&location=" . str_replace(' ', '%20', $this->eventData->sem_location) . "&trp=true&sprop=" . $this->eventData->sem_url . "&sprop=name:" . $this->eventData->sem_url_name . "\" target=\"_blank\">
            <img id='googleBtn' src=\"http://www.google.com/calendar/images/ext/gc_button6.gif\" border=0>
            </a>";
		return $output;
	}
	function addToIcs($cSideOffset) {
		if ($this->iCalCheck == 'no') { return false; }
		if (!isset($cSideOffset) OR empty($cSideOffset) OR $cSideOffset =='') {
			$cSideOffset = NULL;
		}
		else {
			$cSideOffset = "&semCsOffset=$cSideOffset";
		}
		return("<a id='semIcalLink' href='" . WP_PLUGIN_URL . "/the-codetree-semanager/icsdldr.php?semIcal=" . $this->eventData->sem_id . "$cSideOffset' target='_self'>Download an iCal file</a>");
	}
	function genIcs($cSideOffset) {
		if (strlen($this->eventData->sem_description) <= 300) {
			$this->eventData->sem_description = $this->eventData->sem_description;
		}
		else {
			$this->eventData->sem_description = wordwrap($this->eventData->sem_description, 300);
			$this->eventData->sem_description = substr($this->eventData->sem_description, 0, strpos($this->eventData->sem_description, "\n"));
		}
		$ts_start = strtotime($this->eventData->sem_start);
		$ts_end = strtotime($this->eventData->sem_end);
		if (isset($cSideOffset) && !empty($cSideOffset) && $cSideOffset !='') {
			$eventTz = sem_get_target($this->eventData->sem_tz, '(GMT ', ')');
			if (substr($eventTz,0,1) != '-' && substr($cSideOffset,0,1) == '-') { $eventTz = '-'.$eventTz;  }
			if (substr($cSideOffset,0,1) == '-') {
				$cSideOffset = $cSideOffset - $eventTz;
			}
			else {
				$cSideOffset = $cSideOffset + $eventTz;
			}
			//echo $cSideOffset;
			$ts_start = $ts_start + ($cSideOffset * 36);
			$ts_end = $ts_end + ($cSideOffset * 36);
		}
		$details = $this->eventData->sem_description . '...';
		$v = new vcalendar();
		// create a new calendar instance
		$v->setConfig( 'unique_id', $this->eventData->sem_url );
		$v->setProperty( 'method', 'PUBLISH' );
		$v->setProperty( "x-wr-calname", $this->eventData->sem_prodid );
		$v->setProperty( "X-WR-CALDESC", 'SEManager Calendar' );
		$v->setProperty( "X-WR-TIMEZONE", $this->eventData->sem_tz );
		$vevent = new vevent();
		// create an event calendar component
		$start = array( 'year'=>date('Y', $ts_start), 'month'=>date('m', $ts_start), 'day'=>date('d', $ts_start), 'hour'=>date('H', $ts_start), 'min'=>date('i', $ts_start), 'sec'=>date('s', $ts_start) );
		$vevent->setProperty( 'dtstart', $start );
		$end = array( 'year'=>date('Y', $ts_end), 'month'=>date('m', $ts_end), 'day'=>date('d', $ts_end), 'hour'=>date('H', $ts_end), 'min'=>date('i', $ts_end), 'sec'=>date('s', $ts_end) );
		$vevent->setProperty( 'dtend', $end );
		$vevent->setProperty( 'LOCATION', $this->eventData->sem_location );
		// property name - case independent
		$vevent->setProperty( 'summary', $this->eventData->sem_title );
		$vevent->setProperty( 'description', $details );
		$vevent->setProperty( 'comment', 'powered by MyCodeTree SEManager' );
		$v->setComponent ( $vevent );
		// add event to calendar
		$v->returnCalendar();
		// redirect calendar file to browser
	}
	 
}
$exports = new exports();
?>