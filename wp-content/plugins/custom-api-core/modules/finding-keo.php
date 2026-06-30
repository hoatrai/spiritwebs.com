<?php
if (!defined('ABSPATH')) exit;

add_action('rest_api_init', function () {

    register_rest_route('custom/v1', '/finding-keo/on', [
        'methods' => 'POST',
        'callback' => 'finding_keo_on',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('custom/v1', '/finding-keo/off', [
        'methods' => 'POST',
        'callback' => 'finding_keo_off',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('custom/v1', '/finding-keo/update-location', [
        'methods' => 'POST',
        'callback' => 'finding_keo_update_location',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('custom/v1', '/finding-keo/status', [
        'methods' => 'GET',
        'callback' => 'finding_keo_status',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('custom/v1', '/finding-keo/nearby', [
        'methods' => 'GET',
        'callback' => 'finding_keo_nearby',
        'permission_callback' => '__return_true',
    ]);
});


function finding_keo_on($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'finding_keo';

    $body     = json_decode($request->get_body(), true);

    $user_id  = intval($body['user_id'] ?? 0);
    $lat      = floatval($body['lat'] ?? 0);
    $lng      = floatval($body['lng'] ?? 0);
    $district = sanitize_text_field($body['district'] ?? '');
    $option   = sanitize_text_field($body['finding_option'] ?? '');
    $activity = sanitize_text_field($body['activity_type'] ?? '');


    if (!$user_id || !$lat || !$lng || empty($district)) {
        return [
            'success' => false,
            'message' => 'Invalid request data',
            'debug'   => [
                'user_id'       => $user_id,
                'lat'           => $lat,
                'lng'           => $lng,
                'district'      => $district,
                'option'        => $option,
                'activity_type' => $activity,
            ]
        ];
    }

    update_user_meta($user_id, 'last_active', time());

    // ← Tính expire theo finding_option
    $hours = 2;
    switch ($option) {
        case 'Bây giờ':    $hours = 1; break;
        case 'Buổi trưa':  $hours = 3; break;
        case 'Buổi chiều': $hours = 4; break;
        case 'Buổi tối':   $hours = 4; break;
        case 'Khuya':      $hours = 3; break;
    }

    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table WHERE user_id = %d",
        $user_id
    ));

    $data = [
        'lat'            => $lat,
        'lng'            => $lng,
        'district'       => $district,
        'finding_option' => $option,
        'activity_type'  => $activity,
        'is_finding'     => 1,
        'updated_at'     => current_time('mysql'),
        'started_at'     => current_time('mysql'),
        'expire_at'      => date('Y-m-d H:i:s', strtotime("+{$hours} hours")),
    ];

    if ($exists) {
        $wpdb->update($table, $data, ['user_id' => $user_id]);
    } else {
        $data['user_id'] = $user_id;
        $wpdb->insert($table, $data);
    }

    // 🆕 THÊM VÀO ĐÂY — sau khi lưu DB xong
    $user       = get_userdata($user_id);
    $meta_avatar = get_user_meta($user_id, 'avatar_url', true);
    $avatar_url  = $meta_avatar ?: get_avatar_url($user_id);

    wp_remote_post('https://socket.spiritwebs.com/api/finding-keo/on', [
        'method'  => 'POST',
        'headers' => [
            'Content-Type' => 'application/json',
            'x-api-key'    => 'keydungkhithemsanphamhienthinotificationtoapp',
        ],
        'body'    => json_encode([
            'user_id'        => (string) $user_id,
            'username'       => $user->display_name,
            'avatar'         => $avatar_url,
            'district'       => $district,
            'activity_type'  => $activity,
            'finding_option' => $option,
            'lat'            => $lat,
            'lng'            => $lng,
        ]),
        'timeout' => 5,
    ]);
    // 🆕 KẾT THÚC THÊM MỚI

    return [
        'success'       => true,
        'district'      => $district,
        'activity_type' => $activity,
    ];
}


function finding_keo_off($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'finding_keo';

    $user_id = intval($request['user_id']);
    if (!$user_id) return ['success' => false];

    $wpdb->update($table, [
        'is_finding' => 0,
        'updated_at' => current_time('mysql')
    ], ['user_id' => $user_id]);

    // 🆕 THÊM VÀO ĐÂY — sau khi update DB xong
    wp_remote_post('https://socket.spiritwebs.com/api/finding-keo/off', [
        'method'  => 'POST',
        'headers' => [
            'Content-Type' => 'application/json',
            'x-api-key'    => 'keydungkhithemsanphamhienthinotificationtoapp',
        ],
        'body'    => json_encode([
            'user_id' => (string) $user_id,
        ]),
        'timeout' => 5,
    ]);
    // 🆕 KẾT THÚC THÊM MỚI

    return ['success' => true];
}


function finding_keo_update_location($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'finding_keo';

    $user_id  = intval($request['user_id']);
    $lat      = floatval($request['lat']);
    $lng      = floatval($request['lng']);
    $district = sanitize_text_field($request['district']);

    if (!$user_id) return ['success' => false];

    $wpdb->update($table, [
        'lat'        => $lat,
        'lng'        => $lng,
        'district'   => $district,
        'updated_at' => current_time('mysql')
    ], ['user_id' => $user_id]);

    return ['success' => true];
}


function finding_keo_status($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'finding_keo';

    $user_id = intval($request['user_id']);
    if (!$user_id) return ['is_finding' => 0];

    $row = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM $table WHERE user_id = %d", $user_id),
        ARRAY_A
    );

    if (!$row) return ['is_finding' => 0];

    // Tự động tắt nếu hết giờ
    if ($row['is_finding'] == 1 && !empty($row['expire_at'])) {
        if (strtotime($row['expire_at']) < time()) {
            $wpdb->update($table, [
                'is_finding' => 0,
                'updated_at' => current_time('mysql')
            ], ['user_id' => $user_id]);

            $row['is_finding'] = 0;
        }
    }

    return $row;
}


function finding_keo_nearby($request) {
    global $wpdb;

    $table = $wpdb->prefix . 'finding_keo';
    $users = $wpdb->users;

    $district        = isset($request['district']) ? sanitize_text_field($request['district']) : '';
    $current_user_id = isset($request['current_user_id']) ? intval($request['current_user_id']) : 0;
    $activity_type   = isset($request['activity_type']) ? sanitize_text_field($request['activity_type']) : '';

    if (!$district) {
        return ['success' => false, 'message' => 'Missing district'];
    }

    if (!empty($activity_type)) {
        $sql = "
            SELECT 
                fk.*,
                u.display_name,
                u.user_login AS username,
                u.ID AS user_id
            FROM $table fk
            INNER JOIN $users u ON fk.user_id = u.ID
            WHERE fk.is_finding = 1
            AND fk.district = %s
            AND fk.user_id != %d
            AND fk.activity_type = %s
            AND fk.expire_at IS NOT NULL
AND fk.expire_at >= NOW()
            ORDER BY fk.id DESC
            LIMIT 20
        ";
        $results = $wpdb->get_results(
            $wpdb->prepare($sql, $district, $current_user_id, $activity_type),
            ARRAY_A
        );
    } else {
        $sql = "
            SELECT 
                fk.*,
                u.display_name,
                u.user_login AS username,
                u.ID AS user_id
            FROM $table fk
            INNER JOIN $users u ON fk.user_id = u.ID
            WHERE fk.is_finding = 1
            AND fk.district = %s
            AND fk.user_id != %d
            AND fk.expire_at IS NOT NULL
AND fk.expire_at >= NOW()
            ORDER BY fk.id DESC
            LIMIT 20
        ";
        $results = $wpdb->get_results(
            $wpdb->prepare($sql, $district, $current_user_id),
            ARRAY_A
        );
    }

    foreach ($results as &$row) {
        $user_id = intval($row['user_id']);

        $meta_avatar       = get_user_meta($user_id, 'avatar_url', true);
        $wp_avatar         = get_avatar_url($user_id);
        $row['avatar_url'] = $meta_avatar ?: $wp_avatar;

        $last_active      = get_user_meta($user_id, 'last_active', true);
        $row['is_online'] = ($last_active && (time() - intval($last_active) < 300)) ? 1 : 0;
    }
    unset($row);

    return ['success' => true, 'data' => $results];
}