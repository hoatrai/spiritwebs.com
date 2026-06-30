<?php
/*
Plugin Name: Ghe Massage Control
Description: API điều khiển ghế massage cho ESP32
Version: 1.0
Author: SpiritWebs
*/

if (!defined('ABSPATH')) exit;

// ===== TẠO BẢNG KHI KÍCH HOẠT =====
register_activation_hook(__FILE__, 'ghemassage_create_table');

function ghemassage_create_table() {
    global $wpdb;

    $table = $wpdb->prefix . 'ghe_control';
    $charset = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id INT PRIMARY KEY,
        status INT DEFAULT 0
    ) $charset;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    $wpdb->query("INSERT IGNORE INTO $table (id, status) VALUES (1, 0)");
}


// ===== API: LẤY TRẠNG THÁI =====
add_action('rest_api_init', function () {
    register_rest_route('ghe/v1', '/status', array(
        'methods'  => 'GET',
        'callback' => 'ghemassage_get_status',
        'permission_callback' => '__return_true'
    ));
});

function ghemassage_get_status() {
    global $wpdb;

    $table = $wpdb->prefix . 'ghe_control';
    $status = $wpdb->get_var("SELECT status FROM $table WHERE id = 1");

    return $status ? intval($status) : 0;
}


// ===== API: BẬT GHẾ =====
add_action('rest_api_init', function () {
    register_rest_route('ghe/v1', '/start', array(
        'methods'  => 'POST',
        'callback' => 'ghemassage_start',
        'permission_callback' => '__return_true'
    ));
});

function ghemassage_start() {
    global $wpdb;

    $table = $wpdb->prefix . 'ghe_control';

    $wpdb->update($table, array('status' => 1), array('id' => 1));

    return 1;
}


// ===== API: RESET =====
add_action('rest_api_init', function () {
    register_rest_route('ghe/v1', '/reset', array(
        'methods'  => 'POST',
        'callback' => 'ghemassage_reset',
        'permission_callback' => '__return_true'
    ));
});

function ghemassage_reset() {
    global $wpdb;

    $table = $wpdb->prefix . 'ghe_control';

    $wpdb->update($table, array('status' => 0), array('id' => 1));

    return 1;
}
// ===== TẠO MENU ADMIN =====
add_action('admin_menu', function() {
    add_menu_page(
        'Ghe Massage',
        'Ghe Massage',
        'manage_options',
        'ghe-massage',
        'ghemassage_admin_page',
        'dashicons-controls-play'
    );
});

function ghemassage_admin_page() {
    ?>
    <div class="wrap">
        <h1>Điều khiển Ghế Massage</h1>

        <form method="post">
            <button name="ghe_start" class="button button-primary">BẬT GHẾ</button>
            <button name="ghe_reset" class="button button-secondary">RESET</button>
        </form>
    </div>
    <?php

    global $wpdb;
    $table = $wpdb->prefix . 'ghe_control';

    if (isset($_POST['ghe_start'])) {
        $wpdb->update($table, array('status' => 1), array('id' => 1));
        echo "<p style='color:green'>Đã bật ghế</p>";
    }

    if (isset($_POST['ghe_reset'])) {
        $wpdb->update($table, array('status' => 0), array('id' => 1));
        echo "<p style='color:red'>Đã reset</p>";
    }
}