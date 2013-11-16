<?php
/**
 * Plugin Name: PPP Color Designer
 * Plugin URI: http://podbe.wikibyte.org
 * Description: Design a favorite color for your Podlove Webplayer. 
 * Version: 1.5.9
 * Author: Michael McCouman jr. (WordpressPlugin), Simon Waldherr (ColorConverter)
 * Author URI: https://github.com/McCouman/PPP-Color-Designer/
 */
define('PLAYER_CSS_VERSION', '1.5.9');
define('PLAYER_CSS_FILE', WP_CONTENT_DIR . '/uploads/player-css-');
define('PLAYER_CSS_URI', WP_CONTENT_URL . '/uploads/player-css-');

/**
 * Plugin Basics.
 *
 * @since 1.4.0
 */
function admin_register_head() {

	//JQ 
	#$urljq = plugins_url(basename(dirname(__FILE__)) . '/js/libs/jquery.min.js?ver=' . PLAYER_CSS_VERSION);
	#	 echo "<script id='pcss-jq' type='text/javascript' src='$urljq'></script>\n";
				
	//Player standard css
	$playercss = plugins_url(basename(dirname(__FILE__)) . '/js/static/podlove-web-player.css?ver=' . PLAYER_CSS_VERSION);
		 echo "<link id='pcss-css' rel='stylesheet' type='text/css' href='$playercss' media='screen' type='text/css' />\n";	
				
	//icons toggles
	$fontellocss = plugins_url(basename(dirname(__FILE__)) . '/js/font/css/fontello.css?ver=' . PLAYER_CSS_VERSION);
		 echo "<link id='pcss-tcss' rel='stylesheet' type='text/css' href='$fontellocss' />\n";	
	
	//CC
	$cconv = plugins_url(basename(dirname(__FILE__)) . '/js/libs/pwpdesigner/colorconv.js?ver=' . PLAYER_CSS_VERSION);
		 echo "<script id='pcss-cc' type='text/javascript' src='$cconv'></script>\n";
	//Script
	$urlpwp1 = plugins_url(basename(dirname(__FILE__)) . '/js/libs/pwpdesigner/script.js?ver=' . PLAYER_CSS_VERSION);
		 echo "<script id='pcss-sc' type='text/javascript' src='$urlpwp1'></script>\n";

	//Sliders #McCouman		
	$fixpwp2 = plugins_url(basename(dirname(__FILE__)) . '/js/libs/pwpdesigner/slider.js?ver=' . PLAYER_CSS_VERSION);
		 echo "<script id='pcss-sl' type='text/javascript' src='$fixpwp2'></script>\n";
	
}
add_action('admin_head', 'admin_register_head');

/**
 * Plugin initialisieren.
 *
 * @since 1.0.0
 */
if(!function_exists('player_css_init')) {
	function player_css_init() {
		/**
		 * Einstellungen registrieren.
		 */
		if(function_exists('register_setting')) {
			register_setting('player-css-options', 'player-css-options');
		}
		/**
		 * Sprachdatei wählen. (in dieser Version noch nicht vorhanden!)
		 */
		if(function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('player-css', false, dirname(plugin_basename( __FILE__ )) . '/l10n/');
		}
	}
	if(is_admin()) {
		add_action('admin_init', 'player_css_init');
	}
}

/**
 * Festlegen was zu tun ist, bei Aktivierung des Plugins.
 *
 * @since 1.0.0
 */
if(!function_exists('player_css_activate')) {
	function player_css_activate() {
		$sh_adminbar_add_options = array(
			'player-css-pluginname' => 'Your Custom CSS',
			'player-css-pluginversion' => PLAYER_CSS_VERSION,
			'player-css-playercss' => player_css_get_default('playercss'),
			'player-css-jsoninput' => player_css_get_default('jsoninput'),
		);
		if(is_array(get_option('player-css-options'))) {
			add_option('player-css-options', $sh_adminbar_add_options);
		} else {
			update_option('player-css-options', $sh_adminbar_add_options);
		}
	}

	/**
	 * Plugin aktivieren.
	 */
	register_activation_hook(__FILE__, 'player_css_activate');
}

/**
 * Default CSS playercss and jsoninput.
 *
 * Usage:
 * 		player_css_get_default('playercss');
 * 		player_css_get_default('jsoninput');
 *
 * @since 1.0.0
 */
if(!function_exists('player_css_get_default')) {
	function player_css_get_default($var_sWhere = '') {
		if(!$var_sWhere) {
			return;
		}
		switch($var_sWhere) {
			case 'playercss':
				$var_sPlayerCssDefault = '';
				break;

			case 'jsoninput':
				$var_sPlayerCssDefault = '{"hue":"185","sat":"65","lum":"44","gra":"16"}'; //Standard wenn gestartet
				break;
		}
		return $var_sPlayerCssDefault;
	}
}

