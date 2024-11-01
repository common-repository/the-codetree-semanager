<?php
/*
Plugin Name: CodeTree Seminar Event Manager
Version: 2.5
Plugin URI: http://www.mycodetree.com
Donate link: http://mycodetree.com/donations/
Description: Displays seminar event information with an automatic feature to add to Google calendars and download an ICS file 
Author: Mycodetree
Author URI: http://www.mycodetree.com/

Copyright 2011 mycodetree.com. (email: support@mycodetree.com)

*/

if (isset($_POST['registrationkey']) && $_POST['registrationkey'] !='' OR isset($_POST['codetree-semanager-registrationkey'])) {
    if (isset($_POST['codetree-semanager-registrationkey'])){
        update_option('codetree-semanager-registrationkey', trim($_POST['codetree-semanager-registrationkey']));
        $installedregistration = trim($_POST['codetree-semanager-registrationkey']);
    }
    else {
        update_option('codetree-semanager-registrationkey', trim($_POST['registrationkey']));
        $installedregistration = trim($_POST['registrationkey']);
    }
    $installedregistration = base64_decode($installedregistration);
}

wp_enqueue_style("SEManager_event_style", WP_PLUGIN_URL . '/the-codetree-semanager/css/themes/' . get_option('codetree-semanager-style') . '/sem_style.css',false,'1.1','all');
require_once('event.php');
$event->defaultProducer = get_bloginfo('name');
$event->hourType = get_option('codetree-semanager-hour');
$event->dateFormat = get_option('codetree-semanager-date');
$event->defaultTitle = get_bloginfo('description');
register_activation_hook(__FILE__, 'makeDefaultOptions');
register_deactivation_hook(__FILE__, 'semCleanUp');
$checkDigit = $_GET['page'];
if ($checkDigit == 'codetree_semanager_options') {
    add_action('wp_print_scripts','settingsPopupScript');
    add_action('admin_head', 'settingsPopup');
}

if ($_POST['addCatchFlag'] == 'yes') {
    $time = array('sem_start_hr'=>$_POST['sem_start_hr'],'sem_start_min'=>$_POST['sem_start_min'],'sem_end_hr'=>$_POST['sem_end_hr'],'sem_end_min'=>$_POST['sem_end_min']);
    $event->hourFormat = get_option('codetree-semanager-hour');
    if ($event->addEvent($_POST['sem_prodid'],$_POST['sem_start'],$_POST['sem_end'],$time,$_POST['sem_title'],$_POST['sem_location'],$_POST['sem_url'],$_POST['sem_url_name'],$_POST['sem_description'],$_POST['sem_tz'])) {
        $_POST['saveCheckBit'] = true;
    }
    else {
        $_POST['saveCheckBit'] = false;
    }
}
if (isset($_GET['semrmid']) && !empty($_GET['semrmid'])) {
    $event->eventQuery(true, trim(stripslashes($_GET['semrmid'])));
}

function sem_getPhpVersion($supportedVersion) {
    (function_exists('phpversion'))?$phpVer = explode('.', floatval(phpversion())):$phpVer=false;
    (is_array($phpVer) && $phpVer[0] >= $supportedVersion)?$phpVer=true:$phpVer=false;
    return $phpVer;
}
function semanager_set_plugin_meta($links, $file) {
    $plugin = plugin_basename(__FILE__);
    // create link
    if ($file == $plugin) {
        return array_merge( $links, array(             
            '<a href="http://mycodetree.com/forum/viewforum.php?f=11" target="_blank" title="Support Forum">' . __('Support Forum') . '</a>' 
        ));
    }
    return $links;
}
add_filter( 'plugin_row_meta', 'semanager_set_plugin_meta', 10, 2 );
add_filter ( 'the_content', 'callTagReplace' );                             
add_action('admin_menu', 'codetree_semanager');
add_filter( 'plugin_action_links', 'codetree_semanager_add_action_link', 10, 2 );
function codetree_semanager() {
    if (isset($_GET['lnkmgr']) && !empty($_GET['lnkmgr'])) {
        add_options_page('Codetree SEManager Options', 'Codetree SEManager', 'administrator', 'codetree_semanager_options', 'codetree_semanager_edit');
    } 
    elseif (isset($_GET['sempreviewid']) && !empty($_GET['sempreviewid'])) {
        add_options_page('Codetree SEManager Options', 'Codetree SEManager', 'administrator', 'codetree_semanager_options', 'semFormLoader');
    }
    elseif (!isset($_GET['sempreviewid']) && !isset($_GET['lnkmgr'])) {
        add_options_page('Codetree SEManager Options', 'Codetree SEManager', 'administrator', 'codetree_semanager_options', 'codetree_semanager_options');
    }  
}  

