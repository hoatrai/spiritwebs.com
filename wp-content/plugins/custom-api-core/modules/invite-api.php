<?php
if (!defined('ABSPATH')) exit;

global $wpdb;

add_action('rest_api_init', function () {
    register_rest_route('nhau/v1', '/invite/by-product', [
        'methods' => 'GET',
        'callback' => 'nhau_get_invite_by_product',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('nhau/v1', '/invite/create', [
        'methods' => 'POST',
        'callback' => 'nhau_create_invite',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('nhau/v1', '/invite/detail', [
        'methods' => 'GET',
        'callback' => 'nhau_get_invite_detail',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('nhau/v1', '/invite/join', [
        'methods' => 'POST',
        'callback' => 'nhau_join_invite',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('nhau/v1', '/invite/leave', [
        'methods' => 'POST',
        'callback' => 'nhau_leave_invite',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('nhau/v1', '/invite/kick', [
        'methods' => 'POST',
        'callback' => 'nhau_kick_user',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('nhau/v1', '/invite/close', [
        'methods' => 'POST',
        'callback' => 'nhau_close_invite',
        'permission_callback' => '__return_true',
    ]);
    register_rest_route('nhau/v1', '/invite/open', [
        'methods' => 'POST',
        'callback' => 'nhau_open_invite',
        'permission_callback' => '__return_true',
    ]);
    register_rest_route('nhau/v1', '/rating/trust', [
        'methods'  => 'POST',
        'callback' => 'nhau_rating_trust',
        'permission_callback' => '__return_true',
    ]);
});

/*function nhau_create_invite($req) {
    global $wpdb;

    $user_id = get_current_user_id();
    if (!$user_id) {
        return [
            'success' => false,
            'message' => 'Chưa đăng nhập'
        ];
    }

    $product_id = intval($req['product_id'] ?? 0);
    $max_people = intval($req['max_people'] ?? 0);
    $start_time = sanitize_text_field($req['start_time'] ?? null);

    if (!$product_id) {
        return [
            'success' => false,
            'message' => 'Thiếu product_id'
        ];
    }

    $table = $wpdb->prefix . 'invites';

    $inserted = $wpdb->insert($table, [
        'product_id' => $product_id,
        'host_id' => $user_id,
        'status' => 'open',
        'max_people' => $max_people,
        'start_time' => $start_time,
        'created_at' => current_time('mysql')
    ]);

    if (!$inserted) {
        return [
            'success' => false,
            'message' => 'Không thể tạo invite',
            'db_error' => $wpdb->last_error // 👈 debug cực mạnh
        ];
    }

    $invite_id = $wpdb->insert_id;

    // Auto join host
    $wpdb->insert($wpdb->prefix . 'invite_participants', [
        'invite_id' => $invite_id,
        'user_id' => $user_id,
        'role' => 'host',
        'status' => 'joined',
        'joined_at' => current_time('mysql')
    ]);

    return [
        'success' => true,
        'message' => 'Tạo invite thành công',
        'invite_id' => $invite_id
    ];
}*/

function nhau_create_invite($req) {
    global $wpdb;

    $user_id = get_current_user_id();
    if (!$user_id) {
        return ['success' => false, 'message' => 'Chưa đăng nhập'];
    }

    // ✅ Cho phép override creator_id nếu là admin
    if (current_user_can('administrator') && !empty($req['creator_id'])) {
        $user_id = intval($req['creator_id']);
    }

    $product_id = intval($req['product_id'] ?? 0);
    $max_people = intval($req['max_people'] ?? 0);
    $start_time = sanitize_text_field($req['start_time'] ?? null);

    if (!$product_id) {
        return ['success' => false, 'message' => 'Thiếu product_id'];
    }

    $table = $wpdb->prefix . 'invites';

    $inserted = $wpdb->insert($table, [
        'product_id' => $product_id,
        'host_id'    => $user_id,  // ← giờ đúng user rồi
        'status'     => 'open',
        'max_people' => $max_people,
        'start_time' => $start_time,
        'created_at' => current_time('mysql')
    ]);

    if (!$inserted) {
        return ['success' => false, 'message' => 'Không thể tạo invite', 'db_error' => $wpdb->last_error];
    }

    $invite_id = $wpdb->insert_id;

    // Auto join host
    $wpdb->insert($wpdb->prefix . 'invite_participants', [
        'invite_id' => $invite_id,
        'user_id'   => $user_id,  // ← đúng user
        'role'      => 'host',
        'status'    => 'joined',
        'joined_at' => current_time('mysql')
    ]);

    return ['success' => true, 'message' => 'Tạo invite thành công', 'invite_id' => $invite_id];
}

function nhau_get_invite_by_product($req) {
    global $wpdb;

    $product_id = intval($req['product_id']);
    $user_id = get_current_user_id();

    if (!$product_id) {
        return ['success' => false, 'message' => 'Thiếu product_id'];
    }

    $invite = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}invites WHERE product_id = %d",
            $product_id
        )
    );

    if (!$invite) {
        return ['success' => false, 'message' => 'Không tìm thấy invite'];
    }

    // Đếm số người đã join
    $joined_count = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}invite_participants WHERE invite_id = %d AND status = 'joined'",
            $invite->id
        )
    );

    // Check user đã join chưa
    $is_joined = false;
    if ($user_id) {
        $is_joined = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->prefix}invite_participants WHERE invite_id = %d AND user_id = %d",
                    $invite->id,
                    $user_id
                )
            ) > 0;
    }

    $is_full = $joined_count >= intval($invite->max_people);

    return [
        'success' => true,
        'invite_id' => intval($invite->id),
        'status' => $invite->status,
        'is_joined' => $is_joined,
        'is_full' => $is_full,
        'joined_count' => intval($joined_count),
        'max_people' => intval($invite->max_people),
        'invite' => [
            'id' => intval($invite->id),
            'product_id' => intval($invite->product_id),
            'host_id' => intval($invite->host_id),
            'status' => $invite->status,
            'max_people' => intval($invite->max_people),
            'start_time' => $invite->start_time,
        ]
    ];
}



function nhau_get_invite_detail($req) {
    global $wpdb;

    $invite_id = intval($req['invite_id']);
    $user_id = get_current_user_id();

    $invite = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}invites WHERE id=%d",
        $invite_id
    ));

    if (!$invite) {
        return ['success' => false, 'message' => 'Invite không tồn tại'];
    }

    $members = $wpdb->get_results($wpdb->prepare(
        "SELECT 
        p.user_id, 
        p.role, 
        p.status, 
        p.attendance_status,
        u.display_name,
        um.meta_value AS trust_score
     FROM {$wpdb->prefix}invite_participants p
     JOIN {$wpdb->users} u ON p.user_id = u.ID
     LEFT JOIN {$wpdb->usermeta} um 
        ON um.user_id = u.ID 
        AND um.meta_key = 'trust_score'
     WHERE p.invite_id=%d AND p.status='joined'",
        $invite_id
    ), ARRAY_A);
    $members = array_map(function($m) {
        return [
            'user_id' => intval($m['user_id']),
            'name' => $m['display_name'],
            'role' => $m['role'],
            'status' => $m['status'],
            'attendance_status' => $m['attendance_status'] ?? 'undecided',
            'trust_score' => intval($m['trust_score'] ?? 50),
        ];
    }, $members);

    $is_joined = false;
    $my_attendance_status = null;

    if ($user_id) {
        $row = $wpdb->get_row($wpdb->prepare(
            "SELECT attendance_status 
             FROM {$wpdb->prefix}invite_participants
             WHERE invite_id=%d AND user_id=%d AND status='joined'",
            $invite_id, $user_id
        ), ARRAY_A);

        if ($row) {
            $is_joined = true;
            $my_attendance_status = $row['attendance_status'];
        }
    }

    $is_host = ($user_id && intval($invite->host_id) === intval($user_id));

    return [
        'success' => true,
        'invite' => [
            'id' => intval($invite->id),
            'host_id' => intval($invite->host_id),
            'status' => $invite->status,
            'max_people' => intval($invite->max_people),
        ],
        'members' => $members,
        'is_joined' => $is_joined,
        'is_host' => $is_host,
        'joined_count' => count($members),
        'is_full' => count($members) >= intval($invite->max_people),
        'my_attendance_status' => $my_attendance_status,
    ];
}

