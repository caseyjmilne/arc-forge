<?php
/**
 * Helper functions for ARC Forge
 */


if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get the Eloquent Capsule instance
 *
 * @return \Illuminate\Database\Capsule\Manager
 */
function arc_db()
{
    return Plugin::getInstance()->getCapsule();
}
