<?php

class Opt_In_Condition_Cpt extends Opt_In_Condition_Abstract implements Opt_In_Condition_Interface {
	public function is_allowed( Hustle_Model $optin ){
		global $post;

		if ( !isset( $this->args->selected_cpts ) || empty( $this->args->selected_cpts ) ) {
			if ( !isset($this->args->filter_type) || "except" === $this->args->filter_type ) {
				return true;
			} else {
				return false;
			}
		} elseif ( in_array("all", $this->args->selected_cpts, true) ) {
			if ( !isset($this->args->filter_type) || "except" === $this->args->filter_type ) {
				return false;
			} else {
				return true;
			}
		}

		switch( $this->args->filter_type ){
			case "only":

				// handle ms_membership
				if ( in_array( $this->args->post_type, array( 'ms_membership', 'ms_membership-n' ), true ) && defined( 'MS_PLUGIN' ) ) {

					// if no membership set
					if ( empty($this->args->selected_cpts) ) {
						return true;
					} else {
						$current_user = wp_get_current_user();
						$user_meta = get_user_meta( $current_user->ID );
						if ( 0 === $current_user->ID ) {
							return false;
						} else {
							$member_allowed = false;

							if ( isset( $user_meta['ms_subscriptions'] ) && isset( $user_meta['ms_subscriptions'][0] ) ) {
								$subscriptions = unserialize($user_meta['ms_subscriptions'][0]);
								foreach( $subscriptions as $subcription ) {
									if ( in_array( $subcription->membership_id, (array) $this->args->selected_cpts, true ) ) {
										$member_allowed = true;
										break;
									}
								}
							}
							return $member_allowed;
						}
					}
				}

				// handle other custom post types
				if( !isset( $post ) || !( $post instanceof WP_Post ) || $post->post_type !== $this->args->post_type ) return false;
				return in_array( $post->ID, (array) $this->args->selected_cpts );

			case "except":

				// handle ms_membership
				if ( in_array( $this->args->post_type, array( 'ms_membership', 'ms_membership-n' ), true ) && defined( 'MS_PLUGIN' ) ) {

					// if no membership set
					if ( empty($this->args->selected_cpts) ) {
						return true;
					} else {
						$current_user = wp_get_current_user();
						$user_meta = get_user_meta( $current_user->ID );
						if ( 0 === $current_user->ID ) {
							return true;
						} else {
							$member_allowed = true;

							if ( isset( $user_meta['ms_subscriptions'] ) && isset( $user_meta['ms_subscriptions'][0] ) ) {
								$subscriptions = unserialize($user_meta['ms_subscriptions'][0]);
								foreach( $subscriptions as $subcription ) {
									if ( in_array( $subcription->membership_id, (array) $this->args->selected_cpts ) ) {
										$member_allowed = false;
										break;
									}
								}
							}
							return $member_allowed;
						}
					}
				}

				if( !isset( $post ) || !( $post instanceof WP_Post ) || $post->post_type !== $this->args->post_type ) return true;

				return !in_array( $post->ID, (array) $this->args->selected_cpts );
			default:
				return true;
		}
	}


	public function label(){
		$post_type_label = ( isset( $this->args->post_type_label ) )
			? strtolower( $this->args->post_type_label )
			: "";
		if ( isset( $this->args->selected_cpts ) && !empty( $this->args->selected_cpts ) && is_array( $this->args->selected_cpts ) ) {
			$total = count( $this->args->selected_cpts );
			switch( $this->args->filter_type ){
				case "only":
					return ( in_array("all", $this->args->selected_cpts, true) )
						? __("All ", Opt_In::TEXT_DOMAIN) . $post_type_label
						: sprintf( __('%1$d %2$s', Opt_In::TEXT_DOMAIN), $total, $post_type_label );
				case "except":
					return ( in_array("all", $this->args->selected_cpts, true) )
						? __("No ", Opt_In::TEXT_DOMAIN) . $post_type_label
						: sprintf( __('All %1$s except %2$d', Opt_In::TEXT_DOMAIN), $total, $post_type_label );

				default:
					return null;
			}
		} else {
			return ( !isset($this->args->filter_type) || "except" === $this->args->filter_type )
				? __("All ", Opt_In::TEXT_DOMAIN) . $post_type_label
				: __("No ", Opt_In::TEXT_DOMAIN) . $post_type_label;
		}
	}
}