function nhau_join_invite($req) {
    global $wpdb;

    $user_id = get_current_user_id();
    $invite_id = intval($req['invite_id']);

    if (!$user_id) {
        return ['success' => false, 'message' => 'Chưa đăng nhập'];
    }

    $invite = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}invites WHERE id=%d",
        $invite_id
    ));

    if (!$invite) return ['success' => false, 'message' => 'Invite không tồn tại'];
    if ($invite->status !== 'open') return ['success' => false, 'message' => 'Kèo đã đóng'];

    $joined_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->prefix}invite_participants
         WHERE invite_id=%d AND status='joined'",
        $invite_id
    ));

    if ($joined_count >= $invite->max_people) {
        return ['success' => false, 'message' => 'Kèo đã đủ người'];
    }

    $table = $wpdb->prefix . 'invite_participants';

    $old = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table WHERE invite_id=%d AND user_id=%d",
        $invite_id, $user_id
    ));

    if ($old && $old->status === 'kicked') {
        return ['success' => false, 'message' => 'Bạn đã bị kick'];
    }

    if ($old && $old->status === 'joined') {
        return ['success' => false, 'message' => 'Bạn đã join rồi'];
    }

    if ($old) {
        $wpdb->update($table, [
            'status' => 'joined',
            'joined_at' => current_time('mysql')
        ], ['id' => $old->id]);
    } else {
        $wpdb->insert($table, [
            'invite_id' => $invite_id,
            'user_id' => $user_id,
            'role' => 'member',
            'status' => 'joined',
            'joined_at' => current_time('mysql')
        ]);
    }
    // xử lý realtime khi user tham gia nhậu thì hiện cho tất cả những ai có trong page detail
    $avatar = get_avatar_url($user_id);

    wp_remote_post(SPIRIT_SOCKET_URL . '/api/invite/user-joined', [
        'headers' => [
            'Content-Type' => 'application/json',
            'x-api-key'    => 'keydungkhithemsanphamhienthinotificationtoapp',
        ],
        'body' => json_encode([
            'invite_id' => $invite_id,
            'user_id'   => $user_id,
            'username'  => wp_get_current_user()->display_name,
            'avatar'    => $avatar
        ])
    ]);



    return nhau_get_invite_detail(['invite_id' => $invite_id]);
}
function nhau_leave_invite($req) {
    global $wpdb;

    $user_id = get_current_user_id();
    $invite_id = intval($req['invite_id']);

    if (!$user_id) return ['success' => false, 'message' => 'Chưa đăng nhập'];

    $invite = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}invites WHERE id=%d",
        $invite_id
    ));

    if (!$invite) return ['success' => false, 'message' => 'Invite không tồn tại'];

    if ($invite->host_id == $user_id) {
        return ['success' => false, 'message' => 'Host không thể rời kèo'];
    }

    $wpdb->update(
        $wpdb->prefix . 'invite_participants',
        ['status' => 'left'],
        ['invite_id' => $invite_id, 'user_id' => $user_id]
    );


    wp_remote_post(
        SPIRIT_SOCKET_URL . '/api/invite/user-left',
        [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key'    => 'keydungkhithemsanphamhienthinotificationtoapp',
            ],
            'body' => json_encode([
                'invite_id' => $invite_id,
                'user_id'   => $user_id,
                'username'  => wp_get_current_user()->display_name,
            ])
        ]
    );


    return nhau_get_invite_detail(['invite_id' => $invite_id]);
}
function nhau_kick_user($req) {
    global $wpdb;

    $host_id = get_current_user_id();
    $invite_id = intval($req['invite_id']);
    $target_id = intval($req['user_id']);

    $invite = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}invites WHERE id=%d",
        $invite_id
    ));

    if (!$invite) return ['success' => false, 'message' => 'Invite không tồn tại'];
    if ($invite->host_id != $host_id) return ['success' => false, 'message' => 'Không có quyền'];

    $wpdb->update(
        $wpdb->prefix . 'invite_participants',
        ['status' => 'kicked'],
        ['invite_id' => $invite_id, 'user_id' => $target_id]
    );

    $user = get_userdata($target_id);

    wp_remote_post(
        SPIRIT_SOCKET_URL . '/api/invite/user-kicked',
        [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key'    => 'keydungkhithemsanphamhienthinotificationtoapp',
            ],
            'body' => json_encode([
                'invite_id' => $invite_id,
                'user_id'   => $target_id,
                'username'  => $user ? $user->display_name : '',
            ])
        ]
    );

    return nhau_get_invite_detail(['invite_id' => $invite_id]);
}
function nhau_close_invite($req) {
    global $wpdb;

    $user_id = get_current_user_id();
    $invite_id = intval($req['invite_id']);

    $invite = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}invites WHERE id=%d",
        $invite_id
    ));

    if (!$invite) return ['success' => false, 'message' => 'Invite không tồn tại'];
    if ($invite->host_id != $user_id) return ['success' => false, 'message' => 'Không có quyền'];

    $wpdb->update(
        $wpdb->prefix . 'invites',
        ['status' => 'closed'],
        ['id' => $invite_id]
    );

    wp_remote_post(
        SPIRIT_SOCKET_URL . '/api/invite/closed',
        [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key'    => 'keydungkhithemsanphamhienthinotificationtoapp',
            ],
            'body' => json_encode([
                'invite_id' => $invite_id,
            ])
        ]
    );

    return nhau_get_invite_detail(['invite_id' => $invite_id]);
}

