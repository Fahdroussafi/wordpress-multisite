<?php

namespace WPForms\Admin\Dashboard;

/**
 * Class Widget.
 *
 * @since 1.7.3
 */
abstract class Widget {

	/**
	 * Return randomly chosen one of recommended plugins.
	 *
	 * @since 1.7.3
	 *
	 * @return array
	 */
	final protected function get_recommended_plugin() {

		$plugins = [
			'google-analytics-for-wordpress/googleanalytics.php' => [
				'name' => __( 'MonsterInsights', 'wpforms-lite' ),
				'slug' => 'google-analytics-for-wordpress',
				'more' => 'https://www.monsterinsights.com/',
				'pro'  => [
					'file' => 'google-analytics-premium/googleanalytics-premium.php',
				],
			],
			'all-in-one-seo-pack/all_in_one_seo_pack.php' => [
				'name' => __( 'AIOSEO', 'wpforms-lite' ),
				'slug' => 'all-in-one-seo-pack',
				'more' => 'https://aioseo.com/',
				'pro'  => [
					'file' => 'all-in-one-seo-pack-pro/all_in_one_seo_pack.php',
				],
			],
			'coming-soon/coming-soon.php'                 => [
				'name' => __( 'SeedProd', 'wpforms-lite' ),
				'slug' => 'coming-soon',
				'more' => 'https://www.seedprod.com/',
				'pro'  => [
					'file' => 'seedprod-coming-soon-pro-5/seedprod-coming-soon-pro-5.php',
				],
			],
			'wp-mail-smtp/wp_mail_smtp.php'               => [
				'name' => __( 'WP Mail SMTP', 'wpforms-lite' ),
				'slug' => 'wp-mail-smtp',
				'more' => 'https://wpmailsmtp.com/',
				'pro'  => [
					'file' => 'wp-mail-smtp-pro/wp_mail_smtp.php',
				],
			],
		];

		$installed = get_plugins();

		foreach ( $plugins as $id => $plugin ) {

			if ( isset( $installed[ $id ] ) ) {
				unset( $plugins[ $id ] );
			}

			if ( isset( $plugin['pro']['file'], $installed[ $plugin['pro']['file'] ] ) ) {
				unset( $plugins[ $id ] );
			}
		}

		return $plugins ? $plugins[ array_rand( $plugins ) ] : [];
	}
}
