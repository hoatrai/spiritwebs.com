<?php
/**
 * Masothue AUTO PAGE CRAWLER – PHP ONLY
 * Gọi: ?crawl_mst=1
 */

set_time_limit(0);
ini_set('memory_limit', '512M');

function get_html($url) {
    $res = wp_remote_get($url, [
        'timeout' => 20,
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'Accept-Language' => 'vi-VN,vi;q=0.9'
        ]
    ]);
    if (is_wp_error($res)) return false;
    return wp_remote_retrieve_body($res);
}

function crawl_province_page($province, $page) {
    $url = "https://masothue.com/tra-cuu-ma-so-thue-theo-tinh/$province?page=$page";
    $html = get_html($url);
    if (!$html) return [];

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $xp = new DOMXPath($dom);

    $nodes = $xp->query('//div[@class="tax-listing"]//a[contains(@href,"-cong-ty-")]');

    $data = [];
    foreach ($nodes as $a) {
        $href = $a->getAttribute('href');
        if (!preg_match('#^/(\d{10,14})-#', $href, $m)) continue;

        $mst = $m[1];
        $data[$mst] = [
            'tax_code' => $mst,
            'name' => trim($a->textContent),
            'link' => 'https://masothue.com' . $href
        ];
    }

    return array_values($data);
}

/* ================= RUN ================= */
add_action('init', function () {

    if (!isset($_GET['crawl_mst'])) return;

    global $wpdb;

    $province = 'ho-chi-minh-23';
    $page     = 1;
    $total    = 0;

    echo "<pre>";

    while (true) {
        echo "👉 Crawling page $page...\n";

        $list = crawl_province_page($province, $page);
        $count = count($list);

        echo "✔ Page $page: $count\n";

        if ($count === 0) {
            echo "⛔ STOP – hết dữ liệu\n";
            break;
        }

        foreach ($list as $c) {
            print_r($c);

            // lưu DB (optional)
            $wpdb->replace(
                $wpdb->prefix . 'companies',
                [
                    'tax_code'     => $c['tax_code'],
                    'company_name'=> $c['name'],
                    'source_url'  => $c['link'],
                    'last_crawled'=> current_time('mysql')
                ],
                ['%s','%s','%s','%s']
            );
        }

        $total += $count;
        $page++;

        sleep(1); // né rate limit
    }

    echo "\n🔥 TOTAL CRAWLED: $total\n";
    echo "</pre>";
    exit;
});