function nhau_open_invite($req) {
    global $wpdb;
    $user_id = get_current_user_id();
    $invite_id = intval($req['invite_id'] ?? 0);

    if (!$user_id || !$invite_id) {
        return ['success' => false, 'message' => 'Thiếu dữ liệu'];
    }

    $table = $wpdb->prefix . 'invites';

    $invite = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM $table WHERE id = %d", $invite_id),
        ARRAY_A
    );

    if (!$invite) {
        return ['success' => false, 'message' => 'Invite không tồn tại'];
    }

    if (intval($invite['host_id']) !== $user_id) {
        return ['success' => false, 'message' => 'Không có quyền'];
    }

    $wpdb->update($table,
        ['status' => 'open'],
        ['id' => $invite_id]
    );

    wp_remote_post(
        SPIRIT_SOCKET_URL . '/api/invite/opened',
        [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key'    => 'keydungkhithemsanphamhienthinotificationtoapp',
            ],
            'body' => json_encode([
                'invite_id' => $invite_id,
            ])
        ]
    );

    return ['success' => true];
}

add_action('rest_api_init', function () {
    register_rest_route('nhau/v1', '/invite/meeting-point', [
        'methods' => 'GET',
        'callback' => 'get_invite_meeting_point',
        'permission_callback' => '__return_true',
    ]);
});

