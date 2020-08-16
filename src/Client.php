<?php
/**
 * Created by PhpStorm.
 * Project :  elemenda.
 * User: hadie MacBook
 * Date: 17/08/20
 * Time: 01.26
 */

namespace Makewpdev;


/**
 * MakeWP.dev Client
 *
 * This class is necessary to set project data
 */
class Client {
	/**
	 * The client version
	 *
	 * @var string
	 */
	public $version = '1.1.11';

	/**
	 * Hash identifier of the plugin
	 *
	 * @var string
	 */
	public $hash;

	/**
	 * Name of the plugin
	 *
	 * @var string
	 */
	public $name;

	/**
	 * The plugin/theme file path
	 * @example .../wp-content/plugins/test-slug/test-slug.php
	 *
	 * @var string
	 */
	public $file;

	/**
	 * Main plugin file
	 * @example test-slug/test-slug.php
	 *
	 * @var string
	 */
	public $basename;

	/**
	 * Slug of the plugin
	 * @example test-slug
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * The project version
	 *
	 * @var string
	 */
	public $project_version;

	/**
	 * The project type
	 *
	 * @var string
	 */
	public $type;

	/**
	 * textdomain
	 *
	 * @var string
	 */
	public $textdomain;

	/**
	 * Initialize the class
	 *
	 * @param string  $hash hash of the plugin
	 * @param string  $name readable name of the plugin
	 * @param string  $file main plugin file path
	 */

	public function __construct( $hash, $name, $file ) {
		$this->hash = $hash;
		$this->name = $name;
		$this->file = $file;

		$this->set_basename_and_slug();
	}
	/**
	 * Initialize insights class
	 *
	 * @return Appsero\Insights
	 */
	public function insights() {

		if ( ! class_exists( __NAMESPACE__ . '\Insights') ) {
			require_once __DIR__ . '/Insights.php';
		}

		return new Insights( $this );
	}
}
