<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Hustle Providers
 */
class Hustle_Providers{	
	
	/**
	 * Instance of Hustle Providers.
	 * 
	 * @since 3.0.5
	 * @var self
	 */
	private static $instance = null;
	
	/**
	 * Whether Hustle Providers class is instantiated.
	 * 
	 * @since 3.0.5
	 * @var bool
	 */
	private static $_is_instantiated = false;
	
	/**
	 * Returns the existing instance of Hustle_Providers, or creates a new one if none exists.
	 * 
	 * @since 3.0.5
	 * @return Hustle_Providers
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/**
	 * Returns whether Hustle Providers class is instantiated.
	 * 
	 * @since 3.0.5
	 * @return bool
	 */
	public static function is_instantiated() {
		return self::$_is_instantiated;
	}
	
	/**
	 * Container for all the instantiated providers.
	 * 
	 * @since 3.0.5
	 * @var Hustle_Provider_Container
	 */
	private $providers;
	
	
	public function __construct() {
		self::$_is_instantiated = true;
		$this->providers = new Hustle_Provider_Container();
	}
	
	/**
	 * Registers a new Provider.
	 * Created just to avoid third parties having to use Hustle_Providers::get_instance().
	 *
	 * @since 3.0.5
	 * @param Hustle_Provider_Abstract|string $class_name instance of Provider or its classname.
	 * @return bool True if the provider was successfully instantiated and registered. False otherwise.
	 */
	public static function register_provider( $class_name ) {
		return self::get_instance()->register( $class_name );
	}

	/**
	 * Registers a new Provider.
	 *
	 * @since 3.0.5
	 * @param Hustle_Provider_Abstract|string $class_name instance of Provider or its classname.
	 * @return bool True if the provider was successfully instantiated and registered. False otherwise.
	 */
	public function register( $class_name ) {
		try {
			/**
			 * Fires when a provider is registered.
			 *
			 * This action is executed before the whole process of registering a provider.
			 * Validation and requirement check has not been done at this point,
			 * so it's possible that a registered class ends up not being instantiated nor registered
			 * at the end of the process when the validation of the requirements fails.
			 *
			 * @since 3.0.5
			 * @param Hustle_Provider_Abstract|string $class_name instance of Provider or its class name
			 * @return bool True if the provider was registered. False otherwise.
			 */
			do_action( 'hustle_before_provider_registered', $class_name );
			
			if ( $class_name instanceof Hustle_Provider_Abstract ) {
				$provider_class = $class_name;
			} else {
				$provider_class = $this->validate_provider_class( $class_name );
				if ( ! $provider_class ) {
					return false;
				}
			}
			$registered_providers = $this->providers;

			/**
			 * Filter provider instance.
			 *
			 * It's possible to replace / modify the provider instance when it's registered.
			 * Keep in mind that the instance returned by this filter will be used throughout the plugin.
			 * Return must be instance of @see Hustle_Provider_Abstract.
			 * It will be then validated by @see Hustle_Providers::validate_provider_instance().
			 *
			 * @since 3.0.5
			 * @param Hustle_Provider_Abstract $provider_class Current Provider class instance
			 * @param array $registered_providers Current registered providers
			 */
			
			$provider_class = apply_filters( 'hustle_provider_instance', $provider_class, $registered_providers );
			
			$provider_class = $this->validate_provider_instance( $provider_class );

			$this->providers[ $provider_class->get_slug() ] = $provider_class;

			/**
			 * Fires after the provider is successfully registered.
			 *
			 * If the provider is not registered because any reason,
			 * this action will not be executed.
			 *
			 * @since 3.0.5
			 * @param Hustle_Provider_Abstract $provider_class Current provider that's successfully registered
			 */
			do_action( 'hustle_after_provider_registered', $provider_class );
			
			return true;
		} catch ( Exception $e ) {
			Hustle_Api_Utils::maybe_log( __METHOD__, $class_name, $e->getMessage() );
			return false;
		}

	}

