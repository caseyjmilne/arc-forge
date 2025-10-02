<?php
/**
 * Plugin deployment and update management
 */

namespace ARC\Forge\Deploy;

if (!defined('ABSPATH')) {
    exit;
}

// Load Plugin Update Checker
require_once ARC_FORGE_PATH . 'deploy/plugin-update-checker/plugin-update-checker.php';

$arcForgeUpdateChecker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    'http://arcwp.ca/release/arc-forge/latest.json',
    ARC_FORGE_FILE,
    'arc-forge'
);