function codetree_semanager_table_maker() {
  global $wpdb;
  $table_name = $wpdb->prefix . "semanager";
  $wpdb->query("CREATE TABLE IF NOT EXISTS " . $table_name . " (sem_id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,sem_prodid VARCHAR(250),sem_tz VARCHAR(200),sem_title VARCHAR(250) UNIQUE,sem_location VARCHAR(254),sem_url LONGTEXT,sem_url_name VARCHAR(150),sem_description LONGTEXT,sem_start VARCHAR(200),sem_end VARCHAR(200))");
}
function codetree_semanager_add_action_link( $links, $file ) {
    static $this_plugin;
     if( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
    if ( $file == $this_plugin ) {
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=codetree_semanager_options' ) . '">' . __('Settings') . '</a>';
        array_unshift( $links, $settings_link ); // before other links
    }
    return $links;
}

function sem_postbox($id, $title, $content) {
    ?>
<div id="<?php echo $id; ?>" class="postbox"> 
        <div class="handlediv" title="Click to toggle"><br /></div>
        <h3 class="hndle"><span><?php echo $title; ?></span></h3>
        <div class="inside"> 
            <?php echo $content; ?> 
        </div>
    </div>
    <?php
}

function sem_form_table($rows) {
    $content = '<table class="form-table" width="100%">';
    foreach ($rows as $row) {
        $content .= '<tr><th valign="top" scope="row" style="width:50%">';
        if (isset($row['id']) && $row['id'] != '')
            $content .= '<label for="'.$row['id'].'" style="font-weight:bold;">'.$row['label'].':</label>';
        else
            $content .= $row['label'];
        if (isset($row['desc']) && $row['desc'] != '')
            $content .= '<br/><small>'.$row['desc'].'</small>';
        $content .= '</th><td valign="top">';
        $content .= $row['content'];
        $content .= '</td></tr>'; 
    }
    $content .= '</table>';
    return $content;
}
function codetree_sem_events() {
    global $wpdb;
    $table_name = $wpdb->prefix . "semanager";
    $result = "<table width='100%' cellpadding='3' cellspacing='3' style='padding: 5px; font-size: 10px;'>";
    $result .= "<th align='left' valign='top' width='20%'>Prod. ID</th><th align='left' valign='top' width='20%'>Title</th><th align='left' valign='top'>Start Time</th><th align='left' valign='top'>End Time</th><th align='left' valign='top'>Time Zone</th><th align='left' valign='top' width='10%'>Options</th><th align='left' valign='top'>Call Tag</th><tbody>";
    if ($sql = mysql_query("SELECT * FROM " . $table_name)) {
        while ($row = mysql_fetch_array($sql)) {
            $result .= "<tr style='background-color: #DDD;'><td align='center' valign='middle' style='font-size: 10px;'>" . $row['sem_prodid'] . "</td><td align='center' valign='middle' style='font-size: 10px;'>" . $row['sem_title'] . "</td><td align='center' valign='middle' style='font-size: 10px;'>" . $row['sem_start'] . "</td><td align='center' valign='middle' style='font-size: 10px;'>" . $row['sem_end'] . "</td><td align='center' valign='middle' style='font-size: 10px;'>" . $row['sem_tz'] . "</td><td align='center' valign='middle' style='font-size: 10px;'><a href='#' onclick=\"if(confirm('Are you sure, this can\'t be undone!')) {parent.location='" . get_option('siteurl') . "/wp-admin/options-general.php?page=codetree_semanager_options&semrmid=" . $row['sem_id'] . "';}\" title='Delete this event' target='_self'>[X]</a>&nbsp;<a href='" . get_option('siteurl') . "/wp-admin/options-general.php?page=codetree_semanager_options&lnkmgr=" . $row['sem_id'] . "' title='Click to edit this event' target='_self'>[E]</a>&nbsp;<a href='" . get_option('siteurl') . "/wp-admin/options-general.php?page=codetree_semanager_options&sempreviewid=" . $row['sem_id'] . "' target='_self' title='Click to preview this event'>[P]</a></td><td align='center' valign='middle' style='font-size: 10px;'>[SEManager_sem_id='" . $row['sem_id'] . "']</td></tr>";
        }        
    }
    $result .= "</tbody></table>";
    return $result;    
}

function sem_get_target($string, $start, $end){ 
    $string = " ".$string; 
    $ini = strpos($string,$start); 
    if ($ini == 0) return ""; 
    $ini += strlen($start); 
    $len = strpos($string,$end,$ini) - $ini; 
    return substr($string,$ini,$len); 
} 

function settingsPopupScript() {
    wp_enqueue_script('sem_jquery',WP_PLUGIN_URL . '/the-codetree-semanager/jquery-1.4.2.min.js',array(),'1.4.2' );
    if (isset($_POST['codetree-semanager-comp'])) {
        $compFlag = trim($_POST['codetree-semanager-comp']);
    }
    else {
        $compFlag = get_option('codetree-semanager-comp');
    }
    if ($compFlag == 'no') {    
        wp_enqueue_script('sem_jquery_ui',WP_PLUGIN_URL . '/the-codetree-semanager/jquery-ui-1.8.4.custom.min.js',array('sem_jquery'),'1.8.4' );
    }
}
function settingsPopup() {
    echo "
    <link rel='stylesheet' id='text-css'  href='" . get_option('siteurl') . "/wp-content/plugins/the-codetree-semanager/css/codetree-sem-popup.css' type='text/css' media='screen' />
    <link rel='stylesheet' id='text-css'  href='" . get_option('siteurl') . "/wp-content/plugins/the-codetree-semanager/css/jquery-ui-1.8.4.custom.css' type='text/css' media='screen' />
    <script type='text/javascript'>
    function Show_Popup(action, userid) {
    $('#popup').fadeIn('fast');
    $('#window').fadeIn('fast');
    }
    function Close_Popup() {
    $('#popup').fadeOut('fast');
    $('#window').fadeOut('fast');
    }
    $(function() {
        $(\"#sem_start\").datepicker();
    });
    $(function() {
        $(\"#sem_end\").datepicker();
    });
    var tzo=(new Date().getTimezoneOffset()/60)*(-1);              
    </script>";
}
function callTagReplace($content) {
  $testBit = sem_get_target($content,"[SEManager_sem_id='","']");  
  if (!empty($testBit) && $testBit !='') {
      $content = str_replace("[SEManager_sem_id='" . $testBit . "']", semFormLoader($testBit), $content);
  }
  return $content;
}
function semUriBit() {
    if (is_null($_GET) OR count($_GET) <= 0) {return '?';}else {return '&';} 
}
function semFormLoader($semid) {
    if (isset($_GET['sempreviewid']) && !empty($_GET['sempreviewid'])) {
        $semid = trim(stripslashes($_GET['sempreviewid'])); 
    }
    global $wpdb;
    $event = new event();
    require_once('sem_export.php');
    $table_name = $wpdb->prefix . "semanager";
    if (!$row = $wpdb->get_row("SELECT * FROM $table_name WHERE sem_id=$semid")) {
        return "<center><span style='border: 1px solid black; padding: 3px; font-weight: bold;'>SEManager: <span style='color: maroon;'>[SEManager_sem_id='$semid']</span> is not a valid event call tag.</span></center>";
    }
    $exports->eventData = $row;
    $exports->gCalCheck = get_option('codetree-semanager-googlecal');
    $exports->iCalCheck = get_option('codetree-semanager-ical');
    $styleHeader = file_get_contents(ABSPATH . '/wp-content/plugins/the-codetree-semanager/css/themes/' . get_option('codetree-semanager-style') . '/sem_style.css');
    $templateHooks = array(
    "<--PRODID-->"=>$row->sem_prodid,
    "<--TITLE-->"=>$row->sem_title,
    "<--TZ-->"=>$row->sem_tz,
    "<--LOCATION-->"=>$row->sem_location,
    "<--URL-->"=>$row->sem_url,
    "<--URL_NAME-->"=>$row->sem_url_name,
    "<--DESCRIPTION-->"=>$row->sem_description,
    "<--START-->"=>$row->sem_start,
    "<--END-->"=>$row->sem_end,
    "<--GOOGLE-->"=>$exports->addToGoogle($_GET['semCsOffset'],$row->sem_tz),
    "<--ICS-->"=>$exports->addToIcs($_GET['semCsOffset']),
    "<--SOCIAL_TOP-->"=>$event->socialBar('top',$row->sem_description,$row->sem_title,$row->sem_prodid),
    "<--SOCIAL_BOTTOM-->"=>$event->socialBar('bottom',$row->sem_description,$row->sem_title,$row->sem_prodid),
    "<--TZ_SELECTOR-->"=>$event->clientTzSelector(),
    "<--COPYRIGHT-->"=>trim(sem_get_target($styleHeader, "* Copyright:", "\n")),
    "<--STYLE_AUTHOR-->"=>trim(sem_get_target($styleHeader, "* Style Author:", "\n")),
    "<--STYLE_NAME-->"=>trim(sem_get_target($styleHeader, "* Style Name:", "\n")),
    "<--HELP-->"=>"<img src='" . WP_PLUGIN_URL . "/the-codetree-semanager/css/images/help.png' alt='help' title=\"Use this feature to select your local time zone and the export options will be formatted to your local time zone.\" width='16px;' align='middle'>"
    );
    $template = file_get_contents(ABSPATH . '/wp-content/plugins/the-codetree-semanager/css/themes/' . get_option('codetree-semanager-style') . '/sem_event.tpl');
    foreach ($templateHooks as $k=>$v) {
        $template = str_replace($k,$v,$template);
    }
    if (isset($_GET['sempreviewid'])) {
        echo "<div style='padding:20px;margin:15px;'>
    <h2 style='margin-top: 0px;'>SEManager - Preview Event</h2>
    " . $template . "
    <p style='text-align: left; padding: 5px;'><input type=\"button\" class=\"button-primary\" name=\"save\" value=\"Edit\" onclick=\"parent.location.href='" . get_option('siteurl') . "/wp-admin/options-general.php?page=codetree_semanager_options&lnkmgr=" . $row->sem_id . "'\" />&nbsp;<input type=\"button\" class=\"button-primary\" onclick=\"parent.location.href='" . get_option('siteurl') . "/wp-admin/options-general.php?page=codetree_semanager_options'\" name=\"cancel\" value=\"Close\" /></p>
    </div>";
    }
    return $template;
}
function semReadStyles() {
    $path = ABSPATH . 'wp-content/plugins/the-codetree-semanager/css/themes';
    if ($handle = opendir( $path )) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (file_exists($path . '/' . $file . '/sem_style.css')) {
                    $header = file_get_contents($path . '/' . $file . '/sem_style.css');
                    $name = trim(sem_get_target($header, "* Style Name:", "\n"));
                }
                if (!empty($name) && $name != '') {
                    $options .= "<option value='$file'>$name</option>\n";
                }
            }
        }
        closedir($handle);
    }
    return $options;
}
function makeDefaultOptions() {
    codetree_semanager_table_maker();
    $opts = array(
    'codetree-semanager-socialbar'=>'bottom',
    'codetree-semanager-googlecal'=>'yes',
    'codetree-semanager-ical'=>'yes',
    'codetree-semanager-hour'=>'24',
    'codetree-semanager-date'=>'l, F jS Y',
    'codetree-semanager-help'=>'yes',
    'codetree-semanager-comp'=>'no',
    'codetree-semanager-style'=>'sleekSilver'
    );
    foreach($opts as $k=>$v) {
        $test = get_option($k);
        if (!$test OR $test == '') {
            update_option($k,$v);
        }
    }
}
   
