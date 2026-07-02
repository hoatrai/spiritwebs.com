<?php
/*
Plugin Name: Phoenix Notify
Description: Gửi thông báo sản phẩm mới hoặc cập nhật từ WooCommerce tới Phoenix server.
Version: 1.4
Author: SpiritWebs
*/

if (!defined('ABSPATH')) exit;

class PhoenixNotify {
    private $phoenix_url = SPIRIT_SOCKET_URL . '/api/new_product';
    private $api_key;

    public function __construct() {
        $this->api_key = getenv('WORDPRESS_API_KEY') ?: 'keydungkhithemsanphamhienthinotificationtoapp';

        // Hook cho cả thêm mới và cập nhật
        add_action('save_post_product', [$this, 'send_product_notification'], 10, 3);
    }

    /**
     * Gửi notification khi sản phẩm được lưu
     * @param int $post_id
     * @param WP_Post $post
     * @param bool $update
     */
    public function send_product_notification($post_id, $post, $update) {
        // Tránh auto-save, revision hoặc load page bình thường
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) return;

        $product = wc_get_product($post_id);
        if (!$product) return;

        $already_sent = get_post_meta($post_id, '_phoenix_notified', true);

        // Chỉ gửi khi mới publish hoặc đang cập nhật từ trạng thái chưa gửi
        if (($update && $post->post_status === 'publish') || (!$already_sent && $post->post_status === 'publish')) {

            $data = [
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'price' => $product->get_price(),
                'images' => [],
                'updated' => $update
            ];

            $gallery_ids = $product->get_gallery_image_ids();
            foreach ($gallery_ids as $img_id) {
                $data['images'][] = wp_get_attachment_url($img_id);
            }

            $featured_img = $product->get_image_id();
            if ($featured_img) array_unshift($data['images'], wp_get_attachment_url($featured_img));

            $payload = json_encode([
                'product' => $data,
                'api_key' => $this->api_key
            ]);

            $args = [
                'body' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->api_key
                ],
                'timeout' => 15,
            ];

            wp_remote_post($this->phoenix_url, $args);

            // Đánh dấu đã gửi
            update_post_meta($post_id, '_phoenix_notified', true);
        }
    }

}

// Khởi tạo plugin
new PhoenixNotify();

add_action('woocommerce_rest_insert_product', function($product, $request, $creating){
    if($creating){
        $notify = new PhoenixNotify();
        $notify->send_product_notification($product->get_id(), get_post($product->get_id()), false);
    }
}, 10, 3);


// ---------------------
// TEST gửi thủ công
// ---------------------
/*add_action('init', function() {
    if (isset($_GET['test_phoenix'])) {
        $notify = new PhoenixNotify();
        $notify->send_product_notification(123, get_post(123), false); // thay 123 bằng ID sản phẩm thật
        die('✅ Đã gửi thử tới Phoenix');
    }
});*/
