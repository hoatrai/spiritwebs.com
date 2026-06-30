<?php
/**
 * Plugin Name: Crawl Foody Show Dynamic
 * Description: Crawl Foody bằng Node.js, hiển thị và lưu dữ liệu vào database. Dynamic theo category/province/pages/type
 * Version: 2.1
 * Author: SpiritWebs
 */

add_action('admin_init', function() {
    if (isset($_GET['crawl_foody'])) {
        header('Content-Type: text/html; charset=utf-8');

        $category = $_GET['category'] ?? 'foodcourt';
        $province = $_GET['province'] ?? 'hanoi';
        $pages    = intval($_GET['pages'] ?? 30);
        $type     = intval($_GET['type'] ?? 62); // default type

        $data = wp_foody_parse_html($category, $province, $pages);

        if (!$data) {
            echo "Không tìm thấy dữ liệu trong file HTML";
        } else {
            // Bỏ những item name rỗng
            $data = array_filter($data, fn($item) => !empty(trim($item['name'])));

            echo '<h2>Data Crawl:</h2><pre>';
            print_r($data);
            echo '</pre>';

            wp_foody_save_to_db($data, $type);

            echo "<p>Đã lưu " . count($data) . " quán vào database.</p>";
        }
        exit;
    }
});

function wp_foody_save_to_db($restaurants, $type = 62) {
    global $wpdb;
    $table = $wpdb->prefix . 'pubs';

    foreach ($restaurants as $r) {
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE detail_url = %s",
            $r['detail_url']
        ));

        $data = [
            'name' => $r['name'],
            'branch_name' => '',
            'address' => $r['address'],
            'district_id' => 0,
            'city_id' => 217,
            'lat' => 0,
            'lng' => 0,
            'avg_rating' => $r['avg_rating'],
            'total_review' => 0,
            'total_view' => 0,
            'total_favourite' => 0,
            'total_checkins' => 0,
            'status' => 1,
            'is_opening' => 1,
            'picture_count' => 1,
            'type' => $type,
            'master_category_id' => 0,
            'url_rewrite_name' => sanitize_title($r['name']),
            'detail_url' => $r['detail_url'],
            'mobile_picture_path' => $r['mobile_picture_path'],
            'picture_path_large' => $r['mobile_picture_path'],
            'foody_data' => ''
        ];

        if ($exists) {
            $wpdb->update($table, $data, ['id' => $exists]);
        } else {
            $wpdb->insert($table, $data);
        }
    }
}

function wp_foody_parse_html($category, $province, $total_pages = 30) {
    $data = [];

    for ($i = 1; $i <= $total_pages; $i++) {
        $html_file = __DIR__ . "/node/{$category}_{$province}_foody_page_{$i}.html";
        if (!file_exists($html_file)) continue;

        $html = file_get_contents($html_file);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $rows = $xpath->query("//div[contains(@class,'row-item filter-result-item')]");

        foreach ($rows as $row) {
            $aNode = $xpath->query(".//div[contains(@class,'ri-avatar')]//a", $row)->item(0);
            $name = $aNode ? trim($aNode->getAttribute('title')) : '';
            $detail_url = $aNode ? $aNode->getAttribute('href') : '';

            $imgNode = $xpath->query(".//div[contains(@class,'ri-avatar')]//img", $row)->item(0);
            $mobile_picture_path = $imgNode ? $imgNode->getAttribute('src') : '';

            $addressNode = $xpath->query(".//div[contains(@class,'address')]", $row)->item(0);
            $address = $addressNode ? preg_replace('/\s+/u', ' ', trim($addressNode->textContent)) : '';

            $ratingNode = $xpath->query(".//div[contains(@class,'point')]", $row)->item(0);
            $avg_rating = $ratingNode ? floatval(trim($ratingNode->textContent)) : 0;

            $data[] = [
                'name' => $name,
                'address' => $address,
                'avg_rating' => $avg_rating,
                'detail_url' => $detail_url,
                'mobile_picture_path' => $mobile_picture_path,
            ];
        }
    }

    error_log("Found " . count($data) . " restaurants in HTML pages");
    return $data;
}
