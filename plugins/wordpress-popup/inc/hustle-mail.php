<?php 

class Hustle_Mail{

	/**
	 * Email recipient
	 * The email address that will receive the mail
	 *
	 * @var string
	 */
	protected $recipient = '';

	/**
	 * Email message
	 *
	 * @var string
	 */
	protected $message = '';

	/**
	 * Email subject
	 *
	 * @var string
	 */
	protected $subject = '';

	/**
	 * Email 'from' address
	 *
	 * @var string
	 */
	protected $sender_email = '';

	/**
	 * Email 'from' name
	 *
	 * @var string
	 */
	protected $sender_name = '';

	/**
	 * Email headers
	 *
	 * @var array
	 */
	protected $headers = array();


	/**
	 * Main constructor
	 *
	 * @since xxx
	 * @param string $recipient The email recipient
	 * @param string $message The email message
	 * @param string $subject The email subject
	 */
	public function __construct( $recipient = '', $message = '', $subject = '' ) {
		if ( !empty( $recipient ) && filter_var( $recipient, FILTER_VALIDATE_EMAIL ) ) {
			$this->recipient = $recipient;
		}
		if ( !empty( $message ) ) {
			$this->message = $message;
		}
		if ( !empty( $subject ) ) {
			$this->subject = $subject;
		}

		$email_settings = Hustle_Module_Model::get_email_settings();
		$this->sender_email = $email_settings['sender_email_address'];
		$this->sender_name 	= $email_settings['sender_email_name'];
		$this->set_headers();
	}

	/**
	 * Set recipient
	 *
	 * @since xxx
	 * @param string $recipient The email recipient 
	 */
	public function set_recipient( $recipient ) {
		if ( filter_var( $recipient, FILTER_VALIDATE_EMAIL ) ) {
			$this->recipient = $recipient;
		}
	}

	/**
	 * Set message
	 *
	 * @since xxx
	 * @param string $message The email message
	 */
	public function set_message( $message ) {
		$this->message = $message;
	}

	/**
	 * Set headers
	 *
	 * @since xxx
	 * @param array $headers The email headers
	 */
	public function set_headers( $headers = array() ) {
		if ( !empty( $headers ) ) {
			$this->headers = $headers;
		} else {
			$this->headers = array(
				'From: ' . $this->sender_name . ' <' . $this->sender_email . '>',
				'Content-Type: text/html; charset=UTF-8'
			);
		}
	}

	/**
	 * Set sender details
	 *
	 * @since xxx
	 * @param array $sender_details - the sender details
	 * 		( 'email' => 'email', 'name' => 'name' )
	 */
	public function set_sender( $sender_details = array() ) {
		if ( !empty( $sender_details ) ) {
			$this->sender_email = $sender_details['email'];
			$this->sender_name 	= $sender_details['name'];
		}
	}

	/**
	 * Clean mail variables
	 *
	 * @since xxx
	 */
	private function clean() {
		$subject 		= stripslashes( $this->subject );
		$subject 		= wp_strip_all_tags( $subject );
		$this->subject 	= $subject;

		$message 		= stripslashes( $this->message );
		$message 		= wpautop( $message );
		$message 		= make_clickable( $message );
		$this->message 	= $message;
	}

	/**
	 * Send mail
	 *
	 * @since 3.0.5
	 * @return bool
	 */
	public function send() {
		$sent = false;
		if ( !empty( $this->recipient ) && !empty( $this->subject ) && !empty( $this->message )  ) {
			$this->clean();
			$sent = wp_mail( $this->recipient, $this->subject, $this->message, $this->headers );
		}
		return $sent;
	}

	public function process_mail() {
		return $this->send();
	}

	/**
	 * Does the process to submit the unsubscription email.
	 *
	 * @since 3.0.5
	 * @param string $email Email to be unsubscribed.
	 * @param array $modules_id IDs of the modules to which it will be unsubscribed.
	 * @return boolean
	 */
	public static function handle_unsubscription_user_email( $email, $modules_id, $referer ) {
		if( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			Opt_In_Utils::maybe_log( __METHOD__, 'The provided email address is not valid.' );
			return false;
		}

		if( ! filter_var( $referer, FILTER_VALIDATE_URL ) ) {
			Opt_In_Utils::maybe_log( __METHOD__, 'The provided referer is not valid.' );
			return false;
		}
		
		$module = Hustle_Module_Model::instance();
		$nonce = $module->create_unsubscribe_nonce( $email, $modules_id );
		if ( ! $nonce ) {
			Opt_In_Utils::maybe_log( __METHOD__, 'There was an error getting the nonce.' );
			return false;
		}

		$parsed_url = wp_parse_url( $referer, PHP_URL_QUERY );
		$concatenate = empty( $parsed_url ) ? '?' : '&' ;
		$email = apply_filters( 'hustle_unsubscribe_email_recipient', $email, $modules_id, $referer );
		$unsubscribe_url = apply_filters( 'hustle_unsubscribe_email_url',
			$referer . $concatenate . 'token=' . $nonce . '&email=' . rawurlencode( $email ), 
			$email, $modules_id, $referer
		);

		$email_settings = Hustle_Module_Model::get_unsubscribe_email_settings();
		$message = str_replace( '{hustle_unsubscribe_link}', $unsubscribe_url, $email_settings['email_body'] );
		$message = apply_filters( 'hustle_unsubscribe_email_message', $message, $unsubscribe_url, $email, $modules_id, $referer );

		$email_handler = new self( $email, $message, $email_settings['email_subject'] );
		$sent = $email_handler->process_mail();

		return $sent;
	}

}
