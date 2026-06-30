<?php
/**
 * Plugin Name: SpiritWebs Chat Embed
 * Description: Nhúng khung chat từ Phoenix LiveView vào tất cả trang WordPress.
 * Version: 1.1
 * Author: SpiritWebs Team
 */

add_action('wp_footer', 'spiritwebs_chat_embed');

function spiritwebs_chat_embed() {
    ?>
    <style>
        #spiritwebs-chat-toggle {
            position: fixed;
            bottom: 10px;
            right: 60px;
            z-index: 9998;
            background: #000;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            text-align: center;
            line-height: 60px;
            font-size: 28px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        #spiritwebs-chat-frame {
            position: fixed;
            bottom: 90px;
            right: 5px;
            width: 400px;
            height: 460px;
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            z-index: 9999;
            border-radius: 14px;
            display: none;
        }
    </style>

    <div id="spiritwebs-chat-toggle" aria-label="Mở chat AI">💬</div>
    <iframe
            id="spiritwebs-chat-frame"
            src="https://socket.spiritwebs.com/chat"
            allow="camera; microphone"
            loading="lazy"
            sandbox="allow-scripts allow-forms allow-same-origin"
    ></iframe>

    <script>
        (function() {
            const btn = document.getElementById('spiritwebs-chat-toggle');
            const frame = document.getElementById('spiritwebs-chat-frame');

            btn.addEventListener('click', () => {
                const isHidden = frame.style.display === 'none' || frame.style.display === '';
            frame.style.display = isHidden ? 'block' : 'none';
        });
        })();
    </script>
    <?php
}