function semCleanUp() {
  global $wpdb;
  $table_name = $wpdb->prefix . "semanager";
  $wpdb->query("DROP TABLE IF EXISTS $table_name");
    $opts = array(
    'codetree-semanager-socialbar'=>'bottom',
    'codetree-semanager-googlecal'=>'yes',
    'codetree-semanager-ical'=>'yes',
    'codetree-semanager-hour'=>'24',
    'codetree-semanager-date'=>'l, F jS Y',
    'codetree-semanager-help'=>'yes',
    'codetree-semanager-style'=>'sleekSilver',
    'codetree-semanager-registrationkey'=>'',
    'codetree-semanager-comp'=>''
    );
    foreach($opts as $k=>$v) {
        delete_option($k);
    }
}

function codetree_semanager_edit() {
    $event = new event();
    $event->editWin($_GET['lnkmgr']);
}

function codetree_semanager_options() {
    $event = new event();
    $event->defaultProducer = get_bloginfo('name');
    $event->hourType = get_option('codetree-semanager-hour');
    $event->dateFormat = get_option('codetree-semanager-date');
    $event->defaultTitle = get_bloginfo('description');
    if (isset($_POST['addCatchFlag'])) {
        if (!$_POST['saveCheckBit']) {
            echo "<div id=\"message\" class=\"updated highlight\">There is another event using the same <em>title</em>.</div>"; 
        }
        else {
            echo "<div id=\"message\" class=\"updated\">The event has been saved!</div>";
        }
    }
    if (isset($_GET['semrmid']) && !empty($_GET['semrmid'])) {
        echo "<div id=\"message\" class=\"updated highlight\">The event has been deleted!</div>";
    }
    if (isset($_POST['codetree-semanager-socialbar'])) {
         echo "<div id=\"message\" class=\"updated\">Settings saved!</div>";
    }
    
    
    if (isset($_POST['applydomain'])) {
    	if (empty($_POST['applyemail']) OR empty($_POST['applyfirstname']) OR empty($_POST['applylastname'])) {
    		echo "<div id=\"message\" class=\"updated\">Sorry buckaroo, we have to have your first name, last name and email address.</div>";
    	}
    	else {    		
	    	$getReg = file_get_contents("http://mycodetree.com/backendTasks/utilities.php?dir=pluginkey&opts=" . $_POST['applyfirstname'] . "," . $_POST['applylastname'] . "," . $_POST['applyemail'] . "," . $_POST['applydomain'] . ",SEManager");
	        update_option('codetree-semanager-registrationkey', 'YES');
	    	echo "<div id=\"message\" class=\"updated\">The Registration Key has been saved.</div>";
	    	
    	}
    }

?>

 <div class="wrap">
    <h2>Codetree Seminar Event Manager for WordPress</h2> 
    <div class="postbox-container" style="width:70%;">
        <div class="metabox-holder">    
            <div class="meta-box-sortables">
            <?php 
            if (sem_getPhpVersion(5)) {
                $regName = NULL;
                if (!$registrationTest = file_get_contents('http://mycodetree.com/backendTasks/utilities.php?dir=pluginverify&opts=' . get_option('codetree-semanager-registrationkey'))) {
                	echo "<div id=\"message\" class=\"updated\"><p><small><em>Your web server may not be able to talk to other web servers, particularly http://mycodetree.com. Please consult your web host provider for assistance. If your web server is not able to talk to other web servers you will need to move the SEManager plugin to a web server that can. If you consider changing web host providers, we suggest <a href='http://secure.hostgator.com/~affiliat/cgi-bin/affiliates/clickthru.cgi?id=rthcon' target='_blank'>HostGator</a>.</em></small></p></div>";
                }              
  
                if (get_option('codetree-semanager-registrationkey') != 'YES') {
                	$registrationTest = "INVALID REGISTRATION";
                	echo "<div id=\"message\" class=\"updated\">CONFLICT: This domain has not been registered yet or you registered with an email address already in use (one registration per email address and domain). Please use the <em>1-Click Registration</em> form below to get your FREE registration for this domain.</div>";
                }           
                if ($registrationTest != "INVALID REGISTRATION") { 
	                $regName = "<br /><p style='font-size: 9px;color: maroon;font-style: Italic;'>Registration For:" . sem_get_target($registrationTest, "<FIRST>", "</FIRST>") . " " . sem_get_target($registrationTest, "<LAST>", "</LAST>") . " on " . sem_get_target($registrationTest, "<DOMAIN>", "</DOMAIN>") . "</p>"; 
	                $registrationTest = true;
                } 
                else { 
                	$registrationTest = false; 
                }
                if ($registrationTest) { 
            ?>
            <form id='eventAdd' action="/wp-admin/options-general.php?page=codetree_semanager_options" method="post" style='margin: 0px;'>
            <?php sem_postbox('codetreesettings','SEManager Events', codetree_sem_events()); ?>
            <input type="button" class="button-primary" onClick="Show_Popup();" name="save" value="<?php _e('Add Event') ?>" /> 
            </form>
            <br />
            <form id='eventSettings' action="/wp-admin/options-general.php?page=codetree_semanager_options" method="post" style='margin: 0px;'>
            <?php       
                if ( function_exists('wp_nonce_field') ) wp_nonce_field('codetree-semanager-update-options');
                if (isset($_POST['codetree-semanager-registrationkey']) && !empty($_POST['codetree-semanager-registrationkey'])) {
                  $lkey = trim($_POST['codetree-semanager-registrationkey']); 
                  update_option('codetree-semanager-registrationkey', $lkey);             
                }
                if (isset($_POST['codetree-semanager-socialbar']) && !empty($_POST['codetree-semanager-socialbar'])) {
                  $socialbar = trim(stripslashes($_POST['codetree-semanager-socialbar'])); 
                  update_option('codetree-semanager-socialbar', $socialbar);             
                }
                if (isset($_POST['codetree-semanager-googlecal']) && !empty($_POST['codetree-semanager-googlecal'])) {
                  $googlecal = trim(stripslashes($_POST['codetree-semanager-googlecal'])); 
                  update_option('codetree-semanager-googlecal', $googlecal);             
                }
                if (isset($_POST['codetree-semanager-ical']) && !empty($_POST['codetree-semanager-ical'])) {
                  $ical = trim(stripslashes($_POST['codetree-semanager-ical'])); 
                  update_option('codetree-semanager-ical', $ical);             
                }
                if (isset($_POST['codetree-semanager-hour']) && !empty($_POST['codetree-semanager-hour'])) {
                  $hour = trim(stripslashes($_POST['codetree-semanager-hour'])); 
                  update_option('codetree-semanager-hour', $hour);             
                }
                if (isset($_POST['codetree-semanager-date']) && !empty($_POST['codetree-semanager-date'])) {
                  $semdate = trim(stripslashes($_POST['codetree-semanager-date'])); 
                  update_option('codetree-semanager-date', $semdate);             
                }
                if (isset($_POST['codetree-semanager-help']) && !empty($_POST['codetree-semanager-help'])) {
                  $semhelp = trim(stripslashes($_POST['codetree-semanager-help'])); 
                  update_option('codetree-semanager-help', $semhelp);             
                }
                if (isset($_POST['codetree-semanager-comp']) && !empty($_POST['codetree-semanager-comp'])) {
                  $semcomp = trim(stripslashes($_POST['codetree-semanager-comp'])); 
                  update_option('codetree-semanager-comp', $semcomp);             
                }
                 if (isset($_POST['codetree-semanager-style']) && !empty($_POST['codetree-semanager-style'])) {
                  $semstyle = trim(stripslashes($_POST['codetree-semanager-style'])); 
                  update_option('codetree-semanager-style', $semstyle);             
                }
                $rows[] = array(
                        'id' => 'codetree-semanager-registrationkey',
                        'label' => 'SEManager Registration Key',
                        'desc' => 'This is your registration key for The CodeTree SEManager. This key is specifically used for this domain, if you move the SEManager plugin to a different domain this registration key will not work and you\'ll need to aquire a new registration key.',
                        'content' => "Registration Key: <input type='text' name='codetree-semanager-registrationkey' id='codetree-semanager-registrationkey' value='" . get_option('codetree-semanager-registrationkey') . "' />$regName"
                );
                $smbPosDm = array('none'=>NULL,'top'=>NULL,'bottom'=>NULL,'both'=>NULL);
                $smbPosDm[get_option('codetree-semanager-socialbar')] = "checked='checked'";
                $rows[] = array(
                        'id' => 'codetree-semanager-socialbar',
                        'label' => 'SEManager Social Media Bar',
                        'desc' => 'Determines where to display the social media bar. The social media bar is an easy way for visitors to share your event with their friends using popular social media outlets!',
                        'content' => "None: <input type='radio' name='codetree-semanager-socialbar' " . $smbPosDm['none'] . " id='codetree-semanager-socialbar' value='none'/>&nbsp;Top: <input type='radio' name='codetree-semanager-socialbar' " . $smbPosDm['top'] . " id='codetree-semanager-socialbar' value='top'/>&nbsp;Bottom: <input type='radio' name='codetree-semanager-socialbar' " . $smbPosDm['bottom'] . " id='codetree-semanager-socialbar' value='bottom'/>&nbsp;Both: <input type='radio' name='codetree-semanager-socialbar' " . $smbPosDm['both'] . " id='codetree-semanager-socialbar' value='both'/>"
                );
                $gcPosDm = array('yes'=>NULL,'no'=>NULL);
                $gcPosDm[get_option('codetree-semanager-googlecal')] = "checked='checked'";
                $rows[] = array(
                        'id' => 'codetree-semanager-googlecal',
                        'label' => 'Add to Google Calendar',
                        'desc' => 'Choose to offer a convenient button to allow users to easily add the event to their Google Calendar.',
                        'content' => "Yes: <input type='radio' name='codetree-semanager-googlecal' " . $gcPosDm['yes'] . " id='codetree-semanager-googlecal' value='yes'/>&nbsp;No: <input type='radio' name='codetree-semanager-googlecal' " . $gcPosDm['no'] . " id='codetree-semanager-googlecal' value='no'/>"
                );
                $icPosDm = array('yes'=>NULL,'no'=>NULL);
                $icPosDm[get_option('codetree-semanager-ical')] = "checked='checked'";
                $rows[] = array(
                        'id' => 'codetree-semanager-ical',
                        'label' => 'iCalendar Support',
                        'desc' => 'Choose to offer a download link so that users can download a formatted iCalendar file to import into email/claendar applications (<em>e.g MS Outlook&reg;, Thunderbird&reg; ... etc</em>).',
                        'content' => "Yes: <input type='radio' name='codetree-semanager-ical' " . $icPosDm['yes'] . " id='codetree-semanager-ical' value='yes'/>&nbsp;No: <input type='radio' name='codetree-semanager-ical' " . $icPosDm['no'] . " id='codetree-semanager-ical' value='no'/>"
                );
                $hrPosDm = array('12'=>NULL,'24'=>NULL);
                $hrPosDm[get_option('codetree-semanager-hour')] = "checked='checked'";
                $rows[] = array(
                        'id' => 'codetree-semanager-hour',
                        'label' => 'Time Format',
                        'desc' => 'Choose to use a 12 hour or 24 hour clock format for event start and end times. Changing the time format will not effect events that are already saved. Simply edit/save any events that are already created if you wish to change that event\'s time format',
                        'content' => "12 Hour: <input type='radio' name='codetree-semanager-hour' " . $hrPosDm['12'] . " id='codetree-semanager-hour' value='12'/>&nbsp;24 Hour: <input type='radio' name='codetree-semanager-hour' " . $hrPosDm['24'] . " id='codetree-semanager-hour' value='24'/>"
                );
                $rows[] = array(
                        'id' => 'codetree-semanager-date',
                        'label' => 'Date Format',
                        'desc' => 'The date format uses all supported constants for the <a href=\'http://us.php.net/manual/en/function.date.php\' target=\'_blank\'>PHP date function</a> to display dates.',
                        'content' => "<input type='text' name='codetree-semanager-date' id='codetree-semanager-date' value='" . get_option('codetree-semanager-date') . "'/>"
                );
                $hlpPosDm = array('yes'=>NULL,'no'=>NULL);
                $hlpPosDm[get_option('codetree-semanager-help')] = "checked='checked'";
                $rows[] = array(
                        'id' => 'codetree-semanager-help',
                        'label' => 'Help Info',
                        'desc' => 'Choose to display the help and support information (located below in the settings menu).',
                        'content' => "Yes: <input type='radio' name='codetree-semanager-help' " . $hlpPosDm['yes'] . " id='codetree-semanager-help' value='yes'/>&nbsp;No: <input type='radio' name='codetree-semanager-help' " . $hlpPosDm['no'] . " id='codetree-semanager-help' value='no'/>"
                );
                $cptPosDm = array('yes'=>NULL,'no'=>NULL);
                $cptPosDm[get_option('codetree-semanager-comp')] = "checked='checked'";
                $rows[] = array(
                        'id' => 'codetree-semanager-comp',
                        'label' => 'Compatibility Mode',
                        'desc' => 'Turn compatibility mode on if you have difficulties displaying the popup calendar when you add the event or if your current theme uses <em>jQuery UI</em>.',
                        'content' => "Yes: <input type='radio' name='codetree-semanager-comp' " . $cptPosDm['yes'] . " id='codetree-semanager-comp' value='yes'/>&nbsp;No: <input type='radio' name='codetree-semanager-comp' " . $cptPosDm['no'] . " id='codetree-semanager-comp' value='no'/>"
                );
                $header = file_get_contents(ABSPATH . 'wp-content/plugins/the-codetree-semanager/css/themes/' . get_option('codetree-semanager-style') . '/sem_style.css');
                $name = sem_get_target($header, "* Style Name: ", "\n");
                $rows[] = array(
                        'id' => 'codetree-semanager-style',
                        'label' => 'Event Style',
                        'desc' => 'Choose the style to use when the event is displayed in posts or pages (this style only applies to the SEManager event itself). <a href=\'/wp-content/plugins/the-codetree-semanager/semanager_style_creator.pdf\' target=\'_blank\' title=\'Style Creator Manual for SEManager\'>Click Here</a> for the <em>Style Creator</em> manual to learn how to create your own styles!',
                        'content' => "<select name='codetree-semanager-style' id='codetree-semanager-style'/><option value=''>Select Style</option>" . semReadStyles() . "</select><br /><small>(Current theme: <em><strong>$name</strong></em>)</small>"
                );
                ?>        
                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="action" value="update" />   
                <?php sem_postbox('codetreesettings','SEManager Settings', sem_form_table($rows)); ?> 
                <input type="submit" class="button-primary" name="save" value="<?php _e('Save Settings') ?>" /> 
            </form>               
            <?php }
            else {
            	global $current_user;
      			get_currentuserinfo();
                echo file_get_contents("http://mycodetree.com/pluginbranding.php?pluginname=semanager") . "<p>Registration is <b>FREE</b>! Use the form below to get your <b>FREE</b> registration key. Even if you have a registration and misplaced it, you can use the form below to restore your <b>FREE</b> registration for this domain! Only one registration per domain/email address, each domain will need a unique registration key.</p>";
                $applydata = "<div style='margin: 5px;'>
                <form name='applyforreg' action='/wp-admin/options-general.php?page=codetree_semanager_options' method='post' style='margin: 0px;'>
                <p>We want to develop better stuff for you. After you evaluate the plugin, would you take a moment and tell us what you like and don't like about the plugin? What can we do better for you?</p>
                <p>First Name:&nbsp;<input type=\"text\" name=\"applyfirstname\" id=\"applyfirstname\" size=\"25\" value=\"" . $current_user->user_firstname . "\">&nbsp;Last Name:&nbsp;<input type=\"text\" name=\"applylastname\" id=\"applylastname\" size=\"25\" value=\"" . $current_user->user_lastname . "\"><br />
                Email Address:&nbsp;<input type=\"text\" name=\"applyemail\" id=\"applyemail\" size=\"25\" value=\"" . $current_user->user_email . "\"></p>
                <input type=\"hidden\" name=\"applydomain\" id=\"applydomain\" value=\"" . $_SERVER['HTTP_HOST'] . "\">
                <input type=\"submit\" class='button-primary' name='save' value='Get your FREE registration key HERE!'/>
                </form>
                </div>";
			    echo "<div class='wrap'><div class='postbox-container' style='width:70%;'><div class='metabox-holder'><div class='meta-box-sortables'>";
			    sem_postbox('codetreapply','Get your FREE Registration Key with our <em>1-Click</em> Registration!', $applydata);
			    echo "</div></div></div></div>";
            }
            }
            else {
                echo "<p>The minimum required PHP Version for The CodeTree SEManager to function is <strong><em>PHP Version 5.0</em</strong> and we've detected that your server is using <strong><em>PHP Version "; if (function_exists('phpversion')) { echo floatval(phpversion()); } echo "</em></strong>. please upgrade your PHP version or talk to your web host. If you consider moving your website to a different web host, MyCodeTree recomends <a href='http://secure.hostgator.com/~affiliat/cgi-bin/affiliates/clickthru.cgi?id=rthcon' target='_blank'>HostGator.com</a>.</p><p>If you would like further assistance please feel free to contact MyCodeTree at <a href='mailto:support@mycodetree.com?subject=PHP Version with CodeTree SEManager Plugin'>support@mycodetree.com</a>.</p>";   
            }
             ?>
            </div>
        </div>
    </div>
</div>
<table width='75%'>
<tr>
<td>
<?php
$helpDisplay = get_option('codetree-semanager-help');
if (!$registrationTest) { $helpDisplay = 'no'; }
if ($helpDisplay != 'no' && sem_getPhpVersion(5)) {
    $helpData = "
    <div><p style='margin: 3px;text-align: center; background-color: Gold; padding: 5px;'>Obtaining support for The CodeTree SEManager is easy!</p></div>
    <table width='100%' cellspacing='3px'>
    <tr>
    <td valign='top'>
    <p style='text-align: center;background-color: #DDD;'>Support Resources:</p>
    <ul>
    <li style='margin-left: 15px;font-size:11px;'>I.) Visit the <a href='http://mycodetree.com/forum/viewforum.php?f=11' target='_blank' title='Support forum for The CodeTree SEManager'>support forum for The CodeTree SEManager</a></li>
    <li style='margin-left: 15px;font-size:11px;'>II.) Vistit the <a href='http://mycodetree.com/support/frequently-asked-questions/' target='_blank' title='MyCodeTree FAQ Section'>F.A.Q section</a> at MyCodeTree.com</li>
    <li style='margin-left: 15px;font-size:11px;'>III.) <a href='mailto:pluginsupport@mycodetree.com?subject=SEManager Support' target='_blank' title='Email MyCodeTree about SEManager'>Contact us</a> directly by email</li>
    <li style='margin-left: 15px;font-size:11px;'>IV.) Shout at us on Twitter <a href='http://twitter.com/mycodetree' target='_blank' title='@MyCodeTree'>@mycodetree</a></li>
    <li style='margin-left: 15px;font-size:11px;'>IIV.) Shout at us <a href='http://www.facebook.com/pages/Sidney-OH/MyCodeTree/145101265500968' target='_blank' title='MyCodeTree on Facebook'>on Facebook</a></li> 
    </ul>
    </td>
    <td valign='top'>
    <p style='text-align: center;background-color: #DDD;'>Helpful Topics:</p>
    <ul>
    <li style='margin-left: 15px;font-size:11px;'>I.) <a href='/wp-content/plugins/the-codetree-semanager/semanager_style_creator.pdf' target='_blank' title='How to create custom styles for The CodeTree SEManager'>Create custom SEManager styles</a></li>
    <li style='margin-left: 15px;font-size:11px;'>II.) <a href='http://mycodetree.com/videos/plugins/sem-vid-tut/' target='_blank' title='Video Manual for The CodeTree SEManager'>Video Manual for SEManager</a></li>        <li style='margin-left: 15px;font-size:11px;'>III.) <a href='http://secure.hostgator.com/~affiliat/cgi-bin/affiliates/clickthru.cgi?id=rthcon' target='_blank' title='Recommended Web Host Provider'>Web Host Recommendations</a></li>
    </ul>
    </td>
    </tr>
    </table>";
    echo "<div class='postbox-container' style='width:70%;'><div class='metabox-holder'><div class='meta-box-sortables'>";
    sem_postbox('codetreehelp','SEManager Help', $helpData);
    echo "</div></div></div></div>";
}
?> 
</td>
</tr> 
</table>
<?php
$event->popupWin();
}
?>