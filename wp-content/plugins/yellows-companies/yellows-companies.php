<?php
/**
 * Plugin Name: Yellows Companies Grid AJAX AI Logo Modern
 * Description: Grid 4 cột, 24 công ty mỗi trang, phân trang ellipsis hiện đại, load AJAX cho shortcode, show 1 hình AI cố định, box có nền gradient.
 * Version: 2.2
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit;

// Đăng ký shortcode
add_shortcode('yellows_companies_grid', 'yellows_companies_grid_shortcode');
function yellows_companies_grid_shortcode($atts) {
    global $wpdb;

    $atts = shortcode_atts(array(
        'per_page' => 40,
    ), $atts, 'yellows_companies_grid');

    $per_page = intval($atts['per_page']);

    $output = '<div id="yellows-grid-container" data-perpage="'.$per_page.'">';
    $output .= yellows_companies_grid_get_page(1, $per_page);
    $output .= '</div>';

    // JS AJAX
    $output .= '
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const container = document.getElementById("yellows-grid-container");
        function loadPage(page) {
            const perPage = container.dataset.perpage;
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "' . admin_url('admin-ajax.php') . '", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    container.innerHTML = xhr.responseText;
                    container.scrollIntoView({behavior: "smooth"});
                }
            };
            xhr.send("action=yellows_companies_grid_ajax&page=" + page + "&per_page=" + perPage);
        }
        container.addEventListener("click", function(e) {
            if(e.target.tagName === "A" && e.target.dataset.page) {
                e.preventDefault();
                loadPage(e.target.dataset.page);
            }
        });
    });
    </script>';

    // CSS
    $output .= '
<style>

/* ===== GRID ===== */
.yellows-grid { 
    display: grid; 
    grid-template-columns: repeat(4,1fr); 
    gap: 20px; 
    margin-top:20px; 
}

/* Tablet */
@media (max-width: 1024px) {
    .yellows-grid {
        grid-template-columns: repeat(2,1fr);
    }
}

/* Mobile */
@media (max-width: 600px) {
    .yellows-grid-item {
        padding: 10px;
    }

    .yellows-grid-item img {
        width: 50px;
        height: 50px;
    }

    .yellows-grid-item h4 {
        font-size: 12px;
    }

    .yellows-grid-item a {
        font-size: 10px;
    }
}

/* ===== ITEM ===== */
.yellows-grid-item { 
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    text-align: center; 
    padding: 15px; 
    border-radius: 12px; 
    background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: 0.3s;
    color: #333;
}

.yellows-grid-item:hover { 
    transform: translateY(-5px); 
    box-shadow: 0 12px 25px rgba(0,0,0,0.2); 
    background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%);
}

/* Logo */
.yellows-grid-item img { 
    width: 80px;
    height: 80px;
    border-radius: 50%; 
    background: #fff; 
    padding: 5px; 
    object-fit: cover;
}

/* Tên công ty */
.yellows-grid-item h4 {
    font-size: 14px;
    margin: 6px 0;
    line-height: 1.4;
    word-break: break-word;
}

/* Link */
.yellows-grid-item p {
    font-size: 12px;
    margin: 0;
    width: 100%;
}

/* FIX vỡ layout link */
.yellows-grid-item a {
    display: block;
    color: #0a1a3d;
    text-decoration: none;
    word-break: break-all;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ===== MOBILE ITEM ===== */
@media (max-width: 600px) {
    .yellows-grid-item {
        padding: 12px;
    }

    .yellows-grid-item img {
        width: 60px;
        height: 60px;
    }

    .yellows-grid-item h4 {
        font-size: 13px;
    }

    .yellows-grid-item a {
        font-size: 11px;
    }
}

/* ===== PAGINATION ===== */
.yellows-pagination {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
    margin: 20px 0;
}

/* Reset nếu theme đè */
.yellows-pagination a,
.yellows-pagination span {
    all: unset;
}

/* Style lại từ đầu */
.yellows-pagination a, 
.yellows-pagination span { 
    min-width: 36px;
    height: 36px;
    padding: 0 10px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;

    background: rgba(0,0,0,0.05);
    color:#0a1a3d;

    transition: 0.3s;
}

/* Hover */
.yellows-pagination a:hover { 
    background:#0073aa; 
    color:#fff; 
}

/* Active */
.yellows-pagination .current { 
    background:#0073aa; 
    color:#fff; 
}

/* Mobile pagination */
@media (max-width: 600px) {
    .yellows-pagination a,
    .yellows-pagination span {
        min-width: 32px;
        height: 32px;
        font-size: 12px;
    }
}

</style>';

    return $output;
}

// AJAX handler
add_action('wp_ajax_yellows_companies_grid_ajax', 'yellows_companies_grid_ajax');
add_action('wp_ajax_nopriv_yellows_companies_grid_ajax', 'yellows_companies_grid_ajax');

function yellows_companies_grid_ajax() {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 24;

    echo yellows_companies_grid_get_page($page, $per_page);
    wp_die();
}

// Render grid + pagination
function yellows_companies_grid_get_page($paged, $per_page) {
    global $wpdb;
    $offset = ($paged - 1) * $per_page;
    $table_name = $wpdb->prefix . 'companies_yellowpages';

    // Chỉ lấy công ty có website
    $total_companies = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE website IS NOT NULL AND website != ''");
    $companies = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE website IS NOT NULL AND website != '' ORDER BY RAND() LIMIT %d OFFSET %d",
        $per_page, $offset
    ));

    if (!$companies) return '<p>Không có dữ liệu.</p>';

    $ai_logo = SPIRIT_WEB_URL . '/media/2026/03/19049b94-e4d5-4e89-a7bd-a56889dc62b6.png';

    $output = '<div class="yellows-grid">';
    foreach($companies as $company) {
        $output .= '<div class="yellows-grid-item">';
        $output .= '<img src="'.$ai_logo.'" style="width:80px;height:80px;margin-bottom:8px;">';
        $output .= '<h4 style="font-size:14px;margin:5px 0;">'.esc_html($company->company_name).'</h4>';
        $output .= '<p style="font-size:12px;"><a href="'.esc_url($company->website).'" target="_blank" style="color:#0a1a3d;">'.esc_html($company->website).'</a></p>';
        $output .= '</div>';
    }
    $output .= '</div>';

    $total_pages = ceil($total_companies / $per_page);
    if($total_pages > 1) $output .= yellows_companies_grid_ajax_pagination($paged, $total_pages, 2);

    return $output;
}
// Pagination
function yellows_companies_grid_ajax_pagination($paged, $total_pages, $range = 2) {
    $output = '<div class="yellows-pagination" style="text-align:center;margin-top:20px;">';

    if($paged > 1) $output .= '<a href="#" data-page="'.($paged-1).'">&laquo;</a>';

    if($paged > $range + 1) $output .= '<span>...</span>';

    for($i = max(1,$paged-$range); $i<=min($total_pages,$paged+$range); $i++) {
        if($i == $paged) $output .= '<span class="current">'.$i.'</span>';
        else $output .= '<a href="#" data-page="'.$i.'">'.$i.'</a>';
    }

    if($paged < $total_pages - $range) $output .= '<span>...</span>';

    if($paged < $total_pages) $output .= '<a href="#" data-page="'.($paged+1).'">&raquo;</a>';

    $output .= '</div>';
    return $output;
}