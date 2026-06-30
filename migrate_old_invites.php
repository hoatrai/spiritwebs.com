<?php
require_once __DIR__ . '/wp-load.php';
global $wpdb;

echo "<pre>";

$posts = $wpdb->get_results("
    SELECT post_id, meta_value 
    FROM {$wpdb->postmeta}
    WHERE meta_key = 'participants'
");

if (!$posts) {
    echo "❌ Không có kèo nào để migrate\n";
    exit;
}

foreach ($posts as $row) {
    $post_id = intval($row->post_id);
    $raw = maybe_unserialize($row->meta_value);

    if (!is_array($raw) || empty($raw)) {
        echo "⚠️ Skip post $post_id (participants invalid)\n";
        continue;
    }

    // Lấy creator_id
    $host_id = get_post_meta($post_id, 'creator_id', true);
    if (!$host_id) {
        echo "⚠️ Skip post $post_id (no creator_id)\n";
        continue;
    }

    // slots → max_people
    $slots = get_post_meta($post_id, 'slots', true);
    $max_people = intval($slots);

    // time → start_time
    $time_raw = get_post_meta($post_id, 'time', true);
    $start_time = null;
    if ($time_raw) {
        $dt = DateTime::createFromFormat('d/m/Y H:i', $time_raw);
        if ($dt) {
            $start_time = $dt->format('Y-m-d H:i:s');
        }
    }

    // Check đã có trong wp_invites chưa
    $invite_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM wp_invites WHERE product_id = %d",
        $post_id
    ));

    if (!$invite_id) {
        $wpdb->insert('wp_invites', [
            'product_id' => $post_id,
            'host_id' => $host_id,
            'status' => 'open',
            'max_people' => $max_people,
            'start_time' => $start_time,
        ]);

        $invite_id = $wpdb->insert_id;
        echo "✅ Created invite $invite_id for post $post_id\n";
    } else {
        echo "ℹ️ Invite already exists for post $post_id → ID $invite_id\n";
    }

    // Insert participants
    foreach ($raw as $p) {
        if (!isset($p['user_id'])) continue;

        $user_id = intval($p['user_id']);
        $role = $p['role'] ?? 'member';
        $joined_at = $p['joined_at'] ?? current_time('mysql');

        // Check trùng
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM wp_invite_participants WHERE invite_id = %d AND user_id = %d",
            $invite_id, $user_id
        ));

        if ($exists) {
            echo "   ↪ User $user_id đã tồn tại trong invite $invite_id\n";
            continue;
        }

        $wpdb->insert('wp_invite_participants', [
            'invite_id' => $invite_id,
            'user_id' => $user_id,
            'role' => $role,
            'status' => 'joined',
            'joined_at' => $joined_at,
        ]);

        echo "   ➕ Added user $user_id ($role) to invite $invite_id\n";
    }
}

echo "\n🔥 MIGRATE DONE\n";
echo "</pre>";
