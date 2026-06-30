<?php
/*
Plugin Name: Masothue Auto Crawler
*/

set_time_limit(0);
ini_set('memory_limit', '512M');

/* ================= HELPER ================= */

function get_html($url) {
    $cookie = sys_get_temp_dir() . '/masothue_cookie.txt';

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,

        // giả Chrome thật
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)
            AppleWebKit/537.36 (KHTML, like Gecko)
            Chrome/122.0.0.0 Safari/537.36',

        CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language: vi-VN,vi;q=0.9,en-US;q=0.8',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            'Upgrade-Insecure-Requests: 1',
            'Referer: https://masothue.com/',
        ],

        // cookie như người thật
        CURLOPT_COOKIEJAR  => $cookie,
        CURLOPT_COOKIEFILE => $cookie,

        // cloudflare hay dùng cái này
        CURLOPT_ENCODING => '',
    ]);

    $html = curl_exec($ch);

    if ($html === false) {
        error_log('CURL ERROR: ' . curl_error($ch));
    }

    curl_close($ch);
    return $html;
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

        $data[] = [
            'tax_code' => $m[1],
            'name'     => trim($a->textContent),
            'link'     => 'https://masothue.com' . $href
        ];
    }

    return $data;
}

/* ================= RUN ================= */

add_action('init', function () {

    if (!isset($_GET['crawl_mst'])) return;

    global $wpdb;

    $province = 'ho-chi-minh-23';
    $page  = 1;
    $total = 0;

    echo '<pre>';

    while (true) {
        echo "Crawl page $page...\n";

        $list = crawl_province_page($province, $page);
        if (!$list) break;

        foreach ($list as $c) {
            print_r($c);

            $wpdb->replace(
                $wpdb->prefix . 'companies',
                [
                    'tax_code'      => $c['tax_code'],
                    'company_name' => $c['name'],
                    'source_url'   => $c['link'],
                    'last_crawled' => current_time('mysql')
                ]
            );
        }

        $total += count($list);
        $page++;
        sleep(1);
    }

    echo "\nDONE – TOTAL: $total\n";
    echo '</pre>';
    exit;
});
