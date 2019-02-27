<?php
/**
 * ActiveCampaign API implementation
 *
 * Class Hustle_Activecampaign_Api
 */
class Hustle_Activecampaign_Api {

	private $_url;
	private $_key;

	public function __construct( $url, $api_key ){
		$this->_url = trailingslashit( $url ) . 'admin/api.php';
		$this->_key = $api_key;
	}

	/**
	 * Sends request to the endpoint url with the provided $action
	 *
	 * @param string $verb
	 * @param string $action rest action
	 * @param array $args
	 * @return object|WP_Error
	 */
	private function _request( $verb = "GET", $action, $args = array() ){
		$url = $this->_url;

		$apidata = array(
			'api_action' => $action,
			'api_key' => $this->_key,
			'api_output' => 'serialize',
		);

		$url = add_query_arg( $apidata, $url );

		$request = curl_init($url); // initiate curl object
		curl_setopt($request, CURLOPT_HEADER, false); // set to 0 to eliminate header info from response
		curl_setopt($request, CURLOPT_RETURNTRANSFER, true); // Returns response data instead of TRUE(1)
		curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);


		if( array() !== $args ){
			if( "POST" === $verb ){
				curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query( array_merge( $apidata, $args ) ) );
				curl_setopt($request, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/x-www-form-urlencoded'
				));
			}else{
				$url = add_query_arg($args, $url);
				curl_setopt($request, CURLOPT_URL, $url);
			}
		}

		$response = (string)curl_exec($request); //execute curl fetch and store results in $response

		curl_close($request);

		return unserialize($response);

	}

	/**
	 * Sends rest GET request
	 *
	 * @param $action
	 * @param array $args
	 * @return array|mixed|object|WP_Error
	 */
	private function _get( $action, $args = array() ){
		return $this->_request( "GET", $action, $args );
	}

	/**
	 * Sends rest POST request
	 *
	 * @param $action
	 * @param array $args
	 * @return array|mixed|object|WP_Error
	 */
	private function _post( $action, $args = array()  ){
		return $this->_request( "POST", $action, $args );
	}

	/**
	 * Retrieves lists as array of objects
	 *
	 * @return array|WP_Error
	 */
	public function get_lists(){
		$res = $this->_get( "list_list", array(
			'ids' => 'all',
			'global_fields' => 0
		) );

		if( is_wp_error( $res ) || ! is_array( $res ) )
			return $res;

		//$res = $res;
		$res2 = array();
		foreach ($res as $key => $value) {
			if( is_numeric( $key ) ) {
				array_push($res2, $value);
			}
		}

		return $res2;
	}

	/**
	 * Get the existing forms
	 *
	 * @return array
	 */
	public function get_forms() {
		$res = $this->_get( "form_getforms" );

		if( is_wp_error( $res ) || ! is_array( $res ) )
			return $res;
		
		$res2 = array();
		foreach ( $res as $key => $value ) {
			if( is_numeric( $key ) ) {
				array_push( $res2, $value );
			}
		}
		
		return $res2;
	}

	/**
	 * Add new contact
	 *
	 * @param string $id ID of the List or Form to which the user will be subscribed to
	 * @param array $data with the subscription data
	 * @param Hustle_Module_Model $module 
	 * @param array $orig_data
	 * @param string $sign_up_to Indicates if the subscription is done to a Form or to a List
	 * 
	 * @return array|mixed|object|WP_Error
	 */
	public function subscribe( $id, array $data, Hustle_Module_Model $module, $orig_data, $sign_up_to = 'list' ){
		if ( false === $this->email_exist( $data['email'], $id, $sign_up_to ) ) {
			if ( 'list' === $sign_up_to ) {
				if ( (int) $id > 0 ) {
					$data['p'] = array( $id => $id );
					$data['status'] = array( $id => 1 );
					$res = $this->_post( 'contact_sync', $data );
				} else {
					$res = $this->_post( 'contact_add', $data );
				}
			} else {
				$data['form'] = $id;
				$res = $this->_post( 'contact_sync', $data );
			}

			if ( is_array( $res ) && isset( $res['result_code'] ) && 'SUCCESS' === $res['result_code'] ){
				return __( 'Successful subscription', Opt_In::TEXT_DOMAIN );
			} else if ( empty( $res ) ) {
				return __( 'Successful subscription', Opt_In::TEXT_DOMAIN );
			}

			if ( is_array( $res ) && isset( $res['result_code'] ) ){
				if( 'FAILED' === $res['result_code'] ){
					$orig_data['error'] = ! empty( $res['result_message'] ) ? $res['result_message'] : __( 'Unexpected error occurred.', Opt_In::TEXT_DOMAIN );
					$module->log_error( $orig_data );
				}
			}

			return $res;
		} else {
			$err = new WP_Error();
			$err->add( 'email_exist', __( 'This email address has already subscribed.', Opt_In::TEXT_DOMAIN ) );
			return $err;
		}
	}

	public function email_exist( $email, $id, $type = 'list' ) {
		$res = $this->_post( 'contact_view_email', array( 'email' => $email ) );

		// See if duplicate exists.
		if (
			! empty( $res )
			&& ! empty( $res['id'])
			&& !empty($res['lists'])
		) {
			if ( 'list' === $type ) {
				// Also make sure duplicate is in active list.
				foreach ($res['lists'] as $response_list) {
					if ($response_list['listid'] === $id) {
						// Duplicate exists.
						return true;
					}
				}
			} else {
				// Or active form if checking on a form
				if ( $id === $res['formid'] ) {
					return true;
				}
			}
		}

		// Otherwise assume no duplicate.
		return false;
	}

	public function add_custom_fields( $custom_fields, $list, Hustle_Module_Model $module ) {
		if ( ! empty( $custom_fields ) ) {
			foreach ( $custom_fields as $key => $label ) {
				$field_data = array(
					'title' => $label,
					'type' => 1, // We only support text type for now,
					'perstag' => $key,
					'p[' . (int) $list . ']' => (int) $list,
				);
				$res = $this->_post( 'list_field_add', $field_data );
			}
		}
	}
}
