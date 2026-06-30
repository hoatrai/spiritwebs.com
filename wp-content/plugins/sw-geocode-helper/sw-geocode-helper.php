<?php
/*
Plugin Name: Crawl Foody LatLng Auto
Description: Lấy lat/lng từ Foody và update trực tiếp vào DB.
Version: 1.0
Author: SpiritWebs
*/
set_time_limit(0);
ignore_user_abort(true);
ini_set('memory_limit', '1024M');


if (!defined('ABSPATH')) exit;

add_action('admin_menu', function() {
    add_menu_page(
        'Foody LatLng Auto',
        'Foody LatLng Auto',
        'manage_options',
        'foody-latlng-auto',
        'foody_latlng_auto_page'
    );
});

function foody_latlng_auto_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'pubs';

    echo '<div class="wrap"><h1>Foody Lat/Lng Auto Update</h1>';

    // Lấy tất cả quán chưa có lat/lng
    $pubs = $wpdb->get_results("
    SELECT id, detail_url 
    FROM $table 
    WHERE (lat = 0 OR lng = 0) 
      AND detail_url IS NOT NULL
");

    if (empty($pubs)) {
        echo '<p>Tất cả quán đã có tọa độ hoặc chưa có detail_url.</p>';
        echo '</div>';
        return;
    }

    echo '<table class="widefat fixed" style="width:80%;"><thead><tr><th>ID</th><th>Detail URL</th><th>Lat</th><th>Lng</th><th>Status</th></tr></thead><tbody>';

    foreach ($pubs as $pub) {
        $detailUrl = $pub->detail_url;
        $url = 'https://www.foody.vn' . $detailUrl;

        $lat = $lng = null;
        $status = '';

        $response = wp_remote_get($url, [
            'timeout' => 20,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
            ]
        ]);


        if (is_wp_error($response)) {
            $status = 'Fetch error';
        } else {
            $html = wp_remote_retrieve_body($response);
                preg_match('/<meta itemprop="latitude" content="([0-9.]+)">/', $html, $latMatch);
            preg_match('/<meta itemprop="longitude" content="([0-9.]+)">/', $html, $lngMatch);

            if (!empty($latMatch[1]) && !empty($lngMatch[1])) {
                $lat = floatval($latMatch[1]);
                $lng = floatval($lngMatch[1]);

                // Update DB
                $updated = $wpdb->update(
                    $table,
                    ['lat' => $lat, 'lng' => $lng],
                    ['id' => $pub->id],
                    ['%f', '%f'],
                    ['%d']
                );

                $status = $updated !== false ? 'Updated' : 'DB error';
            } else {
                $status = 'Lat/Lng not found';
            }
        }

        echo '<tr>';
        echo '<td>' . esc_html($pub->id) . '</td>';
        echo '<td>' . esc_html($detailUrl) . '</td>';
        echo '<td>' . esc_html($lat) . '</td>';
        echo '<td>' . esc_html($lng) . '</td>';
        echo '<td>' . esc_html($status) . '</td>';
        echo '</tr>';

        // Sleep 1s để tránh bị Foody block
        sleep(1);
    }

    echo '</tbody></table></div>';
}
