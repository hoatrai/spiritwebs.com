<?php
/*
Plugin Name: SpiritWebs Socket Client
Description: Gửi sự kiện real-time về SpiritWebs khi người dùng đăng nhập.
Version: 1.0
Author: SpiritWebs
*/

add_action('wp_footer', 'spiritwebs_socket_client_script');

function spiritwebs_socket_client_script() {
  if (is_user_logged_in()) {
    $user = wp_get_current_user();
    ?>
    <script type="module">
      const { Socket } = await import("https://cdn.jsdelivr.net/npm/phoenix@1.7.9/priv/static/phoenix.min.js");

      const socket = new Socket("wss://<?= SPIRIT_SOCKET_HOST ?>/socket");
      socket.connect();

      const channel = socket.channel("room:lobby", {});
      channel.join().receive("ok", () => {
        channel.push("user_login", {
          site: window.location.hostname,
          username: "<?php echo esc_js($user->user_login); ?>",
          email: "<?php echo esc_js($user->user_email); ?>",
          time: new Date().toISOString()
        });
      });
    </script>
    <?php
  }
}
?>

