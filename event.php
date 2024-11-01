<?php
require_once('validations.php');

class event extends validate {
	var $urlSnags = array('/',':',' ');
	var $urlReplace = array('%2F','%3A','%20');
	function popupWin() {
		echo "
    <form name='addFrm' id='addFrm' margin='style: 0px;' action='" . get_option('siteurl') . "/wp-admin/options-general.php?page=codetree_semanager_options' method='POST'>
    <div id=\"popup\" style=\"display: none;\"></div>
    <div id=\"window\" style=\"display: none;\">  
    <div id=\"popup_content\">
    <div id='semTitle'>SEManager - <em>Add Event</em></div>
    <div id='input'>
    <table>
    <tr>
    <td align='right'>
    <table>
    <tr>
    <td id='txtFields'>
    <label class='lblstyle' for='sem_prodid'><a href='#' title='Insert the site name as the event production ID.' onclick=\"document.getElementById('sem_prodid').value='" . get_bloginfo('name') . "'\">[*]</a>&nbsp;Production ID:</label>
    <input type='text' name='sem_prodid' id='sem_prodid'>
    </td>
    <td id='txtFields'>
    <label class='lblstyle' for='sem_title'><a href='#' title='Insert the site tagline as the event title.' onclick=\"document.getElementById('sem_title').value='" . get_bloginfo('description') . "'\">[*]</a>&nbsp;Title:</label>
    <input type='text' name='sem_title' id='sem_title' maxlength='250'>
    </td> 
    </tr>
    <tr>
    <td id='txtFields'>
    <label class='lblstyle' for='sem_url'><a href='#' title='Insert the site URL as the event URL.' onclick=\"document.getElementById('sem_url').value='" . get_option('siteurl') . "'\">[*]</a>&nbsp;URL:</label>
    <input type='text' name='sem_url' id='sem_url'>
    </td>
    <td id='txtFields'>
    <label class='lblstyle' for='sem_location'>location:</label>
    <input type='text' name='sem_location' id='sem_location' maxlength='250'>
    </td>
    </tr>
    <tr>
    <td id='txtFields'>
    <label class='lblstyle' for='sem_url'>URL Name:</label>
    <input type='text' name='sem_url_name' id='sem_url_name' maxlength='150'>
    </td>
    <td id='txtFields'>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    <tr>
    <td align='right'>
        <table>
        <tr>
        <td id='txtFields'>
        <label class='lblstyle' for='sem_description'>Description:</label>
        </td>
        <td id='txtFields'>
        <textarea name=\"sem_description\" id=\"sem_description\" cols=\"40\" rows=\"5\"></textarea>
        <br /><small style='text-align: right;'>(supports basic HTML tags)</small>
        </td>
        </tr>
        </table>
    </td>
    </tr>
        <tr>
        <td align='right'>
            <table>
            <tr>
            <td id='txtFields'>
            <label class='lblstyle' for='sem_start'>Start Time:</label>
            <input type='text' name='sem_start' id='sem_start' size='12' value='Click Here' onblur=\"if (this.value=='') { this.value='Click Here'; }\" onclick=\"if (this.value=='') { this.value='Click Here'; } else { this.value=''; }\">&nbsp;<select name='sem_start_hr'>" . $this->timeFormatter() . "</select> <select name='sem_start_min'>" . $this->timeFormatter(true) . "</select>
            </td>
            </tr>
            <tr>
            <td id='txtFields'>
            <label class='lblstyle' for='sem_end'>End Time:</label>
            <input type='text' name='sem_end' id='sem_end' size='12' value='Click Here' onblur=\"if (this.value=='') { this.value='Click Here'; }\" onclick=\"if (this.value=='') { this.value='Click Here'; } else { this.value=''; }\">&nbsp;<select name='sem_end_hr'>" . $this->timeFormatter() . "</select> <select name='sem_end_min'>" . $this->timeFormatter(true) . "</select>
            </td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
        <td align='right'>
        <table>
        <tr>
    <td id='txtFields'>
            <label class='lblstyle' for='sem_tz'>TimeZone:</label>
            <select name='sem_tz' id='sem_tz'>
              <option value=\"(GMT -1200) Eniwetok, Kwajalein\">(GMT -12:00) Eniwetok, Kwajalein</option>
              <option value=\"(GMT -1100) Midway Island, Samoa\">(GMT -11:00) Midway Island, Samoa</option>
              <option value=\"(GMT -1000) Hawaii\">(GMT -10:00) Hawaii</option>
              <option value=\"(GMT -900) Alaska\">(GMT -9:00) Alaska</option>
              <option value=\"(GMT -800) Pacific Time (US &amp; Canada)\">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
              <option value=\"(GMT -700) Mountain Time (US &amp; Canada)\">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
              <option value=\"(GMT -600) Central Time (US &amp; Canada), Mexico City\">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
              <option value=\"(GMT -500) Eastern Time (US &amp; Canada), Bogota, Lima\">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
              <option value=\"(GMT -400) Atlantic Time (Canada), Caracas, La Paz\">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
              <option value=\"(GMT -330) Newfoundland\">(GMT -3:30) Newfoundland</option>
              <option value=\"(GMT -300) Brazil, Buenos Aires, Georgetown\">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
              <option value=\"(GMT -200) Mid-Atlantic\">(GMT -2:00) Mid-Atlantic</option>
              <option value=\"(GMT -100 hour) Azores, Cape Verde Islands\">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
              <option value=\"(GMT 0) Western Europe Time, London, Lisbon, Casablanca\">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
              <option value=\"(GMT 100) Brussels, Copenhagen, Madrid, Paris\">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
              <option value=\"(GMT 200) Kaliningrad, South Africa\">(GMT +2:00) Kaliningrad, South Africa</option>
              <option value=\"(GMT 300) Baghdad, Riyadh, Moscow, St. Petersburg\">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
              <option value=\"(GMT 330) Tehran\">(GMT +3:30) Tehran</option>
              <option value=\"(GMT 400) Abu Dhabi, Muscat, Baku, Tbilisi\">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
              <option value=\"(GMT 430) Kabul\">(GMT +4:30) Kabul</option>
              <option value=\"(GMT 500) Ekaterinburg, Islamabad, Karachi, Tashkent\">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
              <option value=\"(GMT 530) Bombay, Calcutta, Madras, New Delhi\">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
              <option value=\"(GMT 545) Kathmandu\">(GMT +5:45) Kathmandu</option>
              <option value=\"(GMT 600) Almaty, Dhaka, Colombo\">(GMT +6:00) Almaty, Dhaka, Colombo</option>
              <option value=\"(GMT 700) Bangkok, Hanoi, Jakarta\">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
              <option value=\"(GMT 800) Beijing, Perth, Singapore, Hong Kong\">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
              <option value=\"(GMT 900) Tokyo, Seoul, Osaka, Sapporo, Yakutsk\">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
              <option value=\"(GMT 930) Adelaide, Darwin\">(GMT +9:30) Adelaide, Darwin</option>
              <option value=\"(GMT 1000) Eastern Australia, Guam, Vladivostok\">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
              <option value=\"(GMT 1100) Magadan, Solomon Islands, New Caledonia\">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
              <option value=\"(GMT 1200) Auckland, Wellington, Fiji, Kamchatka\">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
            </select><br />
            <small>(Suggested TZ: <script>document.write('GMT '+tzo);</script>)</small>
            </td>
        </tr>
        </table>
        </td>
        </tr>
    </table>
    </div>
    <p style='text-align: right; padding: 5px;'><input type=\"submit\" class=\"button-primary\" name=\"save\" value=\"Save\" />&nbsp;<input type=\"button\" class=\"button-primary\" onclick=\"Close_Popup();\" name=\"close\" value=\"Close\" /></p>
    </div>  
    </div> 
    <input type='hidden' name='addCatchFlag' value='yes'>
    </form>";    
	}
	function addEvent($prodid,$start,$end,$time,$title,$location,$url,$urlName,$description,$tz) {

		global $wpdb;
		//PRODID
		$vetttedProdid = $this->vetProducer($prodid);
		//PRODID
		//DATE
		$storeDates = $this->vetDate($start,$end,$time);
		//DATE
		//TITLE
		$vettedTitle = $this->vetTitle($title);
		//TITLE
		//URL
		$vettedUrl = $this->vetUrl($url);
		$vettedUrlName = $this->vetUrlName($urlName);
		//URL
		//LOCATION
		$vettedLocation = $this->vetLocation($location);
		//LOCATION
		//DESCRIPTION
		$vettedDescription = $this->vetDescription($description);
		//DESCRIPTION
		/*** ADD TO DB ***/
		$table_name = $wpdb->prefix . "semanager";
		if (!isset($_GET['lnkmgr'])) {
			$row = $wpdb->get_row("SELECT * FROM $table_name WHERE sem_title='$vettedTitle'");
			if (is_null($row)) {
				$wpdb->query("INSERT INTO $table_name (sem_prodid,sem_tz,sem_title,sem_location,sem_url,sem_url_name,sem_description,sem_start,sem_end) VALUES ('$vetttedProdid','$tz','$vettedTitle','$vettedLocation','$vettedUrl','$vettedUrlName','$vettedDescription','" . $storeDates['start'] ."', '" . $storeDates['end'] . "') ON DUPLICATE KEY UPDATE sem_prodid='$vetttedProdid',sem_tz='$tz',sem_title='$vettedTitle',sem_location='$vettedLocation',sem_url='$vettedUrl',sem_url_name='$vettedUrlName',sem_description='$vettedDescription',sem_start='" . $storeDates['start'] . "',sem_end='" . $storeDates['end'] . "'");
				return true;
			}
			else {
				return false;
			}
		}
		else {
			$wpdb->query("INSERT INTO $table_name (sem_prodid,sem_tz,sem_title,sem_location,sem_url,sem_url_name,sem_description,sem_start,sem_end) VALUES ('$vetttedProdid','$tz','$vettedTitle','$vettedLocation','$vettedUrl','$vettedUrlName','$vettedDescription','" . $storeDates['start'] ."', '" . $storeDates['end'] . "') ON DUPLICATE KEY UPDATE sem_prodid='$vetttedProdid',sem_tz='$tz',sem_title='$vettedTitle',sem_location='$vettedLocation',sem_url='$vettedUrl',sem_url_name='$vettedUrlName',sem_description='$vettedDescription',sem_start='" . $storeDates['start'] . "',sem_end='" . $storeDates['end'] . "'");
			return true;
		}

	}
	function editWin($id) {
		global $wpdb;
		$table_name = $wpdb->prefix . "semanager";
		$row = $wpdb->get_row("SELECT * FROM $table_name WHERE sem_id=$id");
		$start = strtotime($row->sem_start);
		$end = strtotime($row->sem_end);
		$format = get_option('codetree-semanager-hour');
		($format == 12)?$formatBit = 'gA':$formatBit='H';
		if (isset($_POST['addCatchFlag'])) {
			echo "<div id=\"message\" class=\"updated\">The event has been saved!</div>";
		}
		echo "
    <div style='width: 500px;border: 3px solid #BBB;padding:20px;margin:15px;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;'>
    <h2 style='margin-top: 0px;'>SEManager - Edit Event</h2>
    <form name='addFrm' id='addFrm' style='margin: 0px;' action='" . get_option('siteurl') . "/wp-admin/options-general.php?page=codetree_semanager_options&lnkmgr=" . $row->sem_id. "' method='POST'>
    <table>
    <tr>
    <td align='right'>
    <table>
    <tr>
    <td id='txtFields'>
    <label class='lblstyle' for='sem_prodid'><a href='#' title='Insert the site tagline as the event title.' onclick=\"document.getElementById('sem_title').value='" . get_bloginfo('name') . "'\">[*]</a>&nbsp;Production ID:</label>
    <input type='text' name='sem_prodid' id='sem_prodid' value='" . $row->sem_prodid . "'>
    </td>
    <td id='txtFields'>
    <label class='lblstyle' for='sem_title'>Title:</label>
    <input type='text' name='sem_title' id='sem_title' readonly='readonly' maxlength='250' value='" . $row->sem_title . "'>
    </td> 
    </tr>
    <tr>
    <td id='txtFields'>
    <label class='lblstyle' for='sem_url'><a href='#' title='Insert the site URL as the event URL.' onclick=\"document.getElementById('sem_url').value='" . get_option('siteurl') . "'\">[*]</a>&nbsp;URL:</label>
    <input type='text' name='sem_url' id='sem_url' value='" . $row->sem_url . "'>
    </td>
    <td id='txtFields'>
    <label class='lblstyle' for='sem_location'>location:</label>
    <input type='text' name='sem_location' id='sem_location' maxlength='250' value='" . $row->sem_location . "'>
    </td>
    </tr>
    <tr>
    <td id='txtFields'>
    <label class='lblstyle' for='sem_url'>URL Name:</label>
    <input type='text' name='sem_url_name' id='sem_url_name' maxlength='150' value='" . $row->sem_url_name . "'>
    </td>
    <td id='txtFields'>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    <tr>
    <td align='right'>
        <table>
        <tr>
        <td id='txtFields'>
        <label class='lblstyle' for='sem_description'>Description:</label>
        </td>
        <td id='txtFields'>
        <textarea name=\"sem_description\" id=\"sem_description\" cols=\"40\" rows=\"5\">" . $row->sem_description . "</textarea>
        <br /><small style='text-align: right;'>(supports basic HTML tags)</small>
        </td>
        </tr>
        </table>
    </td>
    </tr>
        <tr>
        <td align='right'>
            <table>
            <tr>
            <td id='txtFields'>
            <label class='lblstyle' for='sem_start'>Start Time:</label>
            <input type='text' name='sem_start' id='sem_start' size='12' value='" . date('m/d/Y', $start) . "'>&nbsp;<select name='sem_start_hr'><option value='" . date('H', $start) . "'>" . date($formatBit, $start) . "</option>\n" .  $this->timeFormatter(false,false) . "</select> <select name='sem_start_min'><option value='" . date('i', $start) . "'>" . date('i', $start) . "</option>\n" . $this->timeFormatter(true,false) . "</select>
            </td>
            </tr>
            <tr>
            <td id='txtFields'>
            <label class='lblstyle' for='sem_end'>End Time:</label>
            <input type='text' name='sem_end' id='sem_end' size='12' value='" . date('m/d/Y', $end) . "'>&nbsp;<select name='sem_end_hr'><option value='" . date('H', $end) . "'>" . date($formatBit, $end) . "</option>\n" . $this->timeFormatter(false,false) . "</select> <select name='sem_end_min'><option value='" . date('i', $end) . "'>" . date('i', $end) . "</option>\n" . $this->timeFormatter(true,false) . "</select>
            </td>
            </tr>
            </table>
        </td>
        </tr>
        <tr>
        <td align='right'>
        <table>
        <tr>
    <td id='txtFields'>
            <label class='lblstyle' for='sem_tz'>TimeZone:</label>
            <select name='sem_tz' id='sem_tz'>
          <option value=\"" . $row->sem_tz . "\">" . $row->sem_tz . "</option>
              <option value=\"(GMT -1200) Eniwetok, Kwajalein\">(GMT -12:00) Eniwetok, Kwajalein</option>
              <option value=\"(GMT -1100) Midway Island, Samoa\">(GMT -11:00) Midway Island, Samoa</option>
              <option value=\"(GMT -1000) Hawaii\">(GMT -10:00) Hawaii</option>
              <option value=\"(GMT -900) Alaska\">(GMT -9:00) Alaska</option>
              <option value=\"(GMT -800) Pacific Time (US &amp; Canada)\">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
              <option value=\"(GMT -700) Mountain Time (US &amp; Canada)\">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
              <option value=\"(GMT -600) Central Time (US &amp; Canada), Mexico City\">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
              <option value=\"(GMT -500) Eastern Time (US &amp; Canada), Bogota, Lima\">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
              <option value=\"(GMT -400) Atlantic Time (Canada), Caracas, La Paz\">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
              <option value=\"(GMT -330) Newfoundland\">(GMT -3:30) Newfoundland</option>
              <option value=\"(GMT -300) Brazil, Buenos Aires, Georgetown\">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
              <option value=\"(GMT -200) Mid-Atlantic\">(GMT -2:00) Mid-Atlantic</option>
              <option value=\"(GMT -100 hour) Azores, Cape Verde Islands\">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
              <option value=\"(GMT 0) Western Europe Time, London, Lisbon, Casablanca\">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
              <option value=\"(GMT 100) Brussels, Copenhagen, Madrid, Paris\">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
              <option value=\"(GMT 200) Kaliningrad, South Africa\">(GMT +2:00) Kaliningrad, South Africa</option>
              <option value=\"(GMT 300) Baghdad, Riyadh, Moscow, St. Petersburg\">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
              <option value=\"(GMT 330) Tehran\">(GMT +3:30) Tehran</option>
              <option value=\"(GMT 400) Abu Dhabi, Muscat, Baku, Tbilisi\">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
              <option value=\"(GMT 430) Kabul\">(GMT +4:30) Kabul</option>
              <option value=\"(GMT 500) Ekaterinburg, Islamabad, Karachi, Tashkent\">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
              <option value=\"(GMT 530) Bombay, Calcutta, Madras, New Delhi\">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
              <option value=\"(GMT 545) Kathmandu\">(GMT +5:45) Kathmandu</option>
              <option value=\"(GMT 600) Almaty, Dhaka, Colombo\">(GMT +6:00) Almaty, Dhaka, Colombo</option>
              <option value=\"(GMT 700) Bangkok, Hanoi, Jakarta\">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
              <option value=\"(GMT 800) Beijing, Perth, Singapore, Hong Kong\">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
              <option value=\"(GMT 900) Tokyo, Seoul, Osaka, Sapporo, Yakutsk\">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
              <option value=\"(GMT 930) Adelaide, Darwin\">(GMT +9:30) Adelaide, Darwin</option>
              <option value=\"(GMT 1000) Eastern Australia, Guam, Vladivostok\">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
              <option value=\"(GMT 1100) Magadan, Solomon Islands, New Caledonia\">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
              <option value=\"(GMT 1200) Auckland, Wellington, Fiji, Kamchatka\">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
            </select><br />
            <small>(Suggested TZ: <script>document.write('GMT '+tzo);</script>)</small>
            </td>
        </tr>
        </table>
        </td>
        </tr>
    </table>  
    <p style='text-align: right; padding: 5px;'><input type=\"submit\" class=\"button-primary\" name=\"save\" value=\"Save\" />&nbsp;<input type=\"button\" class=\"button-primary\" onclick=\"parent.location.href='" . get_option('siteurl') . "/wp-admin/options-general.php?page=codetree_semanager_options&sempreviewid=" . $row->sem_id . "'\" name=\"preview\" value=\"Preview\" />&nbsp;<input type=\"button\" class=\"button-primary\" onclick=\"parent.location.href='" . get_option('siteurl') . "/wp-admin/options-general.php?page=codetree_semanager_options'\" name=\"cancel\" value=\"Close\" /></p> 
    <p style='text-align: right;font-size: 10px;margin: 0px'><span style='color: maroon;'>*</span>You must save changes first before previewing them!</p>
    <input type='hidden' name='addCatchFlag' value='yes'>
    </form>
    </div>";    
	}
	function eventQuery($delete = true, $id) {
		global $wpdb;
		$table_name = $wpdb->prefix . "semanager";
		if ($delete) {
			$wpdb->query("DELETE FROM $table_name WHERE sem_id=$id");
		}
		return;
	}
	function timeFormatter($minutes = false, $leads = true) {
		if ($minutes) {
			$cnt = 0;
			while ($cnt <= 60) {
				$ckDigit=NULL;
				if (strlen($cnt) == 1) {
					$ckDigit = '0';
				}
				$result .= "<option value='$ckDigit" . $cnt . "'>$ckDigit" . $cnt . "</option>\n";
				$cnt++;
			}
			if ($leads) {
				$result = "<option value='min'>MIN</option>\n" . $result;
			}
			return $result;
		}
		$format = get_option('codetree-semanager-hour');
		if ($format == 12) {
			$cnt = 0;
			$pmConversion = array(12=>12,13=>1,14=>2,15=>3,16=>4,17=>5,18=>6,19=>7,20=>8,21=>9,22=>10,23=>11);
			while ($cnt <= 24) {
				if ($cnt == 0) {
					$result .= "<option value='24'>12AM</option>\n";
				}
				if ($cnt != 24 && $cnt != 0){
					if (array_key_exists($cnt,$pmConversion)) {
						$result .= "<option value='" . $cnt . "'>" . $pmConversion[$cnt] . "PM</option>\n";
					}
					else {
						$chkdigit = NULL;
						if (strlen($cnt) == 1) {
							$chkdigit = 0;
						}
						$result .= "<option value='$chkdigit" . $cnt . "'>" . $cnt . "AM</option>\n";
					}
				}
				$cnt++;
			}
			if ($leads) {
				$result = "<option value='hr'>HR</option>\n" . $result;
			}
		}
		else {
			$cnt = 0;
			while ($cnt <= 24) {
				if ($cnt != 0) {
					$ckDigit=NULL;
					if (strlen($cnt) == 1) {
						$ckDigit = '0';
					}
					$result .= "<option value='$ckDigit" . $cnt . "'>$ckDigit" . $cnt . "</option>\n";
				}
				$cnt++;
			}
			if ($leads) {
				$result = "<option value='hr'>HR</option>\n" . $result;
			}
		}
		return $result;
	}
	function socialBar($position,$description,$title,$prodid) {
		$stored = get_option('codetree-semanager-socialbar');
		if ($stored == $position OR $stored == 'both') {
			$url = str_replace($this->urlSnags, $this->urlReplace, get_option('siteurl') . $_SERVER['REQUEST_URI']);
			$title = str_replace($this->urlSnags, $this->urlReplace, $title);
			$prodid = str_replace($this->urlSnags, $this->urlReplace, $prodid);
			if (strlen($description) <= 300) {
				$description = $description;
			}
			else {
				$description = wordwrap($this->eventData->sem_description, 300);
				$description = substr($description, 0, strpos($description, "\n"));
			}
			$description = str_replace($this->urlSnags, $this->urlReplace, $description);
			$output = "<strong>Share Event:</strong> <a rel=\"nofollow\"  href=\"http://www.printfriendly.com/print?url=$url\" target='_blank' title=\"Print\"><img src=\"/wp-content/plugins/the-codetree-semanager/css/images/printer.png\" title=\"Print\" alt=\"Print\" style=\"width: 16px; height: 16px; background: transparent url(/wp-content/plugins/the-codetree-semanager/css/images/printer.png) no-repeat; background-position:-343px -37px\" /></a>
            <a rel=\"nofollow\"  href=\"http://twitter.com/home?status=$title-$url\" title=\"Twitter\"><img src=\"/wp-content/plugins/the-codetree-semanager/css/images/twitter.png\" title=\"Twitter\" alt=\"Twitter\" style=\"width: 16px; height: 16px; background: transparent url(/wp-content/plugins/the-codetree-semanager/css/images/twitter.png) no-repeat; background-position:-343px -55px\" /></a>
            <a rel=\"nofollow\"  href=\"http://www.facebook.com/share.php?u=$url\" title=\"Facebook\"><img src=\"/wp-content/plugins/the-codetree-semanager/css/images/facebook.png\" title=\"Facebook\" alt=\"Facebook\" style=\"width: 16px; height: 16px; background: transparent url(/wp-content/plugins/the-codetree-semanager/css/images/facebook.png) no-repeat; background-position:-343px -1px\" /></a>
            <a rel=\"nofollow\"  href=\"http://www.linkedin.com/shareArticle?mini=true&amp;url=$url&amp;title=$title&amp;source=$prodid&amp;summary=$description\" title=\"LinkedIn\"><img src=\"/wp-content/plugins/the-codetree-semanager/css/images/linkedin.png\" title=\"LinkedIn\" alt=\"LinkedIn\" style=\"width: 16px; height: 16px; background: transparent url(/wp-content/plugins/the-codetree-semanager/css/images/linkedin.png) no-repeat; background-position:-1px -37px\" /></a>
            <a rel=\"nofollow\"  href=\"http://digg.com/submit?phase=2&amp;url=$url&amp;title=$title&amp;bodytext=$description\" title=\"Digg\"><img src=\"/wp-content/plugins/the-codetree-semanager/css/images/digg.png\" title=\"Digg\" alt=\"Digg\" style=\"width: 16px; height: 16px; background: transparent url(/wp-content/plugins/the-codetree-semanager/css/images/digg.png) no-repeat; background-position:-235px -1px\" /></a>
            <a rel=\"nofollow\"  href=\"http://delicious.com/post?url=$url&amp;title=$title&amp;notes=$description\" title=\"del.icio.us\"><img src=\"/wp-content/plugins/the-codetree-semanager/css/images/delicious.png\" title=\"del.icio.us\" alt=\"del.icio.us\" style=\"width: 16px; height: 16px; background: transparent url(/wp-content/plugins/the-codetree-semanager/css/images/delicious.png) no-repeat; background-position:-199px -1px\" /></a>
            ";
		}
		return $output;
	}
	function clientTzSelector() {
		$disabled = NULL;
		if (isset($_GET['sempreviewid']) && $_GET['sempreviewid'] !='' && !empty($_GET['sempreviewid'])) {
			$disabled = "disabled='disabled'";
			$pages = substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'?'));
			$selector = "Selector is disabled in preview mode";
		}
		elseif (isset($_GET['semCsOffset']) && $_GET['semCsOffset'] !='' && !empty($_GET['semCsOffset'])) {
			$disabled = "disabled='disabled'";
			$pages = substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'?'));
			$selector = "<a id='semNewZoneLink' href='" . get_option('siteurl') . "$pages' target='_self'>Click Here to select a new time zone (Current: GMT " . $_GET['semCsOffset'] . ")</a>";
		}
		elseif (!isset($_GET['semCsOffset']) && !isset($_GET['sempreviewid'])) {

			$selector = "
        <select id='semClientSelector' $disabled onChange=\"parent.location.href='" . get_option('siteurl') . $_SERVER['REQUEST_URI'] . semUriBit() . "semCsOffset='+this.value\">
          <option value=\"0\">Select a time zone</option>
          <option value=\"-1200\">(GMT -12:00) Eniwetok, Kwajalein</option>
          <option value=\"-1100\">(GMT -11:00) Midway Island, Samoa</option>
          <option value=\"-1000\">(GMT -10:00) Hawaii</option>
          <option value=\"-900\">(GMT -9:00) Alaska</option>
          <option value=\"-800\">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
          <option value=\"-700\">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
          <option value=\"-600\">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
          <option value=\"-500\">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
          <option value=\"-400\">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
          <option value=\"-330\">(GMT -3:30) Newfoundland</option>
          <option value=\"-300\">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
          <option value=\"-200\">(GMT -2:00) Mid-Atlantic</option>
          <option value=\"-100\">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
          <option value=\"0\">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
          <option value=\"100\">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
          <option value=\"200\">(GMT +2:00) Kaliningrad, South Africa</option>
          <option value=\"300\">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
          <option value=\"330\">(GMT +3:30) Tehran</option>
          <option value=\"400\">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
          <option value=\"430\">(GMT +4:30) Kabul</option>
          <option value=\"500\">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
          <option value=\"530\">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
          <option value=\"545\">(GMT +5:45) Kathmandu</option>
          <option value=\"600\">(GMT +6:00) Almaty, Dhaka, Colombo</option>
          <option value=\"700\">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
          <option value=\"800\">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
          <option value=\"900\">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
          <option value=\"930\">(GMT +9:30) Adelaide, Darwin</option>
          <option value=\"1000\">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
          <option value=\"1100\">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
          <option value=\"1200\">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
        </select>
        ";
		}
		return $selector;
	}
}
$event = new event();
?>