function get_invite_meeting_point($req) {
    $invite_id = intval($req->get_param('invite_id'));

    $lat = get_post_meta($invite_id, 'lat', true);
    $lng = get_post_meta($invite_id, 'lng', true);

    if (!$lat || !$lng) {
        return [
            'success' => false,
            'message' => 'Chưa có tọa độ'
        ];
    }

    return [
        'success' => true,
        'lat' => $lat,
        'lng' => $lng,
    ];
}
add_action('rest_api_init', function () {
    register_rest_route('nhau/v1', '/invite/location/update', [
        'methods' => 'POST',
        'callback' => 'update_invite_location',
        'permission_callback' => '__return_true',
    ]);
});

function update_invite_location($req) {
    global $wpdb;

    $params = json_decode($req->get_body(), true);

    $invite_id = intval($params['invite_id']);
    $user_id   = get_current_user_id();
    $lat       = floatval($params['lat']);
    $lng       = floatval($params['lng']);
    $status    = sanitize_text_field($params['status']);

    if (!$invite_id || !$user_id) {
        return ['success' => false];
    }

    $table = $wpdb->prefix . 'invite_locations';

    $wpdb->replace($table, [
        'invite_id' => $invite_id,
        'user_id'   => $user_id,
        'lat'       => $lat,
        'lng'       => $lng,
        'status'    => $status,
        'updated_at' => current_time('mysql'),
    ]);

    return ['success' => true];
}
add_action('rest_api_init', function () {
    register_rest_route('nhau/v1', '/invite/location/list', [
        'methods' => 'GET',
        'callback' => 'list_invite_locations',
        'permission_callback' => '__return_true',
    ]);
});

