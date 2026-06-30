<?php
/*
Plugin Name: Foody Details Crawler
Description: Crawl giờ mở cửa, giá, rating & ảnh từ Foody và lưu vào 1 field JSON.
Version: 1.0
Author: SpiritWebs
*/

if (!defined('ABSPATH')) exit;

add_action('admin_menu', function() {
    add_menu_page(
        'Foody Details',
        'Foody Details',
        'manage_options',
        'foody-details',
        'foody_details_page'
    );
});

function foody_details_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'pubs';

    echo '<div class="wrap"><h1>Foody Full Info Auto Update</h1>';

    // Chỉ lấy quán chưa có foody_data
    $pubs = $wpdb->get_results("
        SELECT id, detail_url 
        FROM $table
        WHERE detail_url IS NOT NULL
          AND (foody_data IS NULL OR foody_data = '')
    ");

    if (empty($pubs)) {
        echo '<p>Không còn quán nào để crawl.</p>';
        echo '</div>';
        return;
    }

    echo '<table class="widefat fixed" style="width:90%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Detail URL</th>
                    <th>Status</th>
                </tr>
            </thead><tbody>';

    foreach ($pubs as $pub) {
        $url = 'https://www.foody.vn' . $pub->detail_url;
        $html = wp_remote_retrieve_body(wp_remote_get($url, ['timeout' => 20]));

        $data = [
            "open_time"   => foody_extract_open_time($html),
            "price_range" => foody_extract_price($html),
            "ratings"     => foody_extract_ratings($html),
            "images"      => foody_extract_images($html)
        ];

        $json = wp_json_encode($data, JSON_UNESCAPED_UNICODE);

        $updated = $wpdb->update(
            $table,
            ['foody_data' => $json],
            ['id' => $pub->id],
            ['%s'],
            ['%d']
        );

        echo "<tr>
                <td>{$pub->id}</td>
                <td>{$pub->detail_url}</td>
                <td>" . ($updated ? "OK" : "ERROR") . "</td>
             </tr>";

        sleep(1);
    }

    echo '</tbody></table></div>';
}

/* ======================================
    PARSER FUNCTIONS
====================================== */

function foody_extract_open_time($html) {
    if (preg_match('/class="itsopen"[^>]*>.*?<\/span>\s*<span>(.*?)<\/span>/', $html, $m)) {
        return trim($m[1]);
    }
    return null;
}

function foody_extract_price($html) {
    if (preg_match('/<span itemprop="priceRange">([\s\S]*?)<\/span>/', $html, $m)) {
        return strip_tags(trim($m[1]));
    }
    return null;
}

function foody_extract_ratings($html) {
    $ratings = [];

    preg_match_all('/<span class="avg-txt-highlight">([0-9.]+)<\/span>[\s\S]*?<div class="label">(.*?)<\/div>/', $html, $matches);

    if (!empty($matches[1])) {
        for ($i = 0; $i < count($matches[1]); $i++) {
            $ratings[ trim($matches[2][$i]) ] = floatval($matches[1][$i]);
        }
    }

    return $ratings;
}

function foody_extract_images($html) {
    $images = [];

    preg_match_all('/<img[^>]+src="([^"]+@resize_ss180x180)"/', $html, $m);

    if (!empty($m[1])) {
        foreach ($m[1] as $img) {
            $images[] = $img;
        }
    }

    return $images;
}
