<?php
/*
Plugin Name: SpiritWebs Realtime Dashboard
Description: Hiển thị realtime logins và lượt truy cập từ hệ thống SpiritWebs.
Version: 1.5
Author: SpiritWebs
*/

add_action('admin_menu', 'spiritwebs_add_dashboard_page');

function spiritwebs_add_dashboard_page() {
    add_dashboard_page(
        'Realtime Activity',
        'SpiritWebs Realtime',
        'manage_options',
        'spiritwebs-realtime',
        'spiritwebs_render_realtime_dashboard'
    );
}

// Đăng ký biến ajaxurl cho JS
add_action('admin_enqueue_scripts', function () {
    wp_localize_script('jquery', 'ajaxurl', admin_url('admin-ajax.php'));
});

function spiritwebs_render_realtime_dashboard() {
    global $wpdb;
    $table = $wpdb->prefix . 'spiritwebs_logs';
    $logs = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC LIMIT 50", ARRAY_A);
    ?>
    <div class="wrap">
        <h1>🌐 SpiritWebs - Hoạt động Realtime</h1>
        <table id="spiritwebs-log-table" style="width:100%; font-family:monospace; background:#fff; border-collapse: collapse; border:1px solid #ccc;">
            <thead>
            <tr style="background:#f5f5f5;">
                <th style="border:1px solid #ccc; padding:6px;">⏰ Thời gian</th>
                <th style="border:1px solid #ccc; padding:6px;">📌 Loại</th>
                <th style="border:1px solid #ccc; padding:6px;">👤 Username</th>
                <th style="border:1px solid #ccc; padding:6px;">📧 Email</th>
                <th style="border:1px solid #ccc; padding:6px;">🌍 Site</th>
                <th style="border:1px solid #ccc; padding:6px;">📄 Path / IP</th>
            </tr>
            </thead>
            <tbody id="spiritwebs-events"></tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/phoenix@1.7.9/priv/static/phoenix.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.getElementById("spiritwebs-events");
            const logs = <?php echo json_encode($logs); ?>;

            logs.forEach(log => {
                const tr = document.createElement("tr");
            const td = (text) => {
                const el = document.createElement("td");
                el.textContent = text || "";
                el.style.border = "1px solid #ccc";
                el.style.padding = "6px";
                return el;
            };
            tr.appendChild(td(log.log_time));
            tr.appendChild(td(log.type));
            tr.appendChild(td(log.username));
            tr.appendChild(td(log.email));
            tr.appendChild(td(log.site));
            tr.appendChild(td(log.path || log.ip));
            tableBody.appendChild(tr);
        });

            const socket = new window.Phoenix.Socket("<?= SPIRIT_SOCKET_WS_URL ?>/socket");
            socket.connect();
            const channel = socket.channel("room:lobby", {});
            channel.join().receive("ok", () => {
                console.log("✅ [room:lobby] Đã kết nối.");
        });

            function insertRow({ time, type, username = "", email = "", site = "", path = "", ip = "" }) {
                const tr = document.createElement("tr");

                const td = (text) => {
                    const el = document.createElement("td");
                    el.textContent = text;
                    el.style.border = "1px solid #ccc";
                    el.style.padding = "6px";
                    return el;
                };

                tr.appendChild(td(time));
                tr.appendChild(td(type));
                tr.appendChild(td(username));
                tr.appendChild(td(email));
                tr.appendChild(td(site));
                tr.appendChild(td(path || ip));
                tableBody.prepend(tr);

                // Gửi log mới về DB
                fetch(ajaxurl, {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({
                        action: "spiritwebs_save_log",
                        type, username, email, site, path, ip, time
                    })
                });
            }

            channel.on("user_login", (payload) => {
                insertRow({ ...payload, type: "🔐 LOGIN" });
        });

            channel.on("user_active", (payload) => {
                insertRow({ ...payload, type: "🟢 ACTIVE" });
        });

            channel.on("new_visitor", (payload) => {
                insertRow({
                              type: "👁️ VISIT",
                              username: "",
                              email: "",
                              site: payload.site,
                ip: payload.ip,
                path: payload.path,
                time: new Date().toISOString()
        });
        });
        });
    </script>
    <?php
}

// ✅ AJAX handler để lưu log
add_action('wp_ajax_spiritwebs_save_log', 'spiritwebs_save_log');
function spiritwebs_save_log() {
    global $wpdb;
    $table = $wpdb->prefix . 'spiritwebs_logs';
    $data = [
        'type'     => sanitize_text_field($_POST['type']),
        'username' => sanitize_text_field($_POST['username']),
        'email'    => sanitize_email($_POST['email']),
        'site'     => sanitize_text_field($_POST['site']),
        'path'     => sanitize_text_field($_POST['path']),
        'ip'       => sanitize_text_field($_POST['ip']),
        'log_time' => sanitize_text_field($_POST['time'])
    ];
    $wpdb->insert($table, $data);
    wp_send_json_success();
}

// ✅ Tạo bảng khi activate plugin
register_activation_hook(__FILE__, 'spiritwebs_create_log_table');
function spiritwebs_create_log_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'spiritwebs_logs';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        type VARCHAR(20) NOT NULL,
        username VARCHAR(100) DEFAULT '',
        email VARCHAR(150) DEFAULT '',
        site VARCHAR(200) DEFAULT '',
        path VARCHAR(300) DEFAULT '',
        ip VARCHAR(100) DEFAULT '',
        log_time DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