/**
 * Optionsseite in das Dashboard einbinden.
 *
 * @since 1.0.0
 */
if(!function_exists('player_css_options')) {
	function player_css_options() {
		if(current_user_can('manage_options')) {
			add_options_page('PPP Color Designer', __('PPP Color Designer', 'player-css'), 8, basename(__FILE__, '.php'), 'player_css_options_page');
		}
	}
	if(is_admin()) {
		add_action('admin_menu', 'player_css_options');
	}
}

if(!function_exists('player_css_options_page')) {
	function player_css_options_page() {
		/**
		 * Status von $_REQUEST abfangen.
		 *
		 * @since 1.0.0
		 */
		if(!empty($_REQUEST) && isset($_REQUEST['Submit'])) {
			/**
			 * Validate the nonce.
			 *
			 * @since 1.0.0
			 */
			check_admin_referer('player-css-options');

			$array_NewOptions = array(
				'player-css-playercss' => stripslashes(wp_filter_post_kses($_REQUEST['player-css-playercss'])),
				'player-css-jsoninput' => stripslashes(wp_filter_post_kses($_REQUEST['player-css-jsoninput']))
			);
			player_css_set_options($array_NewOptions);
			echo '<div id="message" class="updated fade">';
			echo '<p><strong>';
			_e('Player CSS erfolgreich erstellt und gespeichert :)', 'player-css');
			echo '</strong></p>';
			echo '</div>';
		}
		?>
<style>.nots {display:none;} div#mc {background: #efe; border: 1px solid #afa; padding:10px; width: 84%;} div#sw {background: #ffe; border: 1px solid #ff5; padding:10px; width: 84%;}</style>	
<div class="wrap">
			<div class="icon32"><img style="width:40px;" src="https://raw.github.com/podlove/podlove-web-player/2.0.x/podlove-web-player/samples/coverimage.png" /><br /></div>
			<h2 style="margin-bottom: 15px;"><?php _e('Lege die Farbe deines Webplayer fest:', 'player-css'); ?></h2>
			<p><?php _e('Design the Podlove Webplayer in your favorite color.', 'player-css'); ?></p>
		<div class='colorslider'>
			<div id='color1' class='box'>
			<div>
				<input type='button' onclick='pwpdinsertcolor();' class='button' value='Farbe eingeben' /> 
				<input type='button' onclick='pwpdrandomcolor();' class='button' value='Automatisch' /> 
				<input type='button' onclick='pwpdcolorreset();' class='button' value='Zurücksetzen' /> 
			</div>
 				<br/>
			<div>
				<label for='hue'>Farbe</label> <br>
				<input id='hue' style="width: 86%;" onchange='pwpdcolorize();' name='hue' type='range' max='360' min='0'>
				<img style="width:86.90%;" src="http://podbe.wikibyte.org/wp-content/plugins/podbe/farb-info.png">	
			</div>
			<div>
				<label for='sat'>Sättigung</label> <br>
				<input id='sat' style="width: 86%;" onchange='pwpdcolorize();' name='sat' type='range' max='100' min='0'>
				<img style="width:86.90%;" src="http://podbe.wikibyte.org/wp-content/plugins/podbe/settigung-info.png">
			</div>
			<div>
				<label for='lum'>Helligkeit</label> <br>
				<input id='lum' style="width: 86%;" onchange='pwpdcolorize();' name='lum' type='range' max='100' min='0'>
				<img style="width:86.90%" src="http://podbe.wikibyte.org/wp-content/plugins/podbe/helligkeit-info.png">
			</div>
			<div>
				<label for='gra'>Kontrast</label> <br>
				<input id='gra' style="width: 86%;" onchange='pwpdcolorize();' name='gra' type='range' max='20' min='0'>
				<img style="width:86.90%" src="http://podbe.wikibyte.org/wp-content/plugins/podbe/kontrast-info.png">
			</div>
			<br>
				<br />
			</div>
		</div>	
	<p style="width: 87%;">
	<lable><b>Vorschau:</b></lable>
		<style id="pwpdesigner"></style>
		<div class="podlovewebplayer_wrapper podlovewebplayer_audio" style="width: 87%"><div class="podlovewebplayer_meta"><a class="bigplay" title="Play Episode"></a><div class="coverart"><img src="https://raw.github.com/podlove/podlove-web-player/2.0.x/podlove-web-player/samples/coverimage.png" alt=""></div><h3 style="cursor: pointer; box-shadow: none!important; border-bottom-style: none; overflow: hidden;"><a style="cursor: pointer;">Webplayer Vorschau</a></h3><div class="subtitle" style="margin-left: 165px!important;">Designe deinen Player in deiner Lieblngsfarbe</div><div class="togglers"><a class="infowindow infobuttons pwp-icon-info-circle" title="More information about this"></a><a class="chaptertoggle infobuttons pwp-icon-list-bullet" title="Show/hide chapters"></a><a class="showcontrols infobuttons pwp-icon-clock" title="Show/hide time navigation controls"></a><a class="showsharebuttons infobuttons pwp-icon-export" title="Show/hide sharing controls"></a></div></div><div class="summary" style="height: 0px;"><p></p><p>Nullam id dolor id nibh ultricies vehicula ut id elit. Nulla vitae elit libero, a pharetra augue. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Cras mattis consectetur purus sit amet fermentum. Nullam id dolor id nibh ultricies vehicula ut id elit. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</p></div><div id="mep_0" class="mejs-container svg mejs-audio" style="width: 100%; height: 30px;"><div class="mejs-inner"><div class="mejs-mediaelement"></div><div class="mejs-layers"><div class="mejs-poster mejs-layer" style="display: none; width: 100%; height: 30px;"></div></div><div class="mejs-controls"><div class="mejs-time mejs-currenttime-container"><span class="mejs-currenttime">00:00:00</span></div><div class="mejs-time-rail" style="width: 87%;"><span class="mejs-time-total" style="width: 85%;"><span class="mejs-time-buffering" style="display: none;"></span><span class="mejs-time-loaded" style="width: 605px;"></span><span class="mejs-time-current" style="width: 0px;"></span><span class="mejs-time-handle" style="left: -7px;"></span><span class="mejs-time-float"><span class="mejs-time-float-current">00:00</span><span class="mejs-time-float-corner"></span></span></span></div><div class="mejs-time mejs-duration-container"><span class="mejs-duration">00:00:02</span></div><div class="mejs-horizontal-volume-slider mejs-mute"></div></div><div class="mejs-clear"></div></div></div><div class="podlovewebplayer_timecontrol podlovewebplayer_controlbox"><a class="prevbutton infobuttons pwp-icon-to-start" title="Jump backward to previous chapter"></a><a class="nextbutton infobuttons pwp-icon-to-end" title="next chapter"></a><a class="rewindbutton infobuttons pwp-icon-fast-bw" title="Rewind 30 seconds"></a><a class="forwardbutton infobuttons pwp-icon-fast-fw" title="Fast forward 30 seconds"></a></div><div class="podlovewebplayer_sharebuttons podlovewebplayer_controlbox"><a class="currentbutton infobuttons pwp-icon-link" title="Get URL for this"></a><a target="_blank" class="tweetbutton infobuttons pwp-icon-twitter" title="Share this on Twitter"></a><a target="_blank" class="fbsharebutton infobuttons pwp-icon-facebook" title="Share this on Facebook"></a><a target="_blank" class="gplusbutton infobuttons pwp-icon-gplus" title="Share this on Google+"></a><a target="_blank" class="adnbutton infobuttons pwp-icon-appnet" title="Share this on App.net"></a><a target="_blank" class="mailbutton infobuttons pwp-icon-mail" title="Share this via e-mail"></a></div><div class="podlovewebplayer_chapterbox showonplay active" style="height: 93px;"><table class="podlovewebplayer_chapters linked linked_all" style="display: table;"><caption>Podcast Chapters</caption><thead><tr><th scope="col">Chapter Number</th><th scope="col">Start time</th><th scope="col">Title</th><th scope="col">Duration</th></tr></thead><tbody><tr class="chaptertr" data-start="0" data-end="1"><td class="starttime"><span>00:00</span></td><td class="chaptername">Intro des Podcasts</td><td class="timecode"><span>00:10:00</span></td></tr><tr class="chaptertr oddchapter" data-start="1" data-end="1.5"><td class="starttime"><span>00:10</span></td><td class="chaptername">Infos deines Podcastings</td><td class="timecode"><span>00:15:20</span></td></tr><tr class="chaptertr" data-start="1.5" data-end="2.5"><td class="starttime"><span>00:15</span></td><td class="chaptername">Ending des Podcasts</td><td class="timecode"><span>00:20:00</span></td></tr></tbody></table></div><div class="podlovewebplayer_tableend"></div></div>
	</p>
	<form method="post" action="" id="wp-twitter-options">
		<?php wp_nonce_field('player-css-options'); ?>
		<div class="player-css-wrapper nots">
			<div class="nots">
				<!--player css for playercss-->
				<p><textarea id="pwpstyle1" class="nots" name="player-css-playercss"><?php echo esc_textarea(player_css_get_options('player-css-playercss')); ?></textarea></p>
			</div>
			<div class="nots">
				<!--json live player in admin jsoninput-->
				<p><textarea id="pwpconsole" class="nots" name="player-css-jsoninput"><?php echo esc_textarea(player_css_get_options('player-css-jsoninput')); ?></textarea></p>
			</div>
			</div>
			<p class="submit">
				<input class="button button-primary button-large" type="submit" name="Submit" value="<?php _e('Speichern und CSS erstellen', 'player-css'); ?>" />
			</p>
	</form>
</div><?php $anzahl = 2; $simon = '<a href="https://flattr.com/profile/SimonWaldherr"><img style="margin-bottom: -7px;" src="https://a248.e.akamai.net/camo.github.com/739a757846f69c1cc10163619eec008e871b591b/687474703a2f2f6170692e666c617474722e636f6d2f627574746f6e2f666c617474722d62616467652d6c617267652e706e67" /> Simon Waldherr</a>  (<a style="text-decoration:none !important; color:#000 !important;" href="https://github.com/SimonWaldherr/ColorConverter.js">ColorConverter.js</a>)';$mccouman = '<a href="https://flattr.com/profile/mccouman"><img style="margin-bottom: -7px;" src="https://a248.e.akamai.net/camo.github.com/739a757846f69c1cc10163619eec008e871b591b/687474703a2f2f6170692e666c617474722e636f6d2f627574746f6e2f666c617474722d62616467652d6c617267652e706e67" /> Michael McCouman jr.</a> <a style="text-decoration:none !important; color:#000 !important;" href="https://github.com/McCouman/">(Wordpress Plugin)</a>';$url[1] = '<div class="wrap" id="sw"><h2>Unterstützen:</h2><p>'. $simon. ', <span style="padding-left:10px;"></span>'. $mccouman; $url[0] = '<div class="wrap" id="mc"><h2>Unterstützen:</h2><p>'. $mccouman. ', <span style="padding-left:10px;"></span>'. $simon; srand(time()); $random = rand(0,$anzahl - 1); echo $url[$random] . '</p></div>'; ?>
<?php
	}
}
if(!function_exists('player_css_get_options')) {
	function player_css_get_options($var_sOption = '') {
		$array_PluginOptions = get_option('player-css-options');
		if(empty($var_sOption)) {
			return $array_PluginOptions;
		} else {
			return $array_PluginOptions[$var_sOption];
		}
	}
}

