<?php

/**
 * Class Hustle_Provider_Container
 */
class Hustle_Provider_Container implements ArrayAccess, Countable {

	/**
	 * @since 3.0.5
	 * @var Hustle_Provider_Abstract[]
	 */
	private $providers = array();

	/**
	 * @since 3.0.5
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists( $offset ) {
		return isset( $this->providers[ $offset ] );
	}

	/**
	 * @since 3.0.5
	 * @param mixed $offset
	 * @return Hustle_Provider_Abstract|mixed|null
	 */
	public function offsetGet( $offset ) {
		if ( isset( $this->providers[ $offset ] ) ) {
			return $this->providers[ $offset ];
		}

		return null;
	}

	/**
	 * @since 3.0.5
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet( $offset, $value ) {
		$this->providers[ $offset ] = $value;
	}

	/**
	 * @since 3.0.5
	 * @param mixed $offset
	 */
	public function offsetUnset( $offset ) {
		unset( $this->providers[ $offset ] );
	}

	/**
	 * Counts the elements of the object.
	 *
	 * @link  http://php.net/manual/en/countable.count.php
	 * @since 3.0.5
	 * @return int The custom count as an integer.
	 */
	public function count() {
		return count( $this->providers );
	}

	/**
	 * Gets All registered providers' slug.
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public function get_slugs() {
		return array_keys( $this->providers );
	}

	public function to_grouped_array() {
		$providers = array();

		foreach ( $this->providers as $slug => $provider_members ) {
			// force to offsetGet
			// in case a hook is added
			$provider = $this[ $slug ];

			$providers[ $provider->get_slug() ] = $provider->to_array();
		}

		return $providers;
	}

	/**
	 * Returns a list of the registered providers containing each provider's array of properties.
	 * The data included on the provider's array is defined in @see Hustle_Provider_Abstract.
	 *
	 * @since 3.0.5
	 * @return array
	 */
	public function to_array() {
		$providers = array();
		foreach ( $this->providers as $slug => $provider_members ) {
			// force to offsetGet: enable when needed
			// in case a hook is added
			$provider = $this[ $slug ];
			$providers[ $provider->get_slug() ] = $provider->to_array();
		}
		/**
		 * Sort elements by title
		 * @since 3.0.7
		 */
		uasort( $providers, array( $this, 'helper_sort_by_title' ) );
		return $providers;
	}

	/**
	 * Private helper to sort services by name.
	 *
	 * @since 3.0.7
	 *
	 * @param array $a First array to compare.
	 * @param array $b Second array to compare.
	 * @return integer sort order
	 */
	private function helper_sort_by_title( $a, $b ) {
		if ( ! isset( $a['title'] ) || ! isset( $b['title'] ) ) {
			return 0;
		}
		return strcasecmp( $a['title'], $b['title'] );
	}
}
