<?php

class Hustle_SShare_Model extends Hustle_Module_Model {

	const COUNTER_META_KEY = 'hustle_shares';
	const TIMESTAMP_META_KEY = 'hustle_timestamp';
	const REFRESH_OPTION_KEY = 'hustle_ss_refresh_counters';

	public static function instance() {
		return new self();
	}

	public static function get_types() {
		return array(
			'floating_social',
			'widget',
			'shortcode',
		);
	}

	public function get_sshare_content() {
		return new Hustle_SShare_Content( $this->get_settings_meta( self::KEY_CONTENT, '{}', true ), $this );
	}

	public function get_sshare_design() {
		return new Hustle_SShare_Design( $this->get_settings_meta( self::KEY_DESIGN, '{}', true ), $this );
	}

	public function get_sshare_display_settings() {
		return new Hustle_SShare_Settings( $this->get_settings_meta( self::KEY_SETTINGS, '{}', true ), $this );
	}

	public function get_sshare_display_types() {
		return new Hustle_SShare_Types( $this->get_settings_meta( self::KEY_TYPES, '{}', true ), $this );
	}

	public function log_share_stats( $page_id ) {
		$ss_col_instance = Hustle_Module_Collection::instance();
		$ss_col_instance->update_page_share($page_id);
	}

