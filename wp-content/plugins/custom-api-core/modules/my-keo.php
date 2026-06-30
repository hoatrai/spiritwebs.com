<?php
if (!defined('ABSPATH')) exit;

add_action('rest_api_init', function () {
    register_rest_route('nhau/v1', '/my-keo', [
        'methods'  => 'GET',
        'callback' => 'nhau_my_keo',
        'permission_callback' => '__return_true',
    ]);
});

function nhau_my_keo() {
    global $wpdb;

    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;


    $invites = $wpdb->prefix . 'invites';
    $parts   = $wpdb->prefix . 'invite_participants';

    // ================= CURRENT (host + open)

    $current = $wpdb->get_results($wpdb->prepare("
        SELECT i.*, 0 AS joined
        FROM $invites i
        WHERE i.host_id = %d
          AND i.status = 'open'
    ", $user_id), ARRAY_A);

    // ================= JOINING (member + open)

    $joining = $wpdb->get_results($wpdb->prepare("
        SELECT i.*, 1 AS joined
        FROM $invites i
        INNER JOIN $parts p ON p.invite_id = i.id
        WHERE p.user_id = %d
          AND p.role = 'member'
          AND p.status = 'joined'
          AND i.status = 'open'
    ", $user_id), ARRAY_A);

    // ================= HISTORY (closed)

    $history = $wpdb->get_results($wpdb->prepare("
        SELECT i.*,
               IF(p.user_id IS NULL, 0, 1) AS joined
        FROM $invites i
        LEFT JOIN $parts p 
               ON p.invite_id = i.id AND p.user_id = %d
        WHERE (i.host_id = %d OR p.user_id = %d)
          AND i.status = 'closed'
    ", $user_id, $user_id, $user_id), ARRAY_A);

    // ================= Attach Woo Product (FIX CỨNG)

    $attach_product_data = function (&$rows) {

        foreach ($rows as &$r) {

            if (empty($r['product_id'])) {
                $r['product'] = null;
                continue;
            }

            $product = wc_get_product((int)$r['product_id']);
            if (!$product) {
                $r['product'] = null;
                continue;
            }

            // ✅ DÙNG DATA STORE CHUẨN CỦA WOO
            $data = $product->get_data();

            // ===== images (giống Woo REST)
            $images = [];

            if ($product->get_image_id()) {
                $images[] = [
                    'id'  => $product->get_image_id(),
                    'src' => wp_get_attachment_image_url($product->get_image_id(), 'full'),
                ];
            }

            foreach ($product->get_gallery_image_ids() as $gid) {
                $images[] = [
                    'id'  => $gid,
                    'src' => wp_get_attachment_image_url($gid, 'full'),
                ];
            }

            // ===== meta_data GIỐNG HỆT LÚC TẠO
            $meta_data = [];
            $raw_meta = get_post_meta($product->get_id());

            foreach ($raw_meta as $key => $values) {
                if (is_protected_meta($key)) continue;

                $meta_data[] = [
                    'key'   => $key,
                    'value' => maybe_unserialize($values[0]),
                ];
            }

            // ===== categories
            $categories = [];
            foreach ($product->get_category_ids() as $cid) {
                $term = get_term($cid, 'product_cat');
                if ($term) {
                    $categories[] = [
                        'id' => $term->term_id,
                        'name' => $term->name,
                        'slug' => $term->slug,
                    ];
                }
            }

            // ===== BUILD PRODUCT GIỐNG WOO REST
            $r['product'] = [
                'id' => $data['id'],
                'name' => $data['name'],
                'description' => $data['description'],
                'short_description' => $data['short_description'],
                'price' => $data['price'],
                'regular_price' => $data['regular_price'],
                'sale_price' => $data['sale_price'],
                'images' => $images,
                'categories' => $categories,
                'meta_data' => $meta_data,
            ];
        }
    };




    $attach_product_data($current);
    $attach_product_data($joining);
    $attach_product_data($history);

    return [
        'success' => true,
        'current' => array_values($current),
        'joining' => array_values($joining),
        'history' => array_values($history),
    ];
}