/**
 * Neues CSS in die Datenbank eintragen.
 *
 * @since 1.0.0
 */
if(!function_exists('player_css_set_options')) {
	function player_css_set_options($array_NewOptions = array()) {
		$array_Options = array_merge((array) get_option('player-css-options'), $array_NewOptions);
		update_option('player-css-options', $array_Options);
		wp_cache_set('player-css-options', $array_Options);
		if(player_css_get_options('player-css-playercss') != player_css_get_default('playercss')) {
			custom_css_write('playercss', $array_Options['player-css-playercss']);
		}
		if(player_css_get_options('player-css-jsoninput') != player_css_get_default('jsoninput')) {
			custom_css_write('jsoninput', $array_Options['player-css-jsoninput']);
		}
	}
}

/**
 * CSS-Dateien schreiben.
 * Diese werden in /wp-content/uploads/ abgelegt.
 *
 * @since 1.0.0
 */

if(!function_exists('custom_css_write')) {
	function custom_css_write($var_sWhere = '', $var_sCss = '') {
		if($var_sCss == '' || $var_sCss == player_css_get_default($var_sWhere)) {
#			@unlink(PLAYER_CSS_FILE . $var_sWhere . '.css');
		} else {
#			@file_put_contents(PLAYER_CSS_FILE . $var_sWhere . '.css', $var_sCss);
		}
	}
}





/**
 * Player CSS in WordPress playercss einbinden.
 *
 * @since 1.0.0
 */
 
/*
if(!function_exists('custom_css_to_playercss')) {
	//file playercss
	function custom_css_to_playercss() {
		if(file_exists(PLAYER_CSS_FILE . 'playercss.css') && player_css_get_options('player-css-playercss') != player_css_get_default('playercss')) {
			echo '<link id="myplayercss" rel="stylesheet" type="text/css" href="' . PLAYER_CSS_URI . 'playercss.css?ver=' . PLAYER_CSS_VERSION . '" />';
		}
	}
	add_action('wp_head', 'custom_css_to_playercss');
}
*/

if(!function_exists('custom_css_to_playercss')) {
	//db playercss
	function custom_css_to_playercss() {
		echo '<style>'. esc_textarea(player_css_get_options('player-css-playercss')) .'</style>';
	}
	add_action('wp_head', 'custom_css_to_playercss');
}
?>