	public function is_sshare_type_active($type) {
		$settings = $this->get_sshare_display_settings()->to_array();
		if ( isset( $settings[ $type . '_enabled' ] ) && in_array( $settings[ $type . '_enabled' ], array( 'true', true ), true ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Return whether or not the requested counter type is enabled
	 *
	 * @since 3.0.3
	 *
	 * @param string $type
	 * @return boolean
	 */
	public function is_click_counter_type_enabled( $type ) {
		$content = $this->get_sshare_content()->to_array();
		if ( isset( $content['click_counter'] ) && $content['click_counter'] === $type ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Set the number of shares of each network to current $data
	 *
	 * @since 3.0.3
	 *
	 * @param array $data, integer $post_id
	 * @return array
	 */
	public function set_network_shares( $data, $post_id ) {
		if( !empty( $data['content']['social_icons'] ) ){
			$array_with_counters = $this->get_network_shares( $post_id );
			foreach( $array_with_counters as $network => $count ) {
				if ( !empty( $data['content']['social_icons'][ $network ]['counter'] )
						&& $data['content']['social_icons'][ $network ]['counter'] > $count ) {
					$count = (int)$data['content']['social_icons'][ $network ]['counter'];
				}
				$data['content']['social_icons'][$network]['native_counter'] = $this->shorten_count( $count );
			}
		}
		return $data;
	}

	/**
	 * Get the number of shares of each network from stored values, or from APIs if the stored values don't exist or expired
	 *
	 * @since 3.0.3
	 *
	 * @param integer $post_id
	 * @return array
	 */
	public function get_network_shares( $post_id ) {
		if( $this->check_if_use_stored( $post_id ) ) {
			$stored_counters = get_post_meta( $post_id, self::COUNTER_META_KEY, true );
			return $stored_counters;
		} else {
			return array();
		}
	}

	/**
	 * Check if stored values should be used. Don't use stored values if...
	 *
	 * @since 3.0.3
	 *
	 * @param integer $post_id
	 * @param bool $check_expiration_time Optional. Check expiration time or not
	 * @return bool
	 */
	public function check_if_use_stored( $post_id, $check_expiration_time = false ){
		// we don't have anything stored
		if( !get_post_meta( $post_id, self::COUNTER_META_KEY ) ) {
			return false;
		}
		// do use stored values if traffic is a crawler/bot
		if( preg_match( '/bot|crawler|ia_archiver|mediapartners-google|80legs|wget|voyager|baiduspider|curl|yahoo!|slurp/i', $_SERVER['HTTP_USER_AGENT'] ) ) {
			return true;
		}

		if ( $check_expiration_time ) {
			// the expiration time of the counter already passed
			$timestamp = intval( get_post_meta( $post_id, self::TIMESTAMP_META_KEY, true ) );
			if( 'true' === $timestamp || time() > ( $timestamp + ( 6 * 60 * 60 ) ) ) {
				return false;
			}

			// the counter hasn't beeen updated after the last time all counters were cleared
			$clear_counters_time = get_option( self::REFRESH_OPTION_KEY, false );
			if( $clear_counters_time && $timestamp < intval( $clear_counters_time ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Set the number of shares of each network in an array. Like $result[facebook] = 44
	 *
	 * @since 3.0.3
	 *
	 * @param integer $post_id
	 * @return array
	 */
	public function retrieve_network_shares( $post_id ) {
		$post_id = apply_filters( 'hustle_network_shares_post_id', $post_id );
		$current_link = get_permalink( $post_id ) ? get_permalink( $post_id ) : home_url();
		$current_link = apply_filters( 'hustle_network_shares_from_url', $current_link );
		$result = array();
		$social_networks = $this->get_networks_with_active_counter();
		if( !$social_networks ) {
			return array();
		}
		// Get array with json formatted data for each active network
		$networks_info = $this->get_networks_data_from_api( $current_link, $social_networks );

		foreach( $networks_info as $network => $response ) {
			// Get "count" from each network's response and add the "count" number to $result array
			$get_formatted_response = 'format_' . $network . '_api_response';
			if ( !is_callable( array( $this, $get_formatted_response ) ) ) {
				continue;
			}
			$result[$network] = $this->{$get_formatted_response}( $networks_info[$network] );
		}
		//set counters values for current post
		update_post_meta( $post_id, self::COUNTER_META_KEY, $result );
		// set last counter update time
		update_post_meta( $post_id, self::TIMESTAMP_META_KEY, time() );


		return $result;
	}

	/**
	 * Get the data from each network's API
	 *
	 * @since 3.0.3
	 *
	 * @param string $current_link, array $social_networks, array $options
	 * @return array
	 */
	private function get_networks_data_from_api( $current_link, $social_networks = array(), $options = array() ) {
		$result = array();
		$curl_handle = array();
		$mh = curl_multi_init();

		foreach( $social_networks as $network ) {
			$url = $this->get_network_api_link( $network, $current_link);
			if ( !$url ) {
				continue;
			}
			$curl_handle[ $network ] = curl_init();
			curl_setopt( $curl_handle[ $network ], CURLOPT_URL, $url );
			curl_setopt( $curl_handle[ $network ], CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
			curl_setopt( $curl_handle[ $network ], CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $curl_handle[ $network ], CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $curl_handle[ $network ], CURLOPT_CONNECTTIMEOUT, 5 );
			curl_multi_add_handle( $mh, $curl_handle[ $network ] );
		}

		$running = null;
		do {
			curl_multi_exec( $mh, $running );
		} while( $running > 0 );

		foreach( $curl_handle as $network => $content ) {
			$result[ $network ] = curl_multi_getcontent( $content );
			curl_multi_remove_handle( $mh, $content );
		}
		// Finish
		curl_multi_close( $mh );

		return $result;
	}

	/**
	 * Get the API URL for each network
	 *
	 * @since 3.0.3
	 *
	 * @param string $network, string $current_link
	 * @return string
	 */
	private function get_network_api_link( $network, $current_link ) {
		switch( $network ) {
			case 'facebook':
				return 'https://graph.facebook.com/?fields=og_object{likes.summary(true).limit(0)},share&id=' . $current_link;
			case 'twitter':
				// There's no official twitter api for doing this. This alternative also requires signing in https://opensharecount.com/
				return 'http://public.newsharecounts.com/count.json?url=' . $current_link;
			case 'pinterest':
				return 'https://api.pinterest.com/v1/urls/count.json?url=' . $current_link;
			case 'reddit':
				return 'https://www.reddit.com/api/info.json?url=' . $current_link ;
			case 'vkontakte':
				return 'https://vk.com/share.php?act=count&url=' . $current_link;
			default:
				return false;
		}
	}

	/**
	 * Return the active networks from the instantiated SS that also have native counters for a given URL
	 *
	 * @since 3.0.3
	 *
	 * @return array
	 */
	private function get_networks_with_active_counter() {
		$content = $this->get_sshare_content()->to_array();
		$social_icons = $content['social_icons'];
		if( !is_array( $social_icons ) ) {
			return false;
		}
		$social_networks = array_keys( $social_icons );

		// Unset disabled networks and networks that don't have counters
		foreach( $social_networks as $key => $network ) {
			if ( 'true' !== $social_icons[$network]['enabled'] || in_array( $network, array( 'google', 'linkedin' ), true ) ) {
				unset( $social_networks[$key] );
			}
		}

		return $social_networks;
	}

	/**
	 * Format a given number to display it nicely. 10K instead of 10093
	 *
	 * @since 3.0.3
	 *
	 * @param integer $count
	 * @return string
	 */
	private function shorten_count( $count ) {
		$count = intval( $count );
		if ( $count < 1000 ) {
			return $count;
		} elseif ( $count < 1000000 ) {
			return round( $count/1000, 1, PHP_ROUND_HALF_DOWN ) . __(" K", Opt_In::TEXT_DOMAIN);
		} else {
			return round( $count/1000000, 1, PHP_ROUND_HALF_DOWN ) . __(" M", Opt_In::TEXT_DOMAIN);
		}
	}

	/**
	 * Set option to trigger the refresh of the counters
	 *
	 * @since 3.0.3
	 */
	public static function refresh_all_counters() {
		update_option( self::REFRESH_OPTION_KEY, time() );
	}

	/**
	 * Format the response of each API to get the counter
	 *
	 * @since 3.0.3
	 *
	 * @param string $response
	 * @return integer
	 */
	private function format_facebook_api_response( $response ) {
		$response = json_decode( $response , true);
		$likes = !empty( $response['og_object'] ) ? intval( $response['og_object']['likes']['summary']['total_count'] ) : 0;

		if( !empty( $response['share'] ) ){
			$comments = intval( $response['share']['comment_count'] );
			$shares = intval( $response['share']['share_count'] );
		} else {
			$comments = 0;
			$shares = 0;
		}
		$total = $likes + $comments + $shares;
		return $total;
	}

	private function format_twitter_api_response( $response ) {
		$response = json_decode( $response , true);
		return isset( $response['count'] ) ? intval( $response['count'] ) : 0;
	}

	private function format_pinterest_api_response( $response ) {
		preg_match( '/^receiveCount\((.*)\)$/', $response, $match );
		if( !isset( $match[1] ) ) {
			return 0;
		}
		$response = json_decode( $match[1] , true);
		return isset( $response['count'] ) ? intval( $response['count'] ) : 0;
	}

	private function format_reddit_api_response( $response ) {
		$response = json_decode( $response , true);
		if ( !isset( $response['data']['children'] )) {
			return 0;
		}
		$data = $response['data']['children'];
		$counter = 0;
		foreach( $data as $sub ) {
			if ( !isset( $sub['data']['subreddit_subscribers'] ) ) {
				continue;
			}
			$counter = $counter + intval( $sub['data']['subreddit_subscribers'] );
		}
		return $counter;
	}

	private function format_vkontakte_api_response( $response ) {
		preg_match( '/^VK\.Share\.count\(.{1,3}(.*)\)/', $response, $match );
		if( !isset( $match[1] ) ) {
			return 0;
		}
		return intval( $match[1] );
	}

	// End of formatting functions
}