function list_invite_locations($req) {
    global $wpdb;

    $invite_id = intval($req->get_param('invite_id'));
    if (!$invite_id) {
        return ['success' => false];
    }

    $table = $wpdb->prefix . 'invite_locations';

    $rows = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table WHERE invite_id = %d",
            $invite_id
        )
    );

    $members = [];

    foreach ($rows as $r) {
        $user = get_userdata($r->user_id);

        $members[] = [
            'user_id' => $r->user_id,
            'name'    => $user ? $user->display_name : 'Unknown',
            'avatar'  => get_avatar_url($r->user_id),
            'lat'     => $r->lat,
            'lng'     => $r->lng,
            'status'  => $r->status,
        ];
    }

    return [
        'success' => true,
        'members' => $members
    ];
}



add_action('rest_api_init', function () {
    register_rest_route('nhau/v1', '/invite/update-attendance', [
        'methods' => 'POST',
        'callback' => 'nhau_update_attendance_status',
        'permission_callback' => '__return_true',
    ]);
});

function nhau_update_attendance_status($request) {
    global $wpdb;

    $user_id = get_current_user_id();
    if (!$user_id) {
        return ['success' => false, 'message' => 'Not logged in'];
    }

    $invite_id = intval($request['invite_id']);
    $status = sanitize_text_field($request['status']);

    $allowed = ['undecided', 'going', 'on_the_way', 'late', 'not_going'];
    if (!in_array($status, $allowed)) {
        return ['success' => false, 'message' => 'Invalid status'];
    }

    $table = $wpdb->prefix . 'invite_participants';

    $updated = $wpdb->update(
        $table,
        ['attendance_status' => $status],
        ['invite_id' => $invite_id, 'user_id' => $user_id]
    );

    if ($updated === false) {
        return ['success' => false, 'message' => 'Update failed'];
    }

    wp_remote_post(
        SPIRIT_SOCKET_URL . '/api/invite/attendance-updated',
        [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key'    => 'keydungkhithemsanphamhienthinotificationtoapp',
            ],
            'body' => json_encode([
                'invite_id' => $invite_id,
                'user_id'   => $user_id,
                'status'    => $status,
            ])
        ]
    );

    return [
        'success' => true,
        'attendance_status' => $status,
    ];
}

add_action('rest_api_init', function () {
    register_rest_route('nhau/v1', '/random-images', [
        'methods'  => 'GET',
        'callback' => 'nhau_random_images_recursive',
        'permission_callback' => '__return_true',
    ]);
});

function nhau_random_images_recursive() {
    $upload_dir = wp_upload_dir();
    $year  = sanitize_text_field($_GET['year'] ?? '2026');
    $limit = intval($_GET['limit'] ?? 2);

    $base_dir = trailingslashit($upload_dir['basedir']) . $year;

    if (!is_dir($base_dir)) {
        return [
            'success' => false,
            'message' => 'Year directory not found',
        ];
    }

    // 🔍 Lấy TẤT CẢ ảnh trong mọi thư mục tháng
    $all_images = glob($base_dir . '/*/*.{jpg,jpeg,png,webp}', GLOB_BRACE);

    if (empty($all_images)) {
        return [
            'success' => false,
            'message' => 'No images found',
        ];
    }

    // 🚫 LOẠI ẢNH RESIZE CỦA WORDPRESS – CHỈ GIỮ ẢNH GỐC
    $images = array_filter($all_images, function ($file) {
        return !preg_match(
            '/-(\d+x\d+|scaled|rotated)\.(jpg|jpeg|png|webp)$/i',
            $file
        );
    });

    if (empty($images)) {
        return [
            'success' => false,
            'message' => 'No original images found',
        ];
    }

    // 🔥 Random
    shuffle($images);

    if ($limit > count($images)) {
        $limit = count($images);
    }

    $selected = array_slice($images, 0, $limit);

    $urls = array_map(function ($file) use ($upload_dir) {
        return str_replace(
            $upload_dir['basedir'],
            $upload_dir['baseurl'],
            $file
        );
    }, $selected);

    return [
        'success' => true,
        'images'  => array_values($urls),
    ];
}

