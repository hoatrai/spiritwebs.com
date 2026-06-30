<?php
/**
 * Plugin Name: Custom API Core
 * Description: Core API cho app
 * Version: 1.0
 * Author: Ken
 */

if (!defined('ABSPATH')) exit;

// Load modules
require_once __DIR__ . '/modules/finding-keo.php';
require_once __DIR__ . '/modules/invite-api.php'; // ← THÊM DÒNG NÀY
require_once __DIR__ . '/modules/my-keo.php';
// require_once __DIR__ . '/modules/invite.php';
