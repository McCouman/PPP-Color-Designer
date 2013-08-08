<?php
/**
 * Plugin Name: PPP Color Designer
 * Plugin URI: http://podbe.wikibyte.org
 * Description: Add your player color css to your WordPress frontend. 
 * Version: 1.5
 * Author: Michael McCouman jr. (ColorPlugin), Simon Waldherr (ColorConverter)
 * Author URI: http://labs.wikibyte.org
 */
define('PLAYER_CSS_VERSION', '1.5');
define('PLAYER_CSS_FILE', WP_CONTENT_DIR . '/uploads/player-css-');
define('PLAYER_CSS_URI', WP_CONTENT_URL . '/uploads/player-css-');

/**
 * Plugin Basics.
 *
 * @since 1.4.0
 */
	function admin_register_head() {

				// JQ 
				$urljq = plugins_url(basename(dirname(__FILE__)) . '/js/jquery.js?ver=' . PODLOVE_WEB_DESIGNER);
				 echo "<script type='text/javascript' src='$urljq'></script>\n";
				
				// easy Player css includes (als tests)
				$playercss = plugins_url(basename(dirname(__FILE__)) . '/podlove-web-player/podlove-web-player.css');
				 echo "<link rel='stylesheet' type='text/css' href='$playercss' media='screen' type='text/css' />\n";	
				
				// icons
				$fontellocss = plugins_url(basename(dirname(__FILE__)) . '/podlove-web-player/font/css/fontello.css');
				 echo "<link rel='stylesheet' type='text/css' href='$fontellocss' />\n";	
	
		// CConverter
		$cconv = plugins_url(basename(dirname(__FILE__)) . '/podlove-web-player/libs/pwpdesigner/colorconv.js	?ver=' . PODLOVE_WEB_DESIGNER);
		 echo "<script id='ccv' type='text/javascript' src='$cconv'></script>\n";

	
		//Designer Script
		$urlpwp1 = plugins_url(basename(dirname(__FILE__)) . '/podlove-web-player/libs/pwpdesigner/script.js?ver=' . PODLOVE_WEB_DESIGNER);
		 echo "<script id='pwpd1' type='text/javascript' src='$urlpwp1'></script>\n";

		//#fixed slieders		
		$fixpwp2 = plugins_url(basename(dirname(__FILE__)) . '/podlove-web-player/libs/pwpdesigner/html5slider.js?ver=' . PODLOVE_WEB_DESIGNER);
		 echo "<script id='pwpd1' type='text/javascript' src='$fixpwp2'></script>\n";
	
	
				// easy player js includes (tests player ppp)
				$hshiv = plugins_url(basename(dirname(__FILE__)) . '/podlove-web-player/libs/html5shiv.js');
				 echo "<script type='text/javascript' src='$hshiv'></script>\n";
				$playerjs = plugins_url(basename(dirname(__FILE__)) . '/podlove-web-player/podlove-web-player.js');
				 echo "<script type='text/javascript' src='$playerjs'></script>\n";
				
	
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
			'player-css-frontend' => player_css_get_default('frontend'),
			'player-css-backend' => player_css_get_default('backend'),
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
 * Default CSS Frontend and Backend.
 *
 * Usage:
 * 		player_css_get_default('frontend');
 * 		player_css_get_default('backend');
 *
 * @since 1.0.0
 */
if(!function_exists('player_css_get_default')) {
	function player_css_get_default($var_sWhere = '') {
		if(!$var_sWhere) {
			return;
		}

		switch($var_sWhere) {
			case 'frontend':
				$var_sPlayerCssDefault = '@charset "UTF-8";';
				break;

			case 'backend':
				$var_sPlayerCssDefault = '@charset "UTF-8";';
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
				'player-css-frontend' => stripslashes(wp_filter_post_kses($_REQUEST['player-css-frontend'])),
				'player-css-backend' => stripslashes(wp_filter_post_kses($_REQUEST['player-css-backend']))
			);

			player_css_set_options($array_NewOptions);

			echo '<br /><div id="message" class="updated fade">';
			echo '<p><strong>';
			_e('Player CSS erfolgreich erstellt und gespeichert :)', 'player-css');
			echo '</strong></p>';
			echo '</div>';
		}
		?>
<style>.nots {display:none;} div#mc {background: #efe; border: 1px solid #afa; padding:10px; width: 84%;} div#sw {background: #ffe; border: 1px solid #ff5; padding:10px; width: 84%;}</style>	
<div class="wrap">
			<div class="icon32"><img style="width:40px;" src="https://raw.github.com/podlove/podlove-web-player/2.0.x/podlove-web-player/samples/coverimage.png" /><br /></div>
			<h2><?php _e('Lege die Farbe deines Webplayer fest:', 'player-css'); ?></h2>
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
		<style id="pwpdesigner"></style>
		<audio id="testplayer">
			<source src="#.mp4" type="audio/mp4"></source>
		</audio>
		<script>
			$('#testplayer').podlovewebplayer({
				poster: 'https://raw.github.com/podlove/podlove-web-player/2.0.x/podlove-web-player/samples/coverimage.png',
				title: 'PODLOVE Color Designer Test',
				permalink: 'http://podlove.github.com/podlove-web-player/standalone.html',
				subtitle: 'Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.',
				chapters: '00:00:00.000 Chapter One title'
							+"\n"+'00:00:01.000 Chapter Two with <a href="#">hyperlink</a>'
							+"\n"+'00:00:01.500 Chapter Three',
				summary: '<p>Summary and even links <a href="https://github.com/gerritvanaaken/podlove-web-player">Podlove Web Player</a></p>',
				duration: '00:02.500',
				alwaysShowHours: true,
				startVolume: 0.8,
				width: 'auto',
				summaryVisible: false,
				timecontrolsVisible: false,
				sharebuttonsVisible: false,
				chaptersVisible: false
			});
		</script>
	</p>
		<form method="post" action="" id="wp-twitter-options">
				<?php wp_nonce_field('player-css-options'); ?>
				<div class="player-css-wrapper nots">
					<div class="nots">
					<!--Frontend-->
						<p><textarea id="pwpstyle1" class="nots" name="player-css-frontend"><?php echo esc_textarea(player_css_get_options('player-css-frontend')); ?></textarea></p>
					</div>
					<div class="nots">
					<!--backend-->
						<p><textarea id="pwpconsole" class="nots" name="player-css-backend"><?php echo esc_textarea(player_css_get_options('player-css-backend')); ?></textarea></p>
					</div>
				</div>
				<p class="submit">
					<input class="button button-primary button-large" type="submit" name="Submit" value="<?php _e('Speichern und CSS erstellen', 'player-css'); ?>" />
				</p>
		</form>
</div>
<?php $anzahl = 2; $simon = '<a href="https://flattr.com/profile/SimonWaldherr"><img style="margin-bottom: -7px;" src="https://a248.e.akamai.net/camo.github.com/739a757846f69c1cc10163619eec008e871b591b/687474703a2f2f6170692e666c617474722e636f6d2f627574746f6e2f666c617474722d62616467652d6c617267652e706e67" /> Simon Waldherr</a>  (<a style="text-decoration:none !important; color:#000 !important;" href="https://github.com/SimonWaldherr/ColorConverter.js">ColorConverter.js</a>)';$mccouman = '<a href="https://flattr.com/profile/mccouman"><img style="margin-bottom: -7px;" src="https://a248.e.akamai.net/camo.github.com/739a757846f69c1cc10163619eec008e871b591b/687474703a2f2f6170692e666c617474722e636f6d2f627574746f6e2f666c617474722d62616467652d6c617267652e706e67" /> Michael McCouman jr.</a> <a style="text-decoration:none !important; color:#000 !important;" href="https://github.com/McCouman/PPP-Color-Designer">(Wordpress Plugin)</a>';$url[1] = '<div class="wrap" id="sw"><h2>Unterstützen:</h2><p>'. $simon. ', <span style="padding-left:10px;"></span>'. $mccouman; $url[0] = '<div class="wrap" id="mc"><h2>Unterstützen:</h2><p>'. $mccouman. ', <span style="padding-left:10px;"></span>'. $simon; srand(time()); $random = rand(0,$anzahl - 1); echo $url[$random] . '</p></div>'; ?>
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

		if(player_css_get_options('player-css-frontend') != player_css_get_default('frontend')) {
			custom_css_write('frontend', $array_Options['player-css-frontend']);
		}

		if(player_css_get_options('player-css-backend') != player_css_get_default('backend')) {
			custom_css_write('backend', $array_Options['player-css-backend']);
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
			@unlink(PLAYER_CSS_FILE . $var_sWhere . '.css');
		} else {
			@file_put_contents(PLAYER_CSS_FILE . $var_sWhere . '.css', $var_sCss);
		}
	}
}

/**
 * Player CSS in WordPress Frontend einbinden.
 *
 * @since 1.0.0
 */
if(!function_exists('custom_css_to_frontend')) {
	// Frontend
	function custom_css_to_frontend() {
		if(file_exists(PLAYER_CSS_FILE . 'frontend.css') && player_css_get_options('player-css-frontend') != player_css_get_default('frontend')) {
			echo '<link rel="stylesheet" type="text/css" href="' . PLAYER_CSS_URI . 'frontend.css?ver=' . PLAYER_CSS_VERSION . '" />';
		}
	}

	add_action('wp_head', 'custom_css_to_frontend');
}
?>