// gui notification cho 1 user Firebase khi mời kèo
add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/send-invite', [
        'methods'  => 'POST',
        'callback' => 'spiritwebs_send_invite',
        'permission_callback' => '__return_true',
    ]);
});

function spiritwebs_send_invite($request) {
    $sender_id   = intval($request['sender_id']);
    $receiver_id = intval($request['receiver_id']);
    $invite_id   = intval($request['invite_id']);

    if (!$sender_id || !$receiver_id || !$invite_id) {
        return new WP_Error('invalid', 'Thiếu dữ liệu', ['status' => 400]);
    }

    $sender = get_userdata($sender_id);

    // 🔥 Gửi sang Phoenix
    $phoenix_url = SPIRIT_SOCKET_URL . '/api/send_invite';

    $payload = json_encode([
        'invite' => [
            'id'          => $invite_id,
            'keo_id'      => $invite_id,
            'sender_id'   => $sender_id,
            'receiver_id' => $receiver_id,
            'title'       => 'Lời mời kèo',
            'time'        => current_time('mysql'),
        ]
    ]);

    wp_remote_post($phoenix_url, [
        'headers' => [
            'Content-Type' => 'application/json',
            'x-api-key'    => 'keydungkhithemsanphamhienthinotificationtoapp',
        ],
        'body' => $payload,
        'timeout' => 10,
    ]);

    return [
        'success' => true,
        'message' => 'Đã gửi lời mời',
    ];
}
add_action('rest_api_init', function () {

    register_rest_route('spiritwebs/v1', '/random-pub', [
        'methods'  => 'GET',
        'callback' => 'spiritwebs_random_pub',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('nhau/v1', '/invite/viewer-join', [
        'methods' => 'POST',
        'callback' => 'nhau_viewer_join',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('nhau/v1', '/invite/viewer-leave', [
        'methods' => 'POST',
        'callback' => 'nhau_viewer_leave',
        'permission_callback' => '__return_true',
    ]);

    // =============================
    // INVITE MEDIA
    // =============================

    register_rest_route('nhau/v1', '/invite/media/add', [
        'methods' => 'POST',
        'callback' => 'nhau_add_invite_media',
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ]);

    register_rest_route('nhau/v1', '/invite/media', [
        'methods' => 'GET',
        'callback' => 'nhau_get_invite_media',
        'permission_callback' => '__return_true',
    ]);
    // =============================
// UPLOAD FILE
// =============================

    register_rest_route('nhau/v1', '/upload', [
        'methods' => 'POST',
        'callback' => 'nhau_upload_file',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('nhau/v1', '/invite/media/delete', [
        'methods' => 'POST',
        'callback' => 'nhau_delete_invite_media',
        'permission_callback' => '__return_true',
    ]);

});

function spiritwebs_random_pub() {
    global $wpdb;

    $pub = $wpdb->get_row("
SELECT 
  id,
  name,
  branch_name,
  address,
  lat,
  lng
FROM wp_pubs
WHERE status = 1
  AND lat IS NOT NULL
  AND lng IS NOT NULL
  AND lat BETWEEN 10.3 AND 11.2
  AND lng BETWEEN 106.3 AND 107.0
ORDER BY RAND()
LIMIT 1
", ARRAY_A);

    if (!$pub) {
        return [
            'success' => false,
            'error' => 'Không tìm thấy quán'
        ];
    }

    return [
        'success' => true,
        'data' => [
            'pub_name' => trim($pub['name'].' '.$pub['branch_name']),
            'address'  => $pub['address'],
            'lat'      => (float)$pub['lat'],
            'lng'      => (float)$pub['lng'],
        ]
    ];
}

function nhau_viewer_join($req) {

    $invite_id = intval($req['invite_id']);
    $user_id   = get_current_user_id();

    wp_remote_post(
        SPIRIT_SOCKET_URL . '/api/invite/viewer-join',
        [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key'    => 'keydungkhithemsanphamhienthinotificationtoapp',
            ],
            'body' => json_encode([
                'invite_id' => $invite_id,
                'user_id'   => $user_id,
            ])
        ]
    );

    return ['success' => true];
}
function nhau_viewer_leave($req) {

    $invite_id = intval($req['invite_id']);
    $user_id   = get_current_user_id();

    wp_remote_post(
        SPIRIT_SOCKET_URL . '/api/invite/viewer-leave',
        [
            'headers' => [
                'Content-Type' => 'application/json',
                'x-api-key'    => 'keydungkhithemsanphamhienthinotificationtoapp',
            ],
            'body' => json_encode([
                'invite_id' => $invite_id,
                'user_id'   => $user_id,
            ])
        ]
    );

    return ['success' => true];
}

add_action('rest_api_init', function () {

    register_rest_route('nhau/v1', '/user-stats/(?P<user_id>\d+)', [
        'methods' => 'GET',
        'callback' => 'nhau_user_stats',
        'permission_callback' => '__return_true',
    ]);

});

function nhau_user_stats($request)
{
    global $wpdb;

    $user_id = intval($request['user_id']);

    // =========================
    // KÈO ĐÃ TỔ CHỨC
    // =========================

    $total_keo = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*)
        FROM wp_invites
        WHERE host_id = %d
    ", $user_id));

    // Người từng tham gia kèo của user
    $joined_users = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(DISTINCT p.user_id)
        FROM wp_invites i
        JOIN wp_invite_participants p
        ON i.id = p.invite_id
        WHERE i.host_id = %d
        AND p.role = 'member'
    ", $user_id));

    // Tổng joined vào kèo của user
    $joined = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*)
        FROM wp_invites i
        JOIN wp_invite_participants p
        ON i.id = p.invite_id
        WHERE i.host_id = %d
        AND p.role = 'member'
        AND p.status = 'joined'
    ", $user_id));

    // Đi thật vào kèo của user
    $real = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*)
        FROM wp_invites i
        JOIN wp_invite_participants p
        ON i.id = p.invite_id
        WHERE i.host_id = %d
        AND p.role = 'member'
        AND p.attendance_status IN ('going', 'on_the_way')
    ", $user_id));

    $real_join_percent = 0;

    if ($joined > 0) {
        $real_join_percent = round(($real / $joined) * 100);
    }

    // =========================
    // THỐNG KÊ THAM GIA KÈO
    // =========================

    // Tổng số kèo user đã tham gia
    $joined_keo = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*)
        FROM wp_invite_participants
        WHERE user_id = %d
        AND role = 'member'
        AND status = 'joined'
    ", $user_id));

    // Tổng số lần đi thật
    $attendance_count = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*)
        FROM wp_invite_participants
        WHERE user_id = %d
        AND role = 'member'
        AND attendance_status IN ('going', 'on_the_way')
    ", $user_id));

    $attendance_percent = 0;

    if ($joined_keo > 0) {
        $attendance_percent = round(($attendance_count / $joined_keo) * 100);
    }
    $attendance_rate = 0;
    if ($joined_keo > 0) {
        $attendance_rate = $attendance_count / $joined_keo;
    }
    $join_quality = 0;
    if ($joined > 0) {
        $join_quality = $real / $joined;
    }
    $host_factor = 0;
    if ($total_keo > 0) {
        $host_factor = min($total_keo / 10, 1);
    }
    $trust_score =
        ($attendance_rate * 50) +
        ($join_quality * 30) +
        ($host_factor * 20);

    $trust_score = round($trust_score * 100);

    if ($trust_score > 100) $trust_score = 100;
    if ($trust_score < 0) $trust_score = 0;

    return [
        'success' => true,
        'stats' => [

            // HOST STATS
            'total_keo' => intval($total_keo),
            'joined_users' => intval($joined_users),
            'real_join_percent' => intval($real_join_percent),

            // PARTICIPANT STATS
            'joined_keo' => intval($joined_keo),
            'attendance_count' => intval($attendance_count),
            'attendance_percent' => intval($attendance_percent),
            'trust_score' => intval($trust_score),
        ]
    ];
}

function nhau_add_invite_media(WP_REST_Request $request)
{
    global $wpdb;

    $user_id = get_current_user_id();

    $invite_id = intval($request->get_param('invite_id'));
    $url       = sanitize_text_field($request->get_param('url'));
    $type      = sanitize_text_field($request->get_param('type'));

    if (!$invite_id || !$url) {
        return [
            'success' => false,
            'message' => 'Thiếu dữ liệu'
        ];
    }

    $wpdb->insert(
        $wpdb->prefix . 'invite_media',
        [
            'invite_id' => $invite_id,
            'user_id'   => $user_id,
            'type'      => $type,
            'url'       => $url,
            'created_at'=> current_time('mysql'),
        ]
    );

    return [
        'success' => true
    ];
}

function nhau_get_invite_media(WP_REST_Request $request)
{
    global $wpdb;

    $invite_id = intval($request->get_param('invite_id'));

    $items = $wpdb->get_results(
        $wpdb->prepare(
            "
            SELECT *
            FROM {$wpdb->prefix}invite_media
            WHERE invite_id = %d
            ORDER BY id DESC
            ",
            $invite_id
        ),
        ARRAY_A
    );

    return [
        'success' => true,
        'items' => $items
    ];
}
function nhau_upload_file(WP_REST_Request $request)
{
    require_once ABSPATH . 'wp-admin/includes/file.php';

    $files = $request->get_file_params();

    if (empty($files['file'])) {
        return [
            'success' => false,
            'message' => 'Không có file'
        ];
    }

    $uploaded = wp_handle_upload(
        $files['file'],
        [
            'test_form' => false,
        ]
    );

    if (isset($uploaded['error'])) {
        return [
            'success' => false,
            'message' => $uploaded['error']
        ];
    }

    return [
        'success' => true,
        'url' => $uploaded['url'],
        'file' => basename($uploaded['file'])
    ];
}


function nhau_delete_invite_media(WP_REST_Request $request)
{
    global $wpdb;

    $user_id = get_current_user_id();
    $media_id = intval($request->get_param('media_id'));

    $table = $wpdb->prefix . 'invite_media';

    $media = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table WHERE id=%d",
            $media_id
        ),
        ARRAY_A
    );

    if (!$media) {
        return [
            'success' => false,
            'message' => 'Media không tồn tại'
        ];
    }

    // Host hoặc người upload mới được xoá

    $invite_host = get_post_field(
        'post_author',
        intval($media['invite_id'])
    );

    $is_owner =
        intval($media['user_id']) === $user_id;

    $is_host =
        intval($invite_host) === $user_id;

    if (!$is_owner && !$is_host) {
        return [
            'success' => false,
            'message' => 'Không có quyền xoá'
        ];
    }

    $deleted = $wpdb->delete(
        $table,
        ['id' => $media_id],
        ['%d']
    );

    if (!$deleted) {
        return [
            'success' => false,
            'message' => 'Xoá thất bại'
        ];
    }

    return [
        'success' => true,
        'message' => 'Đã xoá'
    ];
}

function nhau_rating_trust(WP_REST_Request $req) {

    $user_id = get_current_user_id();
    $target_user_id = intval($req['user_id']);
    $score = intval($req['point']);

    if (!$user_id || !$target_user_id) {
        return new WP_REST_Response([
            'success' => false,
            'message' => 'Thiếu user'
        ], 400);
    }

    // clamp score
    if ($score < -100) $score = -100;
    if ($score > 100) $score = 100;

    // 🔥 CHECK ĐÃ VOTE CHƯA
    $vote_key = 'rated_by_' . $user_id;
    $already = get_user_meta($target_user_id, $vote_key, true);

    if ($already) {
        return new WP_REST_Response([
            'success' => false,
            'message' => 'Bạn đã đánh giá người này rồi'
        ], 403);
    }

    // current score
    $old = get_user_meta($target_user_id, 'trust_score', true);
    if ($old === '') $old = 50;

    $new = max(0, min(100, $old + $score));

    update_user_meta($target_user_id, 'trust_score', $new);

    // 🔥 mark voted
    update_user_meta($target_user_id, $vote_key, $score);

    return new WP_REST_Response([
        'success' => true,
        'old_score' => $old,
        'new_score' => $new
    ], 200);
}