<?php
/*
Plugin Name: Company Dashboard PRO (DB VERSION)
*/

// =======================
// API
// =======================
add_action('rest_api_init', function () {

    register_rest_route('data/v1', '/dashboard', [
        'methods' => 'GET',
        'callback' => 'cdp_get_dashboard',
        'permission_callback' => '__return_true'
    ]);

});


// =======================
// MAIN ANALYSIS
// =======================
function cdp_get_dashboard() {

    global $wpdb;

    $rows = $wpdb->get_results("
        SELECT company_name, phone, city, company_type, status, active_date
        FROM wp_companies
    ");

    if (!$rows) return ['error' => 'No data'];

    $cities = [];
    $industries = [];
    $ageBuckets = [
        '0-1 năm' => 0,
        '1-3 năm' => 0,
        '3-5 năm' => 0,
        '5+ năm' => 0
    ];
    $phoneStats = [
        'Có phone' => 0,
        'Không phone' => 0
    ];

    $leads = [];

    foreach ($rows as $r) {

        // ===== CLEAN =====
        $status = strtolower(trim($r->status));
        if (strpos($status, 'Đang hoạt động') === false) continue;

        $city = strtolower(trim($r->city));
        if (!$city) $city = 'unknown';

        if (in_array($city, ['Ha noi','ha noi'])) $city = 'Hà Nội';
        if (in_array($city, ['TP Hồ Chí Minh','ho chi minh','tp hồ chí minh'])) $city = 'HCM';

        $phone = preg_replace('/\D/', '', $r->phone);

        // ===== COUNT CITY =====
        if (!isset($cities[$city])) $cities[$city] = 0;
        $cities[$city]++;

        // ===== INDUSTRY =====
        $type = $r->company_type ?: 'unknown';
        if (!isset($industries[$type])) $industries[$type] = 0;
        $industries[$type]++;

        // ===== AGE =====
        $age = 0;
        if ($r->active_date) {
            $age = (time() - strtotime($r->active_date)) / (365*24*60*60);
        }

        if ($age <= 1) $ageBuckets['0-1 năm']++;
        elseif ($age <= 3) $ageBuckets['1-3 năm']++;
        elseif ($age <= 5) $ageBuckets['3-5 năm']++;
        else $ageBuckets['5+ năm']++;

        // ===== PHONE =====
        if (strlen($phone) >= 9) $phoneStats['Có phone']++;
        else $phoneStats['Không phone']++;

        // ===== SCORING =====
        $score = 0;

        if (strlen($phone) >= 9) $score += 50;
        if ($age <= 5) $score += 30;
        if (stripos($type, 'tnhh') !== false || stripos($type, 'cổ phần') !== false) $score += 20;
        if (in_array($city, ['HCM','Hà Nội'])) $score += 10;

        $leads[] = [
            'company_name' => $r->company_name,
            'phone' => $phone,
            'city' => $city,
            'score' => $score
        ];
    }

    // ===== SORT =====
    arsort($cities);
    arsort($industries);

    usort($leads, fn($a,$b) => $b['score'] <=> $a['score']);

    return [
        'cities' => [
            'labels' => array_slice(array_keys($cities),0,10),
            'data' => array_slice(array_values($cities),0,10)
        ],
        'industries' => [
            'labels' => array_slice(array_keys($industries),0,10),
            'data' => array_slice(array_values($industries),0,10)
        ],
        'ages' => [
            'labels' => array_keys($ageBuckets),
            'data' => array_values($ageBuckets)
        ],
        'phones' => [
            'labels' => array_keys($phoneStats),
            'data' => array_values($phoneStats)
        ],
        'top_leads' => array_slice($leads, 0, 50)
    ];
}


// =======================
// LOAD JS
// =======================
add_action('wp_enqueue_scripts', function () {

    wp_enqueue_script('chartjs','https://cdn.jsdelivr.net/npm/chart.js',[],null,true);

    wp_add_inline_script('chartjs', "
    window.addEventListener('load', async function() {

        const res = await fetch('" . site_url('/wp-json/data/v1/dashboard') . "');
        const d = await res.json();

        console.log('DASHBOARD:', d);

        function makeChart(id,type,labels,data){
            const el = document.getElementById(id);
            if(!el) return;

            new Chart(el,{
                type:type,
                data:{
                    labels:labels,
                    datasets:[{data:data}]
                }
            });
        }

        makeChart('cityChart','bar',d.cities.labels,d.cities.data);
        makeChart('industryChart','pie',d.industries.labels,d.industries.data);
        makeChart('ageChart','doughnut',d.ages.labels,d.ages.data);
        makeChart('phoneChart','bar',d.phones.labels,d.phones.data);

        // LEADS
        const leadsEl = document.getElementById('topLeads');
        if (leadsEl) {
            leadsEl.innerHTML = d.top_leads.map(c => `
                <p>
                <b>\${c.company_name}</b><br>
                \${c.phone || 'N/A'} - \${c.city}<br>
                ⭐ \${c.score}
                </p>
            `).join('');
        }

    });
    ");
});


// =======================
// SHORTCODE
// =======================
add_shortcode('company_dashboard', function () {
    return '
    <h2>📊 Top Cities</h2>
    <canvas id="cityChart"></canvas>

    <h2>🏭 Industries</h2>
    <canvas id="industryChart"></canvas>

    <h2>📅 Age Distribution</h2>
    <canvas id="ageChart"></canvas>

    <h2>📞 Phone Quality</h2>
    <canvas id="phoneChart"></canvas>

    <h2>🔥 Top Leads</h2>
    <div id="topLeads"></div>
    ';
});