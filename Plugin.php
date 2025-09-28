<?php
/**
 * Plugin Name: ARC Forge
 * Description: Laravel Eloquent ORM integration for WordPress - Part of the ARC Framework
 * Version: 1.0.0
 * Author: ARC Software
 * Requires PHP: 7.4
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

// Load Composer autoloader
if (file_exists(ARC_FORGE_PATH . 'vendor/autoload.php')) {
    require_once ARC_FORGE_PATH . 'vendor/autoload.php';
} else {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p><strong>ARC Forge:</strong> Run <code>composer install</code> in the plugin directory.</p></div>';
    });
    return;
}

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
        add_action('plugins_loaded', [$this, 'bootEloquent']);
        add_action('plugins_loaded', [$this, 'loadModels'], 20);
        register_activation_hook(ARC_FORGE_FILE, [$this, 'activate']);
        register_deactivation_hook(ARC_FORGE_FILE, [$this, 'deactivate']);
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
        
        $this->capsule->addConnection([
            'driver' => 'mysql',
            'port' => 10019,
            'host' => DB_HOST,
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

    public function loadModels()
    {
        $models_dir = ARC_FORGE_PATH . 'models/';
        
        if (is_dir($models_dir)) {
            foreach (glob($models_dir . '*.php') as $model_file) {
                require_once $model_file;
            }
        }

        do_action('arc_forge_models_loaded');
    }

    public function getCapsule()
    {
        return $this->capsule;
    }

    public function activate()
    {
        // Activation logic here
        $this->bootEloquent();
        do_action('arc_forge_activated');
    }

    public function deactivate()
    {
        // Deactivation logic here
        do_action('arc_forge_deactivated');
    }
}

// Initialize plugin
Plugin::getInstance();

// Helper function to get Capsule instance
function arc_db()
{
    return Plugin::getInstance()->getCapsule();
}