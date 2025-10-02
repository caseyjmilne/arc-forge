<?php
/**
 * Plugin Name: ARC Forge
 * Plugin URI: https://github.com/caseyjmilne/arc-forge
 * Description: Eloquent ORM integration for WordPress - Part of the ARC Suite
 * Version: 1.0.2
 * Author: ARC Software
 * Author URI: https://arcwp.ca
 * Requires PHP: 7.4
 * Requires at least: 5.0
 * Tested up to: 6.7
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: arc-forge
 * Domain Path: /languages
 * Namespace: ARC\Forge
 */

namespace ARC\Forge;

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('ARC_FORGE_VERSION', '1.0.0');
define('ARC_FORGE_PATH', plugin_dir_path(__FILE__));
define('ARC_FORGE_URL', plugin_dir_url(__FILE__));
define('ARC_FORGE_FILE', __FILE__);

class Plugin
{
    private static $instance = null;
    private $capsule;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->init();
    }

    private function init()
    {
        // Check for required vendor autoload
        if (!file_exists(ARC_FORGE_PATH . 'vendor/autoload.php')) {
            add_action('admin_notices', [$this, 'missingDependenciesNotice']);
            return;
        }

        require_once ARC_FORGE_PATH . 'vendor/autoload.php';

        // Load helper functions
        require_once ARC_FORGE_PATH . 'includes/helpers.php';

        // Load update checker if available
        if (file_exists(ARC_FORGE_PATH . 'deploy/manage.php')) {
            require_once ARC_FORGE_PATH . 'deploy/manage.php';
        }
        
        add_action('plugins_loaded', [$this, 'loadTextDomain']);
        add_action('plugins_loaded', [$this, 'bootEloquent']);
        register_activation_hook(ARC_FORGE_FILE, [$this, 'activate']);
        register_deactivation_hook(ARC_FORGE_FILE, [$this, 'deactivate']);
    }

    public function missingDependenciesNotice()
    {
        echo '<div class="notice notice-error"><p>';
        echo '<strong>' . esc_html__('ARC Forge Error:', 'arc-forge') . '</strong> ';
        echo esc_html__('Required dependencies are missing. Please reinstall the plugin.', 'arc-forge');
        echo '</p></div>';
    }

    public function loadTextDomain()
    {
        load_plugin_textdomain(
            'arc-forge',
            false,
            dirname(plugin_basename(ARC_FORGE_FILE)) . '/languages'
        );
    }

    public function bootEloquent()
    {
        global $wpdb;
        
        $this->capsule = new \Illuminate\Database\Capsule\Manager;
        
        // Fix collation mismatch
        $collation = $wpdb->collate ?: 'utf8mb4_unicode_ci';
        if (DB_CHARSET === 'utf8' && strpos($collation, 'utf8mb4') !== false) {
            $collation = 'utf8_general_ci';
        }

        // Parse DB_HOST for port if included (e.g., localhost:3307)
        $host = DB_HOST;
        $port = 3306; // Default MySQL port
        
        if (strpos(DB_HOST, ':') !== false) {
            list($host, $port) = explode(':', DB_HOST, 2);
            $port = intval($port);
        }
        
        $this->capsule->addConnection([
            'driver' => 'mysql',
            'host' => $host,
            'port' => $port,
            'database' => DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASSWORD,
            'charset' => DB_CHARSET,
            'collation' => $collation,
            'prefix' => $wpdb->prefix,
        ]);
        
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();

        do_action('arc_forge_eloquent_booted', $this->capsule);
    }

    public function getCapsule()
    {
        return $this->capsule;
    }

    public function activate()
    {
        // Check PHP version
        if (version_compare(PHP_VERSION, '7.4', '<')) {
            deactivate_plugins(plugin_basename(ARC_FORGE_FILE));
            wp_die(
                esc_html__('ARC Forge requires PHP 7.4 or higher.', 'arc-forge'),
                esc_html__('Plugin Activation Error', 'arc-forge'),
                ['back_link' => true]
            );
        }

        $this->bootEloquent();
        do_action('arc_forge_activated');
    }

    public function deactivate()
    {
        do_action('arc_forge_deactivated');
    }
}

// Initialize plugin
Plugin::getInstance();