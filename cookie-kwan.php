<?php

/**
 * Plugin Name: Cookie Kwan
 * Description: Yet another cookie consent plugin. Suitable for a Bootstrap-4-based site that does not track users. (Settings are on the "Reading" page.)
 * Version: 1.0
 * Author: Toby Inkster
 * Author URI: http://toby.ink/
 */

define( 'COOKIE_KWAN_NAME', 'gdpr_consent' );
define( 'COOKIE_KWAN_DEFAULT_MESSAGE', '<p>Certain features of this site require cookies. This includes the ability to track whether you clicked the "Accept Cookies" button.</p>' );

if ( ! function_exists('cookie_consent_given') ) {
	function cookie_consent_given () {
		return $_COOKIE[COOKIE_KWAN_NAME];
	}
}

add_action( 'admin_init', function () {
	add_settings_field( 'cookie_kwan_message', 'Cookie consent message', function () {
		$message = get_option( 'cookie_kwan_message' );
		if ( empty($message) ) {
			$message = COOKIE_KWAN_DEFAULT_MESSAGE;
		}
		echo '<textarea rows="6" cols="60" name="cookie_kwan_message" id="cookie_kwan_message">' . htmlspecialchars($message) . '</textarea><br>HTML message shown explaining what cookies are used for';
	}, 'reading' );
	register_setting( 'reading', 'cookie_kwan_message' );	
} );

add_action( 'wp_enqueue_scripts', function () {
	if ( cookie_consent_given() ) {
		return;
	}
	
	if ( ! wp_script_is( 'jquery', 'done' ) ) {
		wp_enqueue_script( 'jquery' );
	}
	
	$message = get_option( 'cookie_kwan_message' );
	if ( empty($message) ) {
		$message = COOKIE_KWAN_DEFAULT_MESSAGE;
	}
	
	wp_add_inline_script( 'jquery', "

(function ($) {
	var COOKIE_KWAN_NAME      = '" . addslashes( COOKIE_KWAN_NAME ) . "';
	var COOKIE_KWAN_MESSAGE   = '" . addslashes( $message ) . "';
	
	$.getScript('https://cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js', function () {
		$(function () {
			if ( ! Cookies.get(COOKIE_KWAN_NAME) ) {
				\$('body').append(
					\"<div id='cookie_kwan' class='card bg-light'>\" +
						\"<div class='card-header'><strong>This website uses cookies</strong></div>\" +
						\"<div class='card-body'>\" + COOKIE_KWAN_MESSAGE + \"</div>\" +
						\"<div class='card-footer'><button class='btn btn-primary' id='cookie_kwan_accept'><i class='fa fa-check'></i> Accept Cookies</button> <button class='btn btn-danger' id='cookie_kwan_close'><i class='fa fa-close'></i> Close message</button></div>\" +
					\"</div>\"
				);
				var \$kwan = \$('#cookie_kwan');
				\$kwan.css({
					'position'  : 'fixed',
					'bottom'    : '10px',
					'right'     : '10px',
					'width'     : '500px',
					'max-width' : '80%',
					'min-width' : '280px',
				});
				$('#cookie_kwan_accept').click(function () {
					Cookies.set(COOKIE_KWAN_NAME, 1, { expires: 365 });
					location.reload();
				});
				$('#cookie_kwan_close').click(function () {
					\$kwan.fadeOut();
				});
			}
		});
	});
})(jQuery);

");
} );

add_shortcode( 'if_cookies', function ( $atts=array(), $content='' ) {
	if ( cookie_consent_given() ) {
		return do_shortcodes($content);
	}
	if ( array_key_exists( 'else', $atts ) ) {
		return do_shortcodes($atts['else']);
	}
	return '';
} );

add_shortcode( 'if_no_cookies', function ( $atts=array(), $content='' ) {
	if ( !cookie_consent_given() ) {
		return do_shortcodes($content);
	}
	if ( array_key_exists( 'else', $atts ) ) {
		return do_shortcodes($atts['else']);
	}
	return '';
} );

