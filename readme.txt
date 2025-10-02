=== ARC Forge ===
Contributors: arcsoftware
Tags: eloquent, orm, laravel, database, models
Requires at least: 5.0
Tested up to: 6.3
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Laravel Eloquent ORM integration for WordPress - Part of the ARC Framework

== Description ==

ARC Forge brings the power of Laravel's Eloquent ORM to WordPress, providing a modern, elegant database abstraction layer for your WordPress plugins and themes. As part of the ARC Framework, it enables developers to write clean, expressive database queries using familiar Laravel syntax.

**Key Features:**

* **Laravel Eloquent ORM Integration** - Use the full power of Eloquent models in WordPress
* **Seamless WordPress Integration** - Works with existing WordPress database tables
* **Modern PHP Development** - Leverage modern PHP features and patterns
* **Developer Friendly** - Clean, readable code with excellent documentation
* **Part of ARC Framework** - Integrates seamlessly with other ARC components

**Perfect for developers who:**

* Want to use modern ORM patterns in WordPress
* Prefer Laravel's database abstraction over WordPress's native functions
* Need complex database relationships and queries
* Want to maintain clean, testable code

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/arc-forge` directory, or install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the 'Plugins' screen in WordPress
3. The plugin will automatically boot Eloquent and make it available globally

== Frequently Asked Questions ==

= Do I need to know Laravel to use this plugin? =

While knowledge of Laravel's Eloquent ORM is helpful, the plugin includes documentation and examples to get you started. Basic PHP knowledge is required.

= Will this conflict with existing WordPress database functions? =

No, ARC Forge works alongside WordPress's native database functions. You can use both simultaneously.

= What PHP version is required? =

PHP 7.4 or higher is required to use this plugin.

== Usage ==

After activation, you can create Eloquent models in the `models/` directory of the plugin, or use the global `arc_db()` function to access the Capsule instance directly.

Example model:
```php
use Illuminate\Database\Eloquent\Model;

class Post extends Model {
    protected $table = 'posts';
    protected $primaryKey = 'ID';
}
```

Example usage:
```php
// Get all published posts
$posts = Post::where('post_status', 'publish')->get();

// Or use the Capsule directly
$users = arc_db()->table('users')->where('user_status', 0)->get();
```

== Changelog ==

= 1.0.0 =
* Initial release
* Laravel Eloquent ORM integration
* WordPress database connection setup
* Model autoloading system
* Global helper functions

== Upgrade Notice ==

= 1.0.0 =
Initial release of ARC Forge. Make sure to run `composer install` after installation.