	/**
	 * Validates provider by its class name.
	 * Validation will fail if:
	 * -The class name passed on $class_name does not exist.
	 * -The provider doesn't have a callable 'get_instance' method. It's properly defined by default on @see Hustle_Provider_Abstract.
	 * -The provider doesn't have a callable 'check_is_compatible' method. It's properly defined by default on @see Hustle_Provider_Abstract.
	 * -The provider's 'check_is_compatible' returns false.
	 * 
	 * @since 3.0.5
	 * @param string $class_name
	 * @return Hustle_Provider_Abstract
	 * @throws Exception
	 */
	private function validate_provider_class( $class_name ) {
		if ( ! class_exists( $class_name ) ) {
			throw new Exception( 'Provider with ' . $class_name . ' does not exist' );
		}

		if ( ! is_callable( array( $class_name, 'get_instance' ) ) ) {
			throw new Exception( 'Provider with ' . $class_name . ' does not have get_instance method' );
		}

		if ( ! is_callable( array( $class_name, 'check_is_compatible' ) ) ) {
			throw new Exception( 'Provider with ' . $class_name . ' does not have check_is_compatible method' );
		}

		if ( ! call_user_func( array( $class_name, 'check_is_compatible' ), $class_name ) ) {
			return false;
		}

		$provider_class = call_user_func( array( $class_name, 'get_instance' ), $class_name );

		return $provider_class;

	} 

	/**
	 * Validates the provider instance.
	 * Validation will fail if the provider instance:
	 * -Is not an instance of @see Hustle_Provider_Abstract.
	 * -Doesn't have a _slug property.
	 * -Doesn't have a _title property.
	 * -Doesn't have a _version property.
	 * -Doesn't have a _class property.
	 * -Has the same slug of an existing provider.
	 * 
	 *
	 * @since 3.0.5
	 * @param Hustle_Provider_Abstract $instance
	 * @return Hustle_Provider_Abstract
	 * @throws Exception
	 */
	private function validate_provider_instance( Hustle_Provider_Abstract $instance ) {
		/** @var Hustle_Provider_Abstract $provider_class */
		$provider_class = $instance;
		$class_name  = get_class( $instance );

		if ( ! $provider_class instanceof Hustle_Provider_Abstract ) {
			throw new Exception( 'The provider ' . $class_name . ' is not instanceof Hustle_Provider_Abstract' );
		}
		$slug    = $provider_class->get_slug();
		$title   = $provider_class->get_title();
		$version = $provider_class->get_version();
		$class	 = $provider_class->get_class();

		if ( empty( $slug ) ) {
			throw new Exception( 'The provider ' . $class_name . ' does not have the required _slug property.' );
		}
		if ( empty( $title ) ) {
			throw new Exception( 'The provider ' . $class_name . ' does not have the required _title property.' );
		}
		if ( empty( $class ) ) {
			throw new Exception( 'The provider ' . $class_name . ' does not the required _class property.' );
		}

		// FIFO
		if ( isset( $this->providers[ $slug ] ) ) {
			throw new Exception( 'The provider with the slug ' . $slug . ' already exists.' );
		}
		if ( empty( $version ) ) {
			throw new Exception( 'Provider with the slug ' . $slug . ' does not have a valid _version property.' );
		}

		return $provider_class;
	}

	/**
	 * Gets an instace of a provider by its slug.
	 * 
	 * @param string $slug Slug of the provider to be retrieved
	 * @return Hustle_Provider_Abstract|mixed|null
	 */
	public function get_provider( $slug ) {
		if ( isset( $this->providers[ $slug ] ) ) {
			return $this->providers[ $slug ];
		}
	}

	/**
	 * Returns the container of all registered providers.
	 * Keep in mind that a provider that is successfully registered and listed here
	 * might not be included on the application if its 'check_is_activable' method returns false.
	 * 
	 * @return Hustle_Provider_Container
	 */
	public function get_providers() {
		return $this->providers;
	}
	
}
