<?php
/**
 * Created by PhpStorm.
 * Project :  elemenda.
 * User: hadie MacBook
 * Date: 17/08/20
 * Time: 01.29
 */

namespace Makewpdev;


class Insights {

	/**
	 * The notice text
	 *
	 * @var string
	 */
	public $notice;

	/**
	 * Wheather to the notice or not
	 *
	 * @var boolean
	 */
	protected $show_notice = true;

	/**
	 * If extra data needs to be sent
	 *
	 * @var array
	 */
	protected $extra_data = array();

	/**
	 * Makewpdev\Client
	 *
	 * @var object
	 */
	protected $client;

	/**
	 * Initialize the class
	 *
	 * @param Makewpdev\Client
	 */
}
