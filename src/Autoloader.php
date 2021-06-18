<?php
/**
 * Includes the composer Autoloader used for packages and classes in the src/ directory.
 */

namespace Mrlazyfox\Reactro;

defined( 'ABSPATH' ) || exit;

/**
 * Autoloader class.
 *
 * @since 3.7.0
 */
class Autoloader {

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Require the autoloader and return the result.
	 *
	 * If the autoloader is not present, let's log the failure and display a nice admin notice.
	 *
	 * @return boolean
	 */
	public static function init() {
		$autoloader = dirname( WR_PLUGIN_FILE ) . '/vendor/autoload.php';
		if ( ! is_readable( $autoloader ) ) {
			self::missing_autoloader();
			return false;
		}

		$autoloader_result = require $autoloader;
		if ( ! $autoloader_result ) {
			return false;
		}

		return $autoloader_result;
	}

	/**
	 * If the autoloader is missing, add an admin notice.
	 */
	protected static function missing_autoloader() {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 
				esc_html__( 'Your installation of Wordpress Reactro is incomplete. Missing Packages', WR_TEXT_DOMAIN )
				);
		}
		add_action(
			'admin_notices',
			function() {
				?>
				<div class="notice notice-error">
					<p>
						<?php
						printf(
							esc_html__( 'Your installation of Wordpress Reactro is incomplete. Missing Packages', WR_TEXT_DOMAIN ),
						);
						?>
					</p>
				</div>
				<?php
			}
		);
	}
}
