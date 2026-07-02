<?php
/*
Plugin Name: WP Crypto Phoenix
Description: Hiển thị Order Book Crypto realtime + form đặt lệnh, kết nối Phoenix backend.
Version: 1.1
Author: SpiritWebs
*/

if ( ! defined( 'ABSPATH' ) ) exit;

add_shortcode('wp_crypto', function() {
    ob_start(); ?>

    <div id="crypto-app" style="font-family: Arial, sans-serif; max-width: 600px;">
        <h2>💹 Crypto Order Book</h2>
        <table id="crypto-orders" style="width:100%; border-collapse: collapse; margin-bottom:15px;">
            <thead>
            <tr style="background:#f5f5f5;">
                <th style="border:1px solid #ccc; padding:6px;">Type</th>
                <th style="border:1px solid #ccc; padding:6px;">Amount</th>
                <th style="border:1px solid #ccc; padding:6px;">Price</th>
                <th style="border:1px solid #ccc; padding:6px;">Status</th>
            </tr>
            </thead>
            <tbody id="crypto-orders-body"></tbody>
        </table>

        <h3>➕ Place Order</h3>
        <form id="crypto-order-form" onsubmit="return false;" style="margin-top:10px;">
            <select id="order-type">
                <option value="buy">Buy</option>
                <option value="sell">Sell</option>
            </select>
            <input type="number" id="order-amount" placeholder="Amount" step="0.0001" required>
            <input type="number" id="order-price" placeholder="Price" step="0.0001" required>
            <button type="submit">Submit</button>
        </form>

        <div id="crypto-log" style="margin-top:15px; background:#fafafa; border:1px solid #ddd; padding:8px; font-size:13px; max-height:150px; overflow:auto;"></div>
    </div>

    <!-- Phoenix client -->
    <script src="https://cdn.jsdelivr.net/npm/phoenix@1.7.9/priv/static/phoenix.js"></script>
    <script>
        const log = (msg) => {
            const el = document.getElementById("crypto-log");
            el.innerHTML += msg + "<br>";
            el.scrollTop = el.scrollHeight;
        };

        document.addEventListener("DOMContentLoaded", () => {
            // Dùng window.Phoenix.Socket
            const socket = new window.Phoenix.Socket("<?= SPIRIT_SOCKET_WS_URL ?>/socket");
        socket.connect();

        const channel = socket.channel("crypto:lobby", {});
        channel.join()
            .receive("ok", () => log("✅ Connected to crypto:lobby"))
        .receive("error", () => log("❌ Cannot join crypto:lobby"));

        // Render row vào bảng
        const addOrderRow = ({ type, amount, price, status }) => {
            const tbody = document.getElementById("crypto-orders-body");
            const tr = document.createElement("tr");

            const td = (text, color="") => {
                const el = document.createElement("td");
                el.textContent = text;
                el.style.border = "1px solid #ccc";
                el.style.padding = "6px";
                if (color) el.style.color = color;
                return el;
            };

            const color = type === "buy" ? "green" : (type === "sell" ? "red" : "");
            tr.appendChild(td(type.toUpperCase(), color));
            tr.appendChild(td(amount));
            tr.appendChild(td(price));
            tr.appendChild(td(status || "pending"));
            tbody.prepend(tr);
        };

        // Nhận realtime từ server
        channel.on("new_order", (payload) => {
            log("📥 New order: " + JSON.stringify(payload));
        addOrderRow(payload);
        });

        // Xử lý submit order
        const form = document.getElementById("crypto-order-form");
        form.addEventListener("submit", (e) => {
            e.preventDefault();
        const type = document.getElementById("order-type").value;
        const amount = parseFloat(document.getElementById("order-amount").value);
        const price = parseFloat(document.getElementById("order-price").value);

        channel.push("create_order", { type, amount, price })
            .receive("ok", (res) => {
            log("📤 Order submitted: " + JSON.stringify(res));
        form.reset();
        })
        .receive("error", (err) => {
            log("❌ Order failed: " + JSON.stringify(err));
        });
        });
        });
    </script>


    <?php
    return ob_get_clean();
});
