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
class Client
{
    /**
     * The client version
     *
     * @var string
     */
    public $version = '1.1.11';


    /**
     * UUID for identifier this site
     * @var
     */
    public $uuid;

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
     * @param string $hash hash of the plugin
     * @param string $name readable name of the plugin
     * @param string $file main plugin file path
     */

    public function __construct($hash, $name, $file)
    {
        $this->hash = $hash;
        $this->name = $name;
        $this->file = $file;


        $this->set_basename_and_slug();

        $this->uuid = $this->get_uuid();
    }

    /**
     * Initialize insights class
     *
     * @return Insights
     */
    public function insights()
    {

        if (!class_exists(__NAMESPACE__ . '\Insights')) {
            require_once __DIR__ . '/Insights.php';
        }

        return new Insights($this);
    }

    /**
     * Initialize plugin/theme updater
     *
     * @return Updater
     */
    public function updater()
    {

        if (!class_exists(__NAMESPACE__ . '\Updater')) {
            require_once __DIR__ . '/Updater.php';
        }

        return new Updater($this);
    }

    /**
     * Initialize license checker
     *
     * @return License
     */
    public function license()
    {

        if (!class_exists(__NAMESPACE__ . '\License')) {
            require_once __DIR__ . '/License.php';
        }

        return new License($this);
    }

    /**
     * API Endpoint
     *
     * @return string
     */
    public function endpoint()
    {
        $url = defined('MAKEWP_DEVELOPMENT') ? 'https://api.makewp.test:8890/v1' : 'https://api.makewp.dev/v1';
        $endpoint = apply_filters('makewpdev_endpoint', $url);

        return trailingslashit($endpoint);
    }

    /**
     * Set project basename, slug and version
     *
     * @return void
     */
    protected function set_basename_and_slug()
    {

        if (strpos($this->file, WP_CONTENT_DIR . '/themes/') === false) {

            $this->basename = plugin_basename($this->file);

            list($this->slug, $mainfile) = explode('/', $this->basename);

            require_once ABSPATH . 'wp-admin/includes/plugin.php';

            $plugin_data = get_plugin_data($this->file);

            $this->project_version = $plugin_data['Version'];
            $this->type = 'plugin';
            $this->textdomain = $this->slug;

        } else {

            $this->basename = str_replace(WP_CONTENT_DIR . '/themes/', '', $this->file);

            list($this->slug, $mainfile) = explode('/', $this->basename);

            $theme = wp_get_theme($this->slug);

            $this->project_version = $theme->version;
            $this->type = 'theme';

        }


    }


    private function get_uuid()
    {
        $identier = 'makewp_' . $this->type . '-' . $this->slug . '_uuid';
        $uuid = get_option($identier, false);
        if (!$uuid) {
            $uuid = wp_generate_uuid4();
            add_option($identier, $uuid);
        }

        return $uuid;
    }


    /**
     * Send request to remote endpoint
     *
     * @param array $params
     * @param string $route
     *
     * @return array|WP_Error   Array of results including HTTP headers or WP_Error if the request failed.
     */
    public function send_request($params, $route, $blocking = false)
    {
        $url = $this->endpoint() . $route;

        $headers = array(
            'user-agent' => 'MakeWP/' . md5(esc_url(home_url())) . ';',
            'Accept' => 'application/json',
            'Token' => $this->hash,
            'referer' => home_url()
        );

        $body = array_merge($params, array('client' => $this->version));
        $response = wp_remote_post($url, array(
            'method' => 'POST',
            'timeout' => 30,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => $blocking,
            'sslverify' => false,

            'headers' => $headers,
            'body' => $body,
            'cookies' => array()
        ));


//        if (is_array($response) && !is_wp_error($response)) {
//            update_option($this->slug . '_tracking_results', $response['body']);
//
//        } else {
//            $error = $response->get_error_messages();
//            update_option($this->slug . '_tracking_error', $error);
//            update_option($this->slug . '_tracking_data', $body);
//
//            return $error;
//        }
//        update_option($this->slug . '_tracking_url', $url);

        return $response;
    }

    /**
     * Check if the current server is localhost
     *
     * @return boolean
     */
    public function is_local_server()
    {
        return in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'));
    }

    /**
     * Translate function _e()
     */
    public function _etrans($text)
    {
        call_user_func('_e', $text, $this->textdomain);
    }

    /**
     * Translate function __()
     */
    public function __trans($text)
    {
        return call_user_func('__', $text, $this->textdomain);
    }

}
