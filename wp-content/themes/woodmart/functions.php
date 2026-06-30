<?php
/**
 *
 * The framework's functions and definitions
 */

define( 'WOODMART_THEME_DIR', get_template_directory_uri() );
define( 'WOODMART_THEMEROOT', get_template_directory() );
define( 'WOODMART_IMAGES', WOODMART_THEME_DIR . '/images' );
define( 'WOODMART_SCRIPTS', WOODMART_THEME_DIR . '/js' );
define( 'WOODMART_STYLES', WOODMART_THEME_DIR . '/css' );
define( 'WOODMART_FRAMEWORK', '/inc' );
define( 'WOODMART_DUMMY', WOODMART_THEME_DIR . '/inc/dummy-content' );
define( 'WOODMART_CLASSES', WOODMART_THEMEROOT . '/inc/classes' );
define( 'WOODMART_CONFIGS', WOODMART_THEMEROOT . '/inc/configs' );
define( 'WOODMART_HEADER_BUILDER', WOODMART_THEME_DIR . '/inc/header-builder' );
define( 'WOODMART_ASSETS', WOODMART_THEME_DIR . '/inc/admin/assets' );
define( 'WOODMART_ASSETS_IMAGES', WOODMART_ASSETS . '/images' );
define( 'WOODMART_API_URL', 'https://xtemos.com/licenses/api/' );
define( 'WOODMART_DEMO_URL', 'https://woodmart.xtemos.com/' );
define( 'WOODMART_PLUGINS_URL', WOODMART_DEMO_URL . 'plugins/' );
define( 'WOODMART_DUMMY_URL', WOODMART_DEMO_URL . 'dummy-content-new/' );
define( 'WOODMART_TOOLTIP_URL', WOODMART_DEMO_URL . 'theme-settings-tooltips/' );
define( 'WOODMART_SLUG', 'woodmart' );
define( 'WOODMART_CORE_VERSION', '1.0.36' );
define( 'WOODMART_WPB_CSS_VERSION', '1.0.2' );

if ( ! function_exists( 'woodmart_load_classes' ) ) {
    function woodmart_load_classes() {
        $classes = array(
            'Singleton.php',
            'Api.php',
            'Googlefonts.php',
            'Config.php',
            'Layout.php',
            'License.php',
            'Notices.php',
            'Options.php',
            'Stylesstorage.php',
            'Theme.php',
            'Themesettingscss.php',
            'Vctemplates.php',
            'Wpbcssgenerator.php',
            'Registry.php',
            'Pagecssfiles.php',
        );

        foreach ( $classes as $class ) {
            require WOODMART_CLASSES . DIRECTORY_SEPARATOR . $class;
        }
    }
}

woodmart_load_classes();

new WOODMART_Theme();

define( 'WOODMART_VERSION', woodmart_get_theme_info( 'Version' ) );



//change logo admin
// Change Admin Bar Logo
function custom_admin_logo() {
    echo '
    <style>
        #wp-admin-bar-wp-logo > .ab-item .ab-icon {
            background-image: url("https://warehouse.urbanhome.vn/wp-content/uploads/2025/03/LogoHead.svg") !important;
            background-size: cover !important;
            background-position: center !important;
            width: 20px !important;
            height: 20px !important;
        }
    </style>';
}
add_action("admin_head", "custom_admin_logo");

// Change Login Page Logo
function custom_login_logo() {
    echo '
    <style>
        #login h1 a {
            background-image: url("https://spiritwebs.com/wp-content/uploads/2025/04/2025-04-17_013253-Photoroom.png") !important;
            background-size: contain !important;
            width: 300px !important;
            height: 80px !important;
            display: block;
        }
    </style>';
}
add_action("login_head", "custom_login_logo");



// Change Login Logo Link
function custom_login_logo_url() {
    return home_url(); // Redirect to your site
}
add_filter("login_headerurl", "custom_login_logo_url");


//custom menu admin
function my_custom_admin_menu() {
    add_menu_page(
        'Dự án',       // Page title
        '<span class="nhadat">Dự</span> <span class="dulieu">Án</span>',              // Menu title
        'manage_options',       // Capability
        'wp_duan',       // Menu slug
        'my_custom_page_content_wp_duan', // Function to display content
        'dashicons-admin-generic', // Dashicon icon
        5                     // Position in menu order
    );
    add_menu_page(
        'Dự án',       // Page title
        '<span class="nhadat">Quán</span> <span class="dulieu">nhậu</span>',              // Menu title
        'manage_options',       // Capability
        'wp_pubs',       // Menu slug
        'my_custom_page_content_wp_pubs', // Function to display content
        'dashicons-admin-generic', // Dashicon icon
        5                     // Position in menu order
    );
    add_menu_page(
        'Dự án',       // Page title
        '<span class="nhadat">Doanh</span> <span class="dulieu">nghiệp</span>',              // Menu title
        'manage_options',       // Capability
        'wp_companies',       // Menu slug
        'my_custom_page_content_wp_companies', // Function to display content
        'dashicons-admin-generic', // Dashicon icon
        5                     // Position in menu order
    );
    add_menu_page(
        'Dự án',       // Page title
        '<span class="nhadat">Doanh</span> <span class="dulieu">nghiệp yellow</span>',              // Menu title
        'manage_options',       // Capability
        'wp_companies_yellowpages',       // Menu slug
        'my_custom_page_content_wp_companies_yellow_page', // Function to display content
        'dashicons-admin-generic', // Dashicon icon
        5                     // Position in menu order
    );
    add_menu_page(
        'Dữ liệu nhà đất',
        '<span class="dulieu">Dữ Liệu</span> <span class="nhadat">Nhà Đất</span>',
        'manage_options',
        'wp_dulieunhadat',
        'my_custom_page_content_wp_dulieunhadat',
        'dashicons-building', // Dashicon icon
        6
    );
    /*add_menu_page(
        'Quản lý nhân sự',
        'Nhân Sự',
        'edit_users',
        'nhansu-main',
        '__return_null', // Or your custom function
        'dashicons-groups',
        7
    );

    // Manually add link to User Role Editor (must be active)
    add_submenu_page(
        'nhansu-main',
        'Phân quyền',
        'Phân quyền',
        'edit_users',
        'users.php?page=users-user-role-editor.php'
    );*/
}
add_action('admin_footer', 'customize_menu_titles_html');
function customize_menu_titles_html() {
    ?>
    <script>
        jQuery(document).ready(function($) {
            // Menu "Nhân sự"
            $('#adminmenu a.menu-top[href="users.php"] .wp-menu-name').html('<span class="dulieu">Nhân</span> <span class="nhadat">Sự</span>');
        });
    </script>
    <?php
}

// hide language in user
add_action('admin_head', function () {
    global $pagenow;

    if (in_array($pagenow, ['user-edit.php', 'profile.php', 'user-new.php'])) {
        ?>
        <style>
            /* Hide the Language field row */
            tr.user-language-wrap {
                display: none !important;
            }

        </style>
        <?php
    }
});

// Xóa các role mặc định và ẩn các role khác
function remove_default_user_roles() {
    if( current_user_can( 'administrator' ) ) {
        // Xóa các role mặc định
        remove_role( 'subscriber' );
        remove_role( 'contributor' );

        // Vô hiệu hóa quyền cho Biên Tập Viên (Editor) và Tác Giả (Author)
        $editor = get_role('editor');
        if ($editor) {
            $editor->remove_cap('edit_posts'); // Không cho phép biên tập bài viết
            $editor->remove_cap('publish_posts'); // Không cho phép xuất bản bài viết
        }

        $author = get_role('author');
        if ($author) {
            $author->remove_cap('edit_posts'); // Không cho phép biên tập bài viết
            $author->remove_cap('publish_posts'); // Không cho phép xuất bản bài viết
        }
    }
}
add_action('admin_init', 'remove_default_user_roles');

// Ẩn role "Editor" và "Author" khỏi danh sách có thể chọn trong phần quản lý người dùng
function hide_roles_from_users( $roles ) {
    unset( $roles['editor'] ); // Xóa role Editor
    unset( $roles['author'] );  // Xóa role Author
    unset( $roles['shop_manager'] );
    return $roles;
}
add_filter( 'editable_roles', 'hide_roles_from_users' );

/*// Thay đổi tên hiển thị của vai trò "Administrator" thành "Quản trị cấp cao"
function custom_admin_role_name($role_names) {
    if (isset($role_names['administrator'])) {
        $role_names['administrator'] = 'Quản trị cấp cao'; // Đổi tên hiển thị vai trò Administrator
    }
    return $role_names;
}
add_filter('editable_roles', 'custom_admin_role_name');*/




function replace_admin_bar_site_name_with_logo() {
    ?>
    <style>

        #wp-admin-bar-site-name > .ab-item {
            background-image: url('https://warehouse.urbanhome.vn/wp-content/uploads/2025/04/LogoHead.svg');
            background-repeat: no-repeat;
            background-position: center left;
            background-size: contain;
            text-indent: -9999px;
            width: 160px;
            background-color: transparent !important;
        }

        #wp-admin-bar-site-name > .ab-item:hover {
            background-image: url('https://warehouse.urbanhome.vn/wp-content/uploads/2025/04/LogoHead.svg') !important;
            background-repeat: no-repeat !important;
            background-position: center left !important;
            background-size: contain !important;
            background-color: transparent !important;
        }
    </style>
    <?php
}
add_action('admin_head', 'replace_admin_bar_site_name_with_logo');
add_action('wp_before_admin_bar_render', 'replace_admin_bar_site_name_with_logo');

function remove_footer_admin_text() {
    echo '<style>
        #footer-thankyou,#footer-upgrade{ display: none !important; }
    </style>';
}
add_action('admin_head', 'remove_footer_admin_text');


function my_custom_admin_scripts($hook) {
    /*if ($hook !== 'toplevel_page_wp_dulieunhadat' || $hook !== 'toplevel_page_wp_duan') {
        return;
    }*/

    // Ensure jQuery UI CSS and JS are loaded
    wp_enqueue_style('jquery-ui-css', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css');
    wp_enqueue_script('jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array('jquery'), null, true);

    // jqGrid CSS & JS
    wp_enqueue_style('jqgrid-css', 'https://cdn.jsdelivr.net/npm/free-jqgrid@4.15.5/css/ui.jqgrid.min.css');
    wp_enqueue_script('jqgrid-js', 'https://cdn.jsdelivr.net/npm/free-jqgrid@4.15.5/js/jquery.jqgrid.min.js', array('jquery', 'jquery-ui'), null, true);

    // Enqueue Select2 if needed
    wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css');
    wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js', array('jquery'), null, true);

    // Enqueue custom script for jqGrid
    wp_enqueue_script('my-admin-jqgrid', get_template_directory_uri() . '/admin-jqgrid.js', array('jquery', 'jqgrid-js', 'jquery-ui'), null, true);

    // Localize script for AJAX if needed
    wp_localize_script('my-admin-jqgrid', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));

    // Localize script for AJAX if needed
    wp_localize_script('my-admin-jqgrid', 'ajax_object', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'can_edit'  => function_exists('spiritwebs_user_can') && spiritwebs_user_can('edit_data'),
        'can_delete'  => function_exists('spiritwebs_user_can') && spiritwebs_user_can('delete_data'),
        'can_view'  => function_exists('spiritwebs_user_can') && spiritwebs_user_can('view_data'),
        'is_admin' => current_user_can('administrator'),
    ));
}

add_action('admin_menu', 'my_custom_admin_menu');
add_action('admin_enqueue_scripts', 'my_custom_admin_scripts');

function enqueue_fontawesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

}

add_action('admin_enqueue_scripts', 'enqueue_fontawesome');


function spiritwebs_user_can($capability) {
    $user = wp_get_current_user();
    if (!$user || empty($user->roles)) return false;

    // Nếu user là administrator thì luôn trả về true
    if (in_array('administrator', $user->roles)) {
        return true;
    }

    $saved = get_option('company_roles_caps', []);

    foreach ($user->roles as $role) {
        if (!empty($saved[$role][$capability])) {
            return true;
        }
    }

    return false;
}
// Enqueue CSS & Fonts cho Website Bất Động Sản
function real_estate_enqueue_styles() {
    // Font Be Vietnam Pro
    wp_enqueue_style(
        'be-vietnam-pro',
        'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    // Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
        array(),
        '6.5.2'
    );

    // Custom CSS real-estate.css (trong child theme)
    $css_file = get_stylesheet_directory() . '/css/real-estate.css';
    if ( file_exists( $css_file ) ) {
        wp_enqueue_style(
            'real-estate-style',
            get_stylesheet_directory_uri() . '/css/real-estate.css',
            array(),
            filemtime( $css_file ) // auto update version khi sửa file
        );
    }
}
add_action( 'wp_enqueue_scripts', 'real_estate_enqueue_styles' );

function real_estate_enqueue_tailwind() {
    wp_enqueue_style(
        'tailwind',
        'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
        array(),
        '2.2.19'
    );
}
add_action('wp_enqueue_scripts', 'real_estate_enqueue_tailwind');



function add_custom_class_to_admin_menu() {
    global $menu;

    foreach ($menu as $key => $value) {
        if ($value[2] === 'wp_dulieunhadat') { // Match menu slug
            $menu[$key][4] .= ' wp_dulieunhadat'; // Append custom class
        }

    }
}
add_action('admin_menu', 'add_custom_class_to_admin_menu', 999);

function doi_ten_menu_user_thanh_nhan_su() {
    global $menu, $submenu;

    // Đổi menu chính
    foreach ($menu as $key => $item) {
        if ($item[2] == 'users.php') {
            $menu[$key][0] = 'Nhân sự';
        }
    }

    // Đổi submenu (cẩn thận với key vì có thể khác ngôn ngữ)
    if (isset($submenu['users.php'])) {
        foreach ($submenu['users.php'] as $key => $item) {
            // Đổi "All Users"
            if ($item[2] === 'users.php') {
                $submenu['users.php'][$key][0] = 'Danh sách nhân sự';
            }
            // Đổi "Add New"
            if ($item[2] === 'user-new.php') {
                $submenu['users.php'][$key][0] = 'Thêm nhân sự';
            }
            // Đổi "Profile"
            if ($item[2] === 'profile.php') {
                $submenu['users.php'][$key][0] = 'Thông tin cá nhân';
            }
        }
    }
}
add_action('admin_menu', 'doi_ten_menu_user_thanh_nhan_su', 999);

// Đổi tên và sắp xếp lại các cột trong bảng Users
/*function doi_ten_cot_users($columns) {
    $new_columns = [];

    // Tạo lại thứ tự cột theo mong muốn
    $new_columns['username'] = 'Thông tin';
    $new_columns['name']     = 'Nhân sự';
    $new_columns['email']    = 'Email';
    $new_columns['sinh_nhat'] = 'Năm sinh';
    $new_columns['role']     = 'Chức vụ';
    $new_columns['ngay_vao_lam'] = 'Ngày vào làm';

    // Loại bỏ cột bài viết
    // Không thêm lại cột 'posts' nữa

    return $new_columns;
}
add_filter('manage_users_columns', 'doi_ten_cot_users');*/

// Hiển thị dữ liệu "Năm sinh" trong cột tương ứng
function hien_thi_sinh_nhat($value, $column_name, $user_id) {
    if ($column_name === 'sinh_nhat') {
        $sinh_nhat = get_user_meta($user_id, 'sinh_nhat', true);
        return $sinh_nhat ? date('d/m/Y', strtotime($sinh_nhat)) : '-';
    }
    if ($column_name === 'ngay_vao_lam') {
        $ngay_vao_lam = get_user_meta($user_id, 'ngay_vao_lam', true);
        return $ngay_vao_lam ? date('d/m/Y', strtotime($ngay_vao_lam)) : '-';
    }
    return $value;
}
add_filter('manage_users_custom_column', 'hien_thi_sinh_nhat', 10, 3);


// Thêm nút xuất CSV vào trang quản trị người dùng
function them_nut_xuat_csv() {
    // Kiểm tra quyền quản trị viên
    if (current_user_can('manage_options')) {
        echo '<div class="alignleft actions">';
        echo '<a href="' . admin_url('admin-ajax.php?action=export_users_csv') . '" class="button button-primary">Xuất CSV</a>';
        echo '</div>';
    }
}
add_action('restrict_manage_users', 'them_nut_xuat_csv');


// Hàm xuất CSV cho người dùng
function xuat_du_lieu_users_csv() {
    if (!current_user_can('manage_options')) {
        wp_die('Bạn không có quyền thực hiện thao tác này!');
    }

    // Thiết lập tiêu đề để trình duyệt tải xuống file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="users_export.csv"');

    // Mở file CSV
    $output = fopen('php://output', 'w');

    // Viết tiêu đề cột (dòng đầu tiên)
    fputcsv($output, array('Tài khoản', 'Nhân sự', 'Email', 'Năm sinh', 'Ngày làm', 'Chức vụ'));

    // Lấy tất cả người dùng từ WordPress
    $users = get_users();

    // Lặp qua từng user và xuất dữ liệu vào CSV
    foreach ($users as $user) {
        $username = $user->user_login;
        $name = $user->display_name;
        $email = $user->user_email;
        $sinh_nhat = get_user_meta($user->ID, 'sinh_nhat', true);
        $ngay_lam = get_user_meta($user->ID, 'ngay_lam', true);
        $role = implode(', ', $user->roles); // Nếu người dùng có nhiều vai trò

        // Dữ liệu dòng này sẽ được xuất ra
        fputcsv($output, array($username, $name, $email, $sinh_nhat, $ngay_lam, $role));
    }

    // Đóng file
    fclose($output);
    exit;
}
add_action('wp_ajax_export_users_csv', 'xuat_du_lieu_users_csv');

// Ẩn tab "Trợ giúp" (Help tab) ở góc phải trên trong admin
function an_tab_ho_tro_trong_admin() {
    $screen = get_current_screen();
    $screen->remove_help_tabs(); // Xóa tất cả tab trợ giúp
}
add_action('admin_head', 'an_tab_ho_tro_trong_admin');


//collapse menu admin
/*function collapse_admin_menu_script() {
    wp_enqueue_script('collapse-menu', get_template_directory_uri() . '/collapse-menu.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'collapse_admin_menu_script');*/


function my_custom_admin_styles() {
    echo '<style>
        #adminmenu .menu-top .dulieu {
            color: green !important;
            font-weight: bold;
        }
        #adminmenu .menu-top .nhadat {
            color: orange !important;
            font-weight: bold;
        }
        /* Make the menu icon orange */
        #toplevel_page_wp_dulieunhadat .wp-menu-image img {
            filter: invert(35%) sepia(94%) saturate(560%) hue-rotate(3deg) brightness(98%) contrast(99%);
        }
        #toplevel_page_wp_duan .wp-menu-image img {
            filter: invert(35%) sepia(94%) saturate(560%) hue-rotate(3deg) brightness(98%) contrast(99%);
        }
        #toplevel_page_wp_pubs .wp-menu-image img {
            filter: invert(35%) sepia(94%) saturate(560%) hue-rotate(3deg) brightness(98%) contrast(99%);
        }
    </style>';
}
add_action('admin_head', 'my_custom_admin_styles');

function my_custom_page_content_wp_duan() {
    ?>
    <div class="wrap">
        <div class="header-container">
            <h1  style="padding:10px 0px 10px 0px;">
                <span class="dashicons dashicons-admin-home" style="font-size: 24px; vertical-align: middle; margin-right: 8px; padding:0px 0px 10px 0px"></span>
                <span style="color: #FFA500;  font-weight:bold;">Dự</span>
                <span style="color: #4CAF50; font-weight:bold;">Án</span>

            </h1>
            <div class="button-group">
                <button id="createNewBtn">➕ Tạo Mới</button>
                <button id="deleteBtn">🗑️ Thùng Rác</button>
            </div>
            <select id="tableSelector"></select>
        </div>
        <table id="jqGrid"></table>
        <div id="jqGridPager"></div>
    </div>
    <?php
}

function my_custom_page_content_wp_pubs() {
    ?>
    <div class="wrap">
        <div class="header-container">
            <h1  style="padding:10px 0px 10px 0px;">
                <span class="dashicons dashicons-admin-home" style="font-size: 24px; vertical-align: middle; margin-right: 8px; padding:0px 0px 10px 0px"></span>
                <span style="color: #FFA500;  font-weight:bold;">Quán</span>
                <span style="color: #4CAF50; font-weight:bold;">Nhậu</span>

            </h1>
            <div class="button-group">
                <button id="createNewBtn">➕ Tạo Mới</button>
                <button id="deleteBtn">🗑️ Thùng Rác</button>
            </div>
            <select id="tableSelector"></select>
        </div>
        <table id="jqGrid"></table>
        <div id="jqGridPager"></div>
    </div>
    <?php
}

function my_custom_page_content_wp_companies() {
    ?>
    <div class="wrap">
        <div class="header-container">
            <h1  style="padding:10px 0px 10px 0px;">
                <span class="dashicons dashicons-admin-home" style="font-size: 24px; vertical-align: middle; margin-right: 8px; padding:0px 0px 10px 0px"></span>
                <span style="color: #FFA500;  font-weight:bold;">Doanh</span>
                <span style="color: #4CAF50; font-weight:bold;">Nghiệp</span>

            </h1>
            <select id="tableSelector"></select>
        </div>
        <div style="width:100%; overflow-x:auto;">
            <table id="jqGrid"></table>
            <div id="jqGridPager"></div>
        </div>

    </div>
    <?php
}
function my_custom_page_content_wp_companies_yellow_page() {
    ?>
    <div class="wrap">
        <div class="header-container">
            <h1  style="padding:10px 0px 10px 0px;">
                <span class="dashicons dashicons-admin-home" style="font-size: 24px; vertical-align: middle; margin-right: 8px; padding:0px 0px 10px 0px"></span>
                <span style="color: #FFA500;  font-weight:bold;">Doanh</span>
                <span style="color: #4CAF50; font-weight:bold;">Nghiệp yellow</span>

            </h1>
            <select id="tableSelector"></select>
        </div>
        <div style="width:100%; overflow-x:auto;">
            <table id="jqGrid"></table>
            <div id="jqGridPager"></div>
        </div>

    </div>
    <?php
}


function my_custom_page_content_wp_dulieunhadat() {
    ?>
    <div class="wrap">
        <div class="header-container">
            <h1  style="padding:10px 0px 10px 0px;">
                <span class="dashicons dashicons-admin-home" style="font-size: 24px; vertical-align: middle; margin-right: 8px; padding:0px 0px 10px 0px"></span>
                <span style="color: #4CAF50; font-weight:bold;">Dữ Liệu</span>
                <span style="color: #FFA500;  font-weight:bold;">Nhà Đất</span>
            </h1>
            <div class="button-group">
                <button id="createNewBtn">➕ Tạo Mới</button>
                <button id="deleteBtn">🗑️ Thùng Rác</button>
            </div>
            <select id="tableSelector"></select>
        </div>
        <table id="jqGrid"></table>
        <div id="jqGridPager"></div>
    </div>
    <?php
}

function remove_admin_notices_on_custom_page() {
    $screen = get_current_screen();

    if ($screen && $screen->id === 'toplevel_page_wp_dulieunhadat') {
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }
    if ($screen && $screen->id === 'toplevel_page_wp_duan') {
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }
}

add_action('admin_head', 'remove_admin_notices_on_custom_page');


//hide select
function hide_table_selector() {
    echo '<style>
#tableSelector { display: none !important;}
 .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .button-group {
            display: flex;
            gap: 10px;
        }
        button {
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        #createNewBtn, #deleteBtn {
            background: linear-gradient(145deg, #0073aa, #005e8b);
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.2), 
                        -3px -3px 6px rgba(255, 255, 255, 0.2);
                        margin: 13px 0px 18px 8px;
        }
        #createNewBtn:hover, #deleteBtn:hover {
            background: linear-gradient(145deg, #005e8b, #00496d);
            margin: 13px 0px 18px 8px;
        }
        #createNewBtn:active, #deleteBtn:active {
            transform: translateY(3px);
            box-shadow: inset 3px 3px 6px rgba(0, 0, 0, 0.2);
            margin: 13px 0px 18px 8px;
        }
</style>';
}
add_action('admin_head', 'hide_table_selector');

// hide notification
add_action('admin_head', function () {
    echo '<style>.notice, .update-nag, .error, .is-dismissible { display: none !important; }</style>';
});


function add_ajaxurl_script() {
    ?>
    <script type="text/javascript">
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    </script>
    <?php
}
add_action('wp_head', 'add_ajaxurl_script');

function get_jqgrid_tables() {
    global $wpdb;
    $table_name = "wp_jq_sys_format";

    // Fetch unique table names from the 'tables' column
    $tables = $wpdb->get_col("SELECT DISTINCT `tables` FROM $table_name");

    if (!$tables) {
        wp_send_json_error(["message" => "No tables found"]);
    }

    wp_send_json(["success" => true, "tables" => $tables]);
}
add_action("wp_ajax_get_jqgrid_tables", "get_jqgrid_tables");
add_action("wp_ajax_nopriv_get_jqgrid_tables", "get_jqgrid_tables");

function format_number_to_vietnamese($number) {
    // Remove commas or non-numeric characters
    $number = str_replace(',', '', $number);

    if ($number >= 1000000000) {
        // Convert to billion (tỷ)
        return number_format((float)$number / 1000000000, 1) . ' tỷ';
    } elseif ($number >= 1000000) {
        // Convert to million (triệu)
        return number_format((float)$number / 1000000, 1) . ' triệu';
    } elseif ($number >= 1000) {
        // Convert to thousand (nghìn)
        return number_format((float)$number / 1000, 1) . ' nghìn';
    } else {
        // Return the number as is if it's smaller than 1000
        return number_format((float) $number, 0, ',', '.');
    }
}
// load content data
function load_jqgrid_data() {
    global $wpdb;

    if (!isset($_POST['table']) || empty($_POST['table'])) {
        wp_send_json_error(["message" => "No table specified"]);
    }

    $selected_table = esc_sql($_POST['table']); // Sanitize input
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $limit = isset($_POST['rows']) ? intval($_POST['rows']) : 15;
    $start = ($page - 1) * $limit;

    // Handle sorting
    $sort_field = isset($_POST['sidx']) && !empty($_POST['sidx']) ? esc_sql($_POST['sidx']) : "id";
    $sort_order = isset($_POST['sord']) && !empty($_POST['sord']) ? esc_sql($_POST['sord']) : "asc";

    // Initialize filtering
    $where = "WHERE 1=1"; // Always true condition to append filters

    if (!empty($_POST['filters'])) {
        $filters = json_decode(stripslashes($_POST['filters']), true);
        if ($filters && isset($filters['rules'])) {
            foreach ($filters['rules'] as $filter) {
                $field = esc_sql($filter['field']);
                $value = esc_sql($filter['data']);
                $where .= " AND $field LIKE '%$value%'"; // Use LIKE for "contains" search
            }
        }
    }


    // Get total records count with filters
    $total_records = $wpdb->get_var("SELECT COUNT(*) FROM `$selected_table` $where");
    //$total_records = $wpdb->get_var("SELECT COUNT(*) FROM `$selected_table`");
    $total_pages = ($total_records > 0) ? ceil($total_records / $limit) : 1;

    // Fetch paginated and sorted data
    $rows = $wpdb->get_results("SELECT * FROM `$selected_table` $where ORDER BY `$sort_field` $sort_order LIMIT $start, $limit", ARRAY_A);
    // Post-process: Attach category name
    $transactionTypeCache = [];
    $propertyTypeCache = [];
    $vitriCache = [];

    if($selected_table == "wp_dulieunhadat"){
        foreach ($rows as &$row) {
            // Process transaction_type
            if (isset($row['transaction_type'])) {
                $term_id = $row['transaction_type'];

                if (!isset($transactionTypeCache[$term_id])) {
                    $term = get_term($term_id, 'category');
                    $transactionTypeCache[$term_id] = (!is_wp_error($term) && !empty($term)) ? $term->name : "";
                }

                $row['transaction_type_name'] = $transactionTypeCache[$term_id];
            } else {
                $row['transaction_type_name'] = null;
            }

            // Process property_type
            if (isset($row['property_type'])) {
                $term_id = $row['property_type'];

                if (!isset($propertyTypeCache[$term_id])) {
                    $term = get_term($term_id, 'category');
                    $propertyTypeCache[$term_id] = (!is_wp_error($term) && !empty($term)) ? $term->name : "";
                }

                $row['property_type_name'] = $propertyTypeCache[$term_id];
            } else {
                $row['property_type_name'] = null;
            }
            // Process vitri
            if (isset($row['vitri'])) {
                $term_id = $row['vitri'];

                if (!isset($propertyTypeCache[$term_id])) {
                    $term = get_term($term_id, 'category');
                    $propertyTypeCache[$term_id] = (!is_wp_error($term) && !empty($term)) ? $term->name : "";
                }

                $row['vitri_name'] = $propertyTypeCache[$term_id];
            } else {
                $row['vitri_name'] = null;
            }

            // Combine the two into one label
            $row['getname_column_first'] = '<span><b style="    background: #ffa500;
    padding: 5px;
    color: #fff;
    border-radius: 5px;">'.$row['transaction_type_name'] . '</b><br><hr style="margin: 3px; 
width: 80%; border: none; border-top: 1px solid #ccc; ">' . $row['property_type_name'].'</span>';

            $row['getname_column_two'] = '<p style="
    font-size: 10px;
    background: #fff;
    padding: 2px 5px;
    width: max-content;
    color: #00974c;
    border-radius: 3px;
    text-transform: uppercase;
    box-shadow: 0px 0px 2px #ccc;
    margin-bottom: 2px;">'.$row['vitri_name'] .'</p><div style="color: #0000ca;
    font-style: italic;">'.$row['road'].'</div><p style="margin-top: 5px; background: #aeaeae; color: #fff; 
    padding: 2px; width: max-content; border-radius: 5px;">'.$row['khuvuc'].'</p>';

// Get wp_district name based on wp_district_id (assuming wp_district_id is stored in your table)
            if (isset($row['wp_district'])) {
                $wp_district_id = $row['wp_district']; // The ID of the wp_district stored in the row
                $wp_district_name = $wpdb->get_var($wpdb->prepare("SELECT name FROM wp_district WHERE id = %d", $wp_district_id));

                if ($wp_district_name) {
                    $row['wp_district_name'] = $wp_district_name;
                } else {
                    $row['wp_district_name'] = "Not Found"; // Default message if no wp_district is found
                }
            } else {
                $row['wp_district_name'] = "No wp_district ID"; // Default message if no wp_district_id is set
            }
            if (isset($row['wp_wards'])) {
                $ward_id = $row['wp_wards']; // The ID of the wp_district stored in the row
                $ward_name = $wpdb->get_var($wpdb->prepare("SELECT name FROM wp_wards WHERE id = %d", $ward_id));

                if ($ward_name) {
                    $row['ward_name'] = $ward_name;
                } else {
                    $row['ward_name'] = "Not Found"; // Default message if no wp_district is found
                }
            } else {
                $row['ward_name'] = "No wp_district ID"; // Default message if no wp_district_id is set
            }


            if (isset($row['donvi_giaban'])) {
                $term_id = $row['donvi_giaban'];

                if (!isset($propertyTypeCache[$term_id])) {
                    $term = get_term($term_id, 'category');
                    $propertyTypeCache[$term_id] = (!is_wp_error($term) && !empty($term)) ? $term->name : "";
                }

                $row['donvi_giaban_name'] = $propertyTypeCache[$term_id];
            } else {
                $row['donvi_giaban_name'] = null;
            }

            $row['giaban'] = '<b style="
    color: #ff0000; font-weight:bold;">'.format_number_to_vietnamese($row['giaban']).' '.$row['donvi_giaban_name'].'</b>';

            $row['getname_column_three'] = '<p style="
        font-size: 14px;
    background: #fff;
    padding: 2px 5px;
    width: max-content;
    color: #ff0000;
    border-radius: 3px;
    box-shadow: 0px 0px 2px #ccc;
    box-shadow: 0px 0px 2px #ccc;
    margin-bottom: 2px;
    font-weight: bold;">'.$row['dtd_congnhan'] .' m²</p><p style="margin-top: 5px; background: #4caf50; color: #fff; 
    padding: 2px; width: max-content; border-radius: 5px;">('.$row['dtd_ngang'].'m x '.$row['dtd_dai'].'m )</p>';


        }
        unset($row);
    }



    wp_send_json([
        "page" => $page,
        "total" => $total_pages,
        "records" => $total_records,
        "rows" => $rows
    ]);
}

add_action("wp_ajax_load_jqgrid_data", "load_jqgrid_data");
add_action("wp_ajax_nopriv_load_jqgrid_data", "load_jqgrid_data");




function get_wp_provinces() {
    global $wpdb;

    // Query to get id and name from the wp_province table
    $sql = "SELECT id, `name` FROM wp_province";  // Replace 'wp_province' with your actual table name

    // Execute the query and fetch the results
    $results = $wpdb->get_results($sql);

    // Initialize an array to store the options
    $options = [];

    // Loop through the results and store them in the options array
    if ($results) {
        foreach ($results as $row) {
            // Use the id as the key and the name as the value
            $options[$row->id] = $row->name;
        }
    }

    return $options;
}

function get_wp_duan() {
    global $wpdb;

    // Query to get id and name from the wp_province table
    $sql = "SELECT id, `TenLoai` FROM wp_duan";  // Replace 'wp_province' with your actual table name

    // Execute the query and fetch the results
    $results = $wpdb->get_results($sql);

    // Initialize an array to store the options
    $options = [];

    // Loop through the results and store them in the options array
    if ($results) {
        foreach ($results as $row) {
            // Use the id as the key and the name as the value
            $options[$row->id] = $row->TenLoai;
        }
    }

    return $options;
}

function parseEditRules($rulesString) {
    $rulesArray = [];
    $rules = explode(";", $rulesString);

    foreach ($rules as $rule) {
        $parts = explode(":", $rule);
        if (count($parts) == 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);

            // Convert string "true"/"false" to boolean
            $rulesArray[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }
    }
    return $rulesArray;
}
// get colModel
function get_jqgrid_colmodel() {
    global $wpdb;

    // Ensure a table is specified
    if (!isset($_POST['table']) || empty($_POST['table'])) {
        wp_send_json_error(["message" => "No table specified"]);
    }

    $selected_table = esc_sql($_POST['table']); // Sanitize table name

    // Fetch column details from wp_jq_sys_format
    $columns = $wpdb->get_results("SELECT tables, `name` AS field, label, width, align, searchoptions, editable, editrules,  edittype,`key`, hidden, `order`,`active`
FROM wp_jq_sys_format WHERE active = 1 AND tables = '$selected_table'  ORDER BY `order` ASC", ARRAY_A);

    if (!$columns) {
        wp_send_json_error(["message" => "No column definitions found in wp_jq_sys_format for table: $selected_table"]);
    }

    $colModel = array_map(function ($col) use ($wpdb, $selected_table) {
        $column_name = esc_sql($col["field"]);
        $search_options = isset($col["searchoptions"]) ? $col["searchoptions"] : "";

        $colConfig = [
            "tables" => $col["tables"],
            "name" => $column_name,
            "index" => $column_name,
            "key" => filter_var($col["key"], FILTER_VALIDATE_BOOLEAN),  // Convert "true"/"false" string to boolean
            "hidden" => filter_var($col["hidden"], FILTER_VALIDATE_BOOLEAN),  // Ensure hidden is boolean
            "sortable" => true,
            "sorttype" => "text",
            "width" => intval($col["width"]) ?: 100,
            "align" => !empty($col["align"]) ? $col["align"] : "left",
            "label" => $col["label"],
            "order" => intval($col["order"]),
            "active" => $col["active"],
            "editable" => filter_var($col["editable"], FILTER_VALIDATE_BOOLEAN),
            "edittype" => "text",
            "editrules" => parseEditRules($col['editrules']),
            "editoptions" => [], // initialize empty
        ];
        /* if ($column_name === 'id') {
             $colConfig["editable"] = false;
             $colConfig["hidden"] = true;
             $colConfig["editoptions"] = [
                 "readonly" => true
             ];
         }*/
        // For date input
        if ($column_name === 'created_at') {
            $colConfig["edittype"] = "text";
            $colConfig["editoptions"] = [
                "dataInit" => "function(el) { $(el).datepicker({ dateFormat: 'yy-mm-dd' }); }"
            ];
        }
        if ($col['edittype'] === 'textarea') {
            $colConfig["edittype"] = "textarea";
            $colConfig["editoptions"] = [
                "rows" => 4,
                "cols" => 40
            ];
        }
        if ($column_name === 'getname_column_first' || $column_name === 'getname_column_two' || $column_name === 'getname_column_three' || $column_name === 'giaban') {
            $colConfig["formatter"] = "html";
        }

        if ($column_name === 'dtd_dai' || $column_name === 'dtd_ngang' || $column_name === 'dtd_mathau' ||
            $column_name === 'dtqh_dai' || $column_name === 'dtqh_ngang' || $column_name === 'dtqh_mathau'
            || $column_name === 'ttk_duongrong') {
            $colConfig["editoptions"] = [
                "placeholder" => "m",
                "title" => "Giá trị nhập: m"
            ];
        }
        if ($column_name === 'dtqh_xaydung' || $column_name === 'dtd_congnhan' || $column_name === 'ttk_dtsd') {
            $colConfig["editoptions"] = [
                "placeholder" => "m²",
                "title" => "Giá trị nhập: m²"
            ];
        }

        if (!empty($col["editrules"]) && strpos($col["editrules"], "required:true") !== false) {
            $colConfig["label"] = "<i class='fa fa-asterisk' style='color:red; margin-right:5px;'></i>" . $colConfig["label"];
        }
        // If the column has searchoptions set to 'select', fetch distinct values
        if ($search_options === "select") {

            if($column_name == 'wp_district_name'){
                // Custom logic for wp_district table
                $query = "SELECT DISTINCT `name` FROM `wp_district` WHERE `name` IS NOT NULL ORDER BY `name` ASC";
                $distinct_values = $wpdb->get_col($query);

                if ($distinct_values) {
                    // Example: capitalize wp_district names, or add "All" option, etc.
                    $options = array_map(fn($val) => ucfirst($val) . ":" . ucfirst($val), $distinct_values);
                    array_unshift($options, ":Tất cả"); // Optional blank/default option

                    $colConfig["stype"] = "select";
                    $colConfig["searchoptions"] = [
                        "value" => implode(";", $options),
                        "sopt" => ["eq", "ne"]
                    ];
                }
            }
            if($column_name == 'ward_name'){
                // Custom logic for wp_district table
                $query = "SELECT DISTINCT `name` FROM `wp_wards` WHERE `name` IS NOT NULL ORDER BY `name` ASC";
                $distinct_values = $wpdb->get_col($query);

                if ($distinct_values) {
                    // Example: capitalize wp_district names, or add "All" option, etc.
                    $options = array_map(fn($val) => ucfirst($val) . ":" . ucfirst($val), $distinct_values);
                    array_unshift($options, ":Tất cả"); // Optional blank/default option

                    $colConfig["stype"] = "select";
                    $colConfig["searchoptions"] = [
                        "value" => implode(";", $options),
                        "sopt" => ["eq", "ne"]
                    ];
                }
            }
            else{
                $query = "SELECT DISTINCT `$column_name` FROM `$selected_table` WHERE `$column_name` IS NOT NULL ORDER BY `$column_name` ASC";
                $distinct_values = $wpdb->get_col($query);

                if ($distinct_values) {
                    $options = array_map(fn($val) => "$val:$val", $distinct_values);
                    $colConfig["stype"] = "select";
                    $colConfig["searchoptions"] = [
                        "value" => implode(";", $options),
                        "sopt" => ["eq", "ne"] // Equals, Not Equals
                    ];
                }
            }

        }

        // form edit
        if ($col["edittype"] === "select") {
            $categories = get_the_category();

            // Example static values for select options
            if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "transaction_type"){
                $parent_id = 431; // Replace with the actual parent category ID
                $categories = get_categories([
                    'taxonomy'   => 'category',  // WordPress category taxonomy
                    'hide_empty' => false,       // Show even empty categories
                    'parent'     => $parent_id,  // Get child categories of this parent
                ]);

                $options = [];
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }

            }
            else if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "product_type"){
                $parent_id = 513; // Replace with the actual parent category ID
                $categories = get_categories([
                    'taxonomy'   => 'category',  // WordPress category taxonomy
                    'hide_empty' => false,       // Show even empty categories
                    'parent'     => $parent_id,  // Get child categories of this parent
                ]);

                $options = [];
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }

            }
            else if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "property_type"){
                $parent_id = 519; // Replace with the actual parent category ID
                $categories = get_categories([
                    'taxonomy'   => 'category',  // WordPress category taxonomy
                    'hide_empty' => false,       // Show even empty categories
                    'parent'     => $parent_id,  // Get child categories of this parent
                ]);

                $options = [];
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }

            }
            else if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "vitri"){
                $parent_id = 557; // Replace with the actual parent category ID
                $categories = get_categories([
                    'taxonomy'   => 'category',  // WordPress category taxonomy
                    'hide_empty' => false,       // Show even empty categories
                    'parent'     => $parent_id,  // Get child categories of this parent
                ]);

                $options = [];
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }

            }
            else if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "ttk_huong"){
                $parent_id = 567; // Replace with the actual parent category ID
                $categories = get_categories([
                    'taxonomy'   => 'category',  // WordPress category taxonomy
                    'hide_empty' => false,       // Show even empty categories
                    'parent'     => $parent_id,  // Get child categories of this parent
                ]);

                $options = [];
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }

            }
            else if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "ttk_loaidat"){
                $parent_id = 595; // Replace with the actual parent category ID
                $categories = get_categories([
                    'taxonomy'   => 'category',  // WordPress category taxonomy
                    'hide_empty' => false,       // Show even empty categories
                    'parent'     => $parent_id,  // Get child categories of this parent
                ]);

                $options = [];
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }

            }
            else if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "loaitaisan"){
                $parent_id = 609; // Replace with the actual parent category ID
                $categories = get_categories([
                    'taxonomy'   => 'category',  // WordPress category taxonomy
                    'hide_empty' => false,       // Show even empty categories
                    'parent'     => $parent_id,  // Get child categories of this parent
                ]);

                $options = [];
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }

            }
            else if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "vaitro"){
                $parent_id = 625; // Replace with the actual parent category ID
                $categories = get_categories([
                    'taxonomy'   => 'category',  // WordPress category taxonomy
                    'hide_empty' => false,       // Show even empty categories
                    'parent'     => $parent_id,  // Get child categories of this parent
                ]);

                $options = [];
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }

            }
            else if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "gioitinh"){
                $parent_id = 641; // Replace with the actual parent category ID
                $categories = get_categories([
                    'taxonomy'   => 'category',  // WordPress category taxonomy
                    'hide_empty' => false,       // Show even empty categories
                    'parent'     => $parent_id,  // Get child categories of this parent
                ]);

                $options = [];
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }

            }
            else if($col["tables"] == 'wp_dulieunhadat' && ($col["field"] == "donvi_giathue" || $col["field"] == "donvi_giaban")){
                $parent_id = 655; // Replace with the actual parent category ID
                $categories = get_categories([
                    'taxonomy'   => 'category',  // WordPress category taxonomy
                    'hide_empty' => false,       // Show even empty categories
                    'parent'     => $parent_id,  // Get child categories of this parent
                ]);

                $options = [];
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }

            }
            else if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "donvi_thoigiangia"){
                $parent_id = 661; // Replace with the actual parent category ID
                $categories = get_categories([
                    'taxonomy'   => 'category',  // WordPress category taxonomy
                    'hide_empty' => false,       // Show even empty categories
                    'parent'     => $parent_id,  // Get child categories of this parent
                ]);

                $options = [];
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }

            }
            else if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "donvi_thoigianthue"){
                $parent_id = 667; // Replace with the actual parent category ID
                $categories = get_categories([
                    'taxonomy'   => 'category',  // WordPress category taxonomy
                    'hide_empty' => false,       // Show even empty categories
                    'parent'     => $parent_id,  // Get child categories of this parent
                ]);

                $options = [];
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }

            }
            else if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "loaihoahong"){
                $parent_id = 647; // Replace with the actual parent category ID
                $categories = get_categories([
                    'taxonomy'   => 'category',  // WordPress category taxonomy
                    'hide_empty' => false,       // Show even empty categories
                    'parent'     => $parent_id,  // Get child categories of this parent
                ]);

                $options = [];
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }

            }
            else if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "wp_province"){
                $options = get_wp_provinces();
            }
            else if($col["tables"] == 'wp_dulieunhadat' && $col["field"] == "name_area"){
                $options = get_wp_duan();
            }
            else if($col["tables"] == 'wp_dulieunhadat' && ($col["field"] == "ttk_sotang" || $col["field"] == "ttk_sophongngu" || $col["field"] == "ttk_swc"
                    || $col["field"] == "ttk_sotangham")){
                $options = [];
                for ($i = 1; $i <= 50; $i++) {
                    $options[$i] = $i;
                }
            }
            else{

            }


            $colConfig["edittype"] = "select"; // Set the edit type to select

            // Map the options for the select input
            $optionString = '';
            foreach ($options as $value => $label) {
                $optionString .= "$value:$label;";
            }

            // Set editoptions to include the select options
            $colConfig["editoptions"] = [
                "value" => ":;" . rtrim($optionString, ';'), // Create a string like "value1:label1;value2:label2;"
            ];
        }
        return $colConfig;
    }, $columns);

    wp_send_json(["success" => true, "colModel" => $colModel]);
}

add_action("wp_ajax_get_jqgrid_colmodel", "get_jqgrid_colmodel");
add_action("wp_ajax_nopriv_get_jqgrid_colmodel", "get_jqgrid_colmodel");

// function add data
// Function to add data
function add_jqgrid_data() {
    global $wpdb;

    if (!isset($_POST['table']) || empty($_POST['table'])) {
        wp_send_json_error(["message" => "No table specified"]);
    }

    // Get the current logged-in user
    $current_user = wp_get_current_user();

    // Check if the user is logged in
    if (!$current_user->ID) {
        wp_send_json_error(["message" => "User not logged in"]);
    }

    // Sanitize the table name from the POST data
    $selected_table = esc_sql($_POST['table']);

    // Get data from the POST request
    $data = $_POST;

    // Remove unnecessary fields from the data array
    unset($data['action'], $data['table'], $data['id'], $data['oper'], $data['pll_ajax_backend']);

    // Add user information to the data (e.g., user ID)
    $data['user'] = $current_user->ID; // You can add more user-related info as needed

    // Insert data into the specified table
    $wpdb->insert($selected_table, $data);

    // Check for any errors after the insert
    if ($wpdb->last_error) {
        wp_send_json_error(["message" => $wpdb->last_error]);
    } else {
        wp_send_json_success(["message" => "Record added successfully"]);
    }
}


add_action("wp_ajax_add_jqgrid_data", "add_jqgrid_data");

//function edit data
function edit_jqgrid_data() {
    global $wpdb;
    error_log("Received POST data: " . print_r($_POST, true));
    if (!isset($_POST['table']) || empty($_POST['table'])) {
        wp_send_json_error(["message" => "No table specified"]);
    }

    $selected_table = esc_sql($_POST['table']);
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id <= 0) {
        wp_send_json_error(["message" => "Invalid ID: $id"]);
    }

    $data = $_POST;
    unset($data['action'], $data['table'], $data['id'], $data['oper']); // Remove unnecessary fields

    $columns = $wpdb->get_col("SHOW COLUMNS FROM `$selected_table`");

    // ❌ Xóa các key không thuộc bảng database
    foreach ($data as $key => $value) {
        if (!in_array($key, $columns)) {
            unset($data[$key]); // Xóa key không hợp lệ
        }
    }

    unset($data['id']); // Remove ID from update data
    // Log the data being updated
    error_log("Updating table: $selected_table, id: $id, Data: " . print_r($data, true));

    $result = $wpdb->update($selected_table, $data, ["id" => $id]);

    if ($result === false) {
        error_log("SQL ERROR: " . $wpdb->last_error);
        wp_send_json_error(["message" => "DB Error: " . $wpdb->last_error]);
    } else {
        error_log("SQL QUERY SUCCESS: " . $wpdb->last_query);
        wp_send_json_success(["message" => "Record updated successfully"]);
    }
}

add_action("wp_ajax_edit_jqgrid_data", "edit_jqgrid_data");

// function delete data
add_action('wp_ajax_delete_jqgrid_data', 'delete_jqgrid_data_callback');
function delete_jqgrid_data_callback() {
    // Validate nonce for security
    /* if ( !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'delete_jqgrid_data_nonce') ) {
         wp_send_json_error("Invalid nonce");
         return;
     }*/

    // Check if the required parameters are set
    if ( !isset($_POST['table']) || !isset($_POST['id']) ) {
        wp_send_json_error("Missing parameters");
        return;
    }

    // Sanitize inputs
    $table = sanitize_text_field($_POST['table']);
    $id = intval($_POST['id']);  // Ensure the ID is an integer

    if ( !$table || !$id ) {
        wp_send_json_error("Invalid table or ID");
        return;
    }

    global $wpdb;

    // Perform the deletion query
    $result = $wpdb->delete($table, ['id' => $id]);

    if ($result !== false) {
        wp_send_json_success("Deleted successfully");
    } else {
        wp_send_json_error("Failed to delete");
    }
}





// ajax wp_province, wp_district, wp_wards
// Hook for AJAX request
add_action('wp_ajax_load_wp_districts', 'load_wp_districts_callback');
add_action('wp_ajax_nopriv_load_wp_districts', 'load_wp_districts_callback');

function load_wp_districts_callback() {
    // Check for the wp_province_id passed from the AJAX request
    if (isset($_POST['wp_province_id'])) {
        global $wpdb;

        // Sanitize the input
        $wp_province_id = intval($_POST['wp_province_id']);

        // Query to get the wp_districts related to the selected wp_province
        $sql = "
            SELECT id, `name` 
            FROM wp_district  -- Replace with your actual wp_districts table
            WHERE wp_province_id = %d
        ";

        // Prepare and execute the query
        $wp_districts = $wpdb->get_results($wpdb->prepare($sql, $wp_province_id));

        // Prepare the response
        if ($wp_districts) {
            $data = [];
            foreach ($wp_districts as $wp_district) {
                $data[] = [
                    'id' => $wp_district->id,
                    'name' => $wp_district->name
                ];
            }

            // Send the response back to the AJAX call
            wp_send_json_success(['data' => $data]);
        } else {
            wp_send_json_success(['data' => []]);
        }
    } else {
        wp_send_json_error(['message' => 'wp_province ID not provided']);
    }

    // Always die in AJAX functions to terminate correctly
    wp_die();
}


add_action('wp_ajax_load_wp_wards', 'load_wp_wards_callback');
add_action('wp_ajax_nopriv_load_wp_wards', 'load_wp_wards_callback');

function load_wp_wards_callback() {
    // Check for the wp_province_id passed from the AJAX request
    if (isset($_POST['wp_district_id'])) {
        global $wpdb;

        // Sanitize the input
        $wp_district_id = intval($_POST['wp_district_id']);

        // Query to get the wp_wards related to the selected wp_district
        $sql = "
            SELECT id, `name` 
            FROM wp_wards  -- Replace with your actual wp_wards table
            WHERE wp_district_id = %d
        ";

        // Prepare and execute the query
        $wp_wards = $wpdb->get_results($wpdb->prepare($sql, $wp_district_id));

        // Prepare the response
        if ($wp_wards) {
            $data = [];
            foreach ($wp_wards as $ward) {
                $data[] = [
                    'id' => $ward->id,
                    'name' => $ward->name
                ];
            }

            // Send the response back to the AJAX call
            wp_send_json_success(['data' => $data]);
        } else {
            wp_send_json_success(['data' => []]);
        }
    } else {
        wp_send_json_error(['message' => 'wp_province ID not provided']);
    }

    // Always die in AJAX functions to terminate correctly
    wp_die();
}






// relate USER
// Get wp_provinces from DB
function uh_get_wp_province_options() {
    global $wpdb;
    $results = $wpdb->get_results("SELECT id, name FROM wp_province ORDER BY name ASC");
    $options = [];
    foreach ($results as $row) {
        $options[$row->id] = $row->name;
    }
    return $options;
}

// Get relationships from DB
function uh_get_relationship_options() {
    global $wpdb;
    $results = $wpdb->get_results("SELECT id, name FROM relationship ORDER BY name ASC");
    $options = [];
    foreach ($results as $row) {
        $options[$row->id] = $row->name;
    }
    return $options;
}



function uh_render_text_input($label, $name, $value = '') {
    echo "<tr><th><label for='{$name}'>{$label}</label></th><td><input type='text' name='{$name}' value='" . esc_attr($value) . "' class='regular-text' /></td></tr>";
}

function uh_render_date_input($label, $name, $value = '') {
    echo "<tr><th><label for='{$name}'>{$label}</label></th><td><input type='date' name='{$name}' value='" . esc_attr($value) . "' /></td></tr>";
}

function uh_render_select($label, $name, $options, $selected_value = '') {
    echo "<tr><th><label for='{$name}'>{$label}</label></th><td><select name='{$name}'><option value=''>— Chọn —</option>";
    foreach ($options as $val => $text) {
        $selected = selected($selected_value, $val, false);
        echo "<option value='" . esc_attr($val) . "' {$selected}>" . esc_html($text) . "</option>";
    }
    echo "</select></td></tr>";
}

function uh_render_radio_group($label, $name, $options, $selected_value = '') {
    echo "<tr><th>{$label}</th><td>";
    foreach ($options as $val => $text) {
        $checked = checked($selected_value, $val, false);
        echo "<label><input type='radio' name='{$name}' value='{$val}' {$checked}> {$text}</label><br>";
    }
    echo "</td></tr>";
}

function uh_render_file_upload($label, $name, $current_url = '') {
    echo "<tr><th><label for='{$name}'>{$label}</label></th><td>";
    if (!empty($current_url)) {
        echo "<p><img src='" . esc_url($current_url) . "' style='max-width:200px;' /></p>";
    }
    echo "<input type='file' name='{$name}' accept='image/*' /></td></tr>";
}

function uh_show_custom_user_fields($user) {
    $fields = [
        'ngay_vao_lam', 'nguon_tuyen', 'dien_thoai', 'hon_nhan',
        'sinh_nhat', 'dan_toc', 'ton_giao', 'noi_sinh', 'gioi_tinh',
        'cccd', 'ngay_cap', 'noi_cap',
        'dia_chi_thuong_tru', 'dia_chi_tam_tru', 'facebook', 'zalo',
        'so_tk_ngan_hang', 'chi_nhanh_ngan_hang',
        'ten_nguoi_phu_thuoc', 'dt_nguoi_phu_thuoc', 'quan_he', 'so_nguoi_phu_thuoc',
        'cccd_mat_truoc', 'cccd_mat_sau',
        'anh_so_ho_khau', 'anh_so_yeu_ly_lich', 'anh_bang_cap', 'giay_kham_suc_khoe', 'anh_khac'
    ];
    foreach ($fields as $key) {
        $$key = isset($user->ID) ? get_the_author_meta($key, $user->ID) : '';
    }

    echo '<table class="form-table">';

    uh_render_date_input('Ngày vào làm', 'ngay_vao_lam', $ngay_vao_lam);
    uh_render_radio_group('Nguồn tuyển', 'nguon_tuyen', [
        'tuyen_dung' => 'Tuyển dụng',
        'gioi_thieu' => 'Giới thiệu',
        'khong_xac_dinh' => 'Không xác định'
    ], $nguon_tuyen);
    uh_render_text_input('Điện thoại', 'dien_thoai', $dien_thoai);
    uh_render_select('Hôn nhân', 'hon_nhan', [
        'doc_than' => 'Độc thân',
        'ket_hon' => 'Kết hôn',
        'ly_hon' => 'Ly hôn'
    ], $hon_nhan);
    uh_render_date_input('Sinh nhật', 'sinh_nhat', $sinh_nhat);
    uh_render_select('Giới tính', 'gioi_tinh', ['nam' => 'Nam', 'nu' => 'Nữ'], $gioi_tinh);
    uh_render_text_input('Dân tộc', 'dan_toc', $dan_toc);
    uh_render_text_input('Tôn giáo', 'ton_giao', $ton_giao);

    echo '<tr><th><label for="noi_sinh">Nơi sinh</label></th><td><select name="noi_sinh"><option value="">— Chọn nơi sinh —</option>';
    foreach (uh_get_wp_province_options() as $id => $name) {
        echo '<option value="' . esc_attr($name) . '" ' . selected($noi_sinh, $name, false) . '>' . esc_html($name) . '</option>';
    }
    echo '</select></td></tr>';

    uh_render_text_input('CCCD', 'cccd', $cccd);
    uh_render_date_input('Ngày cấp', 'ngay_cap', $ngay_cap);
    uh_render_text_input('Nơi cấp', 'noi_cap', $noi_cap);
    uh_render_text_input('Địa chỉ thường trú', 'dia_chi_thuong_tru', $dia_chi_thuong_tru);
    uh_render_text_input('Địa chỉ tạm trú', 'dia_chi_tam_tru', $dia_chi_tam_tru);
    uh_render_text_input('Facebook', 'facebook', $facebook);
    uh_render_text_input('Zalo', 'zalo', $zalo);
    uh_render_text_input('Số TK Ngân Hàng', 'so_tk_ngan_hang', $so_tk_ngan_hang);
    uh_render_text_input('Chi nhánh Ngân Hàng', 'chi_nhanh_ngan_hang', $chi_nhanh_ngan_hang);
    uh_render_text_input('Tên người phụ thuộc', 'ten_nguoi_phu_thuoc', $ten_nguoi_phu_thuoc);
    uh_render_text_input('Điện thoại người phụ thuộc', 'dt_nguoi_phu_thuoc', $dt_nguoi_phu_thuoc);
    uh_render_select('Quan hệ', 'quan_he', uh_get_relationship_options(), $quan_he);
    uh_render_text_input('Số người phụ thuộc', 'so_nguoi_phu_thuoc', $so_nguoi_phu_thuoc);

    // Uploads
    foreach ([
                 'cccd_mat_truoc' => 'CCCD Mặt Trước',
                 'cccd_mat_sau' => 'CCCD Mặt Sau',
                 'anh_so_ho_khau' => 'Ảnh Sổ Hộ Khẩu',
                 'anh_so_yeu_ly_lich' => 'Ảnh Sơ Yếu Lý Lịch',
                 'anh_bang_cap' => 'Ảnh Bằng Cấp',
                 'giay_kham_suc_khoe' => 'Giấy Khám Sức Khỏe',
                 'anh_khac' => 'Ảnh Khác'
             ] as $field_key => $label) {
        uh_render_file_upload($label, $field_key, $$field_key);
    }

    echo '</table>';
}

add_action('show_user_profile', 'uh_show_custom_user_fields');
add_action('edit_user_profile', 'uh_show_custom_user_fields');

function uh_save_custom_user_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) return;

    $text_fields = [
        'ngay_vao_lam', 'nguon_tuyen', 'dien_thoai', 'hon_nhan',
        'sinh_nhat', 'dan_toc', 'ton_giao', 'noi_sinh', 'gioi_tinh',
        'cccd', 'ngay_cap', 'noi_cap',
        'dia_chi_thuong_tru', 'dia_chi_tam_tru', 'facebook', 'zalo',
        'so_tk_ngan_hang', 'chi_nhanh_ngan_hang',
        'ten_nguoi_phu_thuoc', 'dt_nguoi_phu_thuoc', 'quan_he', 'so_nguoi_phu_thuoc'
    ];

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_user_meta($user_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    $file_fields = [
        'cccd_mat_truoc', 'cccd_mat_sau',
        'anh_so_ho_khau', 'anh_so_yeu_ly_lich', 'anh_bang_cap', 'giay_kham_suc_khoe', 'anh_khac'
    ];
    foreach ($file_fields as $file_key) {
        if (!empty($_FILES[$file_key]['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $uploaded = wp_handle_upload($_FILES[$file_key], ['test_form' => false]);
            if (!isset($uploaded['error'])) {
                update_user_meta($user_id, $file_key, esc_url_raw($uploaded['url']));
            }
        }
    }
}

add_action('personal_options_update', 'uh_save_custom_user_fields');
add_action('edit_user_profile_update', 'uh_save_custom_user_fields');

function uh_show_custom_fields_on_new_user($operation) {
    uh_show_custom_user_fields((object)[]);
}

add_action('user_new_form', 'uh_show_custom_fields_on_new_user');

function uh_save_custom_fields_on_register($user_id) {
    uh_save_custom_user_fields($user_id);
}

add_action('user_register', 'uh_save_custom_fields_on_register');

add_action('user_edit_form_tag', 'uh_add_enctype_to_user_form');
add_action('show_user_profile', 'uh_add_enctype_to_user_form'); // For own profile

function uh_add_enctype_to_user_form() {
    echo ' enctype="multipart/form-data"';
}

// upload avatar
function uh_show_avatar_upload_field($user) {
    $avatar_url = get_user_meta($user->ID, 'custom_avatar', true);
    echo '<h3>Ảnh đại diện</h3><table class="form-table">';
    echo '<tr><th><label for="custom_avatar">Tải ảnh đại diện</label></th><td>';
    if ($avatar_url) {
        echo '<img src="' . esc_url($avatar_url) . '" style="max-width:100px;"><br>';
    }
    echo '<input type="file" name="custom_avatar" accept="image/*" /></td></tr></table>';
}
add_action('show_user_profile', 'uh_show_avatar_upload_field');
add_action('edit_user_profile', 'uh_show_avatar_upload_field');
function uh_save_avatar_upload($user_id) {
    if (!current_user_can('edit_user', $user_id)) return;

    if (!empty($_FILES['custom_avatar']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $upload = wp_handle_upload($_FILES['custom_avatar'], ['test_form' => false]);
        if (!isset($upload['error'])) {
            update_user_meta($user_id, 'custom_avatar', esc_url_raw($upload['url']));
        }
    }
}
add_action('personal_options_update', 'uh_save_avatar_upload');
add_action('edit_user_profile_update', 'uh_save_avatar_upload');
function uh_custom_avatar($avatar, $id_or_email) {
    $user = false;

    if (is_numeric($id_or_email)) {
        $user = get_user_by('id', $id_or_email);
    } elseif (is_object($id_or_email) && isset($id_or_email->user_id)) {
        $user = get_user_by('id', $id_or_email->user_id);
    } elseif (is_string($id_or_email)) {
        $user = get_user_by('email', $id_or_email);
    }

    if ($user) {
        $custom_avatar = get_user_meta($user->ID, 'custom_avatar', true);
        if ($custom_avatar) {
            $avatar = "<img alt='' src='" . esc_url($custom_avatar) . "' class='avatar avatar-96 photo' height='96' width='96' />";
        }
    }

    return $avatar;
}
add_filter('get_avatar', 'uh_custom_avatar', 10, 5);

// hide gavatar
add_action('admin_head-user-edit.php', 'uh_hide_default_avatar');
add_action('admin_head-profile.php', 'uh_hide_default_avatar');

function uh_hide_default_avatar() {
    echo '<style>
        .user-profile-picture { display: none !important; }
    </style>';
}


//hoat dong user trong dashboard
// Lưu số lần đăng nhập mỗi khi người dùng đăng nhập
function track_user_login( $user_login, $user ) {
    $user_id = $user->ID;
    $last_login = get_user_meta( $user_id, 'last_login', true );
    $login_count = get_user_meta( $user_id, 'login_count', true );

    // Nếu chưa từng có login_count, khởi tạo là 0
    if ( empty($login_count) || !is_numeric($login_count) ) {
        $login_count = 0;
    } else {

        $login_count = (int) $login_count;
    }

    // Lấy thời gian hiện tại và xác định tuần hiện tại
    $current_time = current_time( 'timestamp' );
    $week_start = strtotime( 'last sunday midnight', $current_time );

    // Nếu lần đăng nhập trước không thuộc tuần này => reset login_count
    if ( !empty($last_login) && $last_login < $week_start ) {
        $login_count = 1;
    } else {
        $login_count++;
    }

    // Lưu số lần đăng nhập và thời gian đăng nhập
    update_user_meta( $user_id, 'login_count', $login_count );
    update_user_meta( $user_id, 'last_login', $current_time );
}
add_action( 'wp_login', 'track_user_login', 10, 2 );



// Tạo biểu đồ trên dashboard
function render_user_login_activity_chart() {
    global $wpdb;

    // Lấy dữ liệu người dùng đăng nhập trong tuần
    $results = $wpdb->get_results( "
        SELECT user_id, meta_value AS login_count
        FROM {$wpdb->prefix}usermeta
        WHERE meta_key = 'login_count'
    " );

    $user_data = [];
    foreach ( $results as $result ) {
        $user_info = get_userdata( $result->user_id );
        $user_data[] = [
            'label' => $user_info->user_login,
            'data' => $result->login_count,
        ];
    }

    // Nếu không có dữ liệu, không vẽ biểu đồ
    if ( empty( $user_data ) ) {
        echo '<p>Không tìm thấy người dùng nào đăng nhập trong tuần.</p>';
        return;
    }

    // Hiển thị biểu đồ với kiểu giao diện đẹp hơn
    ?>
    <div id="user_login_activity_chart" style="background: #f9f9f9; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

        <canvas id="user-login-chart" width="100%"></canvas>
    </div>

    <style>
        /* CSS nâng cao cho widget */
        #user_login_activity_chart {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        }

        #user-login-chart {
            margin-top: 20px;
        }

        .wrap h2 {
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var ctx = document.getElementById('user-login-chart').getContext('2d');

            var gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(75,192,192,0.6)');
            gradient.addColorStop(1, 'rgba(75,192,192,0)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode( array_column( $user_data, 'label' ) ); ?>,
                    datasets: [{
                        label: 'Số lần đăng nhập',
                        data: <?php echo json_encode( array_column( $user_data, 'data' ) ); ?>,
                        backgroundColor: gradient,
                        borderColor: 'rgba(75,192,192,1)',
                        borderWidth: 2,
                        borderRadius: 8,
                        hoverBackgroundColor: 'rgba(75,192,192,0.8)',
                        hoverBorderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: { color: '#333', font: { size: 14 } }
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#333',
                            bodyColor: '#000',
                            borderColor: '#ccc',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#333' },
                            grid: { color: '#eee' }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#333' },
                            grid: { color: '#eee' }
                        }
                    }
                }
            });
        });
    </script>
    <?php
}

// Đăng widget trên dashboard
function add_user_login_activity_chart_to_dashboard() {
    wp_add_dashboard_widget(
        'user_login_activity_chart',
        'Biểu Đồ Hoạt Động Đăng Nhập Của Nhân Viên Trong Tuần',
        'render_user_login_activity_chart'
    );
}
add_action( 'wp_dashboard_setup', 'add_user_login_activity_chart_to_dashboard' );


// chart thống kê


add_action('wp_dashboard_setup', 'custom_dashboard_product_stats');

function custom_dashboard_product_stats() {
    global $wp_meta_boxes;

    // Widget 1 - Loại sản phẩm
    wp_add_dashboard_widget(
        'custom_product_stats_widget',
        'Thống kê Sản phẩm theo Loại',
        'render_custom_product_stats'
    );

    // Widget 2 - Loại bất động sản
    wp_add_dashboard_widget(
        'custom_property_stats_widget',
        'Thống kê Bất động sản theo Loại',
        'render_property_type_stats_widget'
    );

    // Widget 3 - Loại giao dịch (tạm add vào normal để sau chuyển sang side)
    wp_add_dashboard_widget(
        'custom_transaction_stats_widget',
        'Thống kê Giao dịch theo Loại',
        'render_transaction_type_stats_widget'
    );

    // Di chuyển widget giao dịch sang cột bên phải (side)
    if (isset($wp_meta_boxes['dashboard']['normal']['core']['custom_transaction_stats_widget'])) {
        $widget = $wp_meta_boxes['dashboard']['normal']['core']['custom_transaction_stats_widget'];
        unset($wp_meta_boxes['dashboard']['normal']['core']['custom_transaction_stats_widget']);
        $wp_meta_boxes['dashboard']['side']['core']['custom_transaction_stats_widget'] = $widget;
    }
}




function render_custom_product_stats() {
    global $wpdb;

    // Thêm CSS cho widget
    echo '<style>
        #custom_product_stats_widget {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        }
    </style>';

    // Lấy dữ liệu từ database
    $results = $wpdb->get_results("
        SELECT product_type, giaban
        FROM wp_dulieunhadat
        WHERE product_type IN ('517', '515')
    ");

    $stats = [
        '517' => ['label' => 'Dự án', 'count' => 0, 'total_price' => 0],
        '515' => ['label' => 'Nhà ở riêng lẻ', 'count' => 0, 'total_price' => 0],
    ];

    foreach ($results as $row) {
        $product_type = $row->product_type;
        $price_str = trim($row->giaban);
        $price = 0;

        if (is_numeric($price_str)) {
            $price = floatval($price_str) / 1000000; // chuyển về triệu
        } else {
            $price_str = strtolower($price_str);
            if (strpos($price_str, 'tỷ') !== false) {
                $price = floatval(str_replace(',', '.', str_replace('tỷ', '', $price_str))) * 1000;
            } elseif (strpos($price_str, 'triệu') !== false) {
                $price = floatval(str_replace(',', '.', str_replace('triệu', '', $price_str)));
            }
        }

        $stats[$product_type]['count']++;
        $stats[$product_type]['total_price'] += $price;
    }

    // Hiển thị bảng
    echo '<div id="custom_product_stats_widget">';
    echo '<table class="widefat striped">';
    echo '<thead><tr><th>Loại sản phẩm</th><th>Số lượng</th><th>Tổng giá bán</th></tr></thead><tbody>';

    $labels = [];
    $counts = [];
    $prices = [];
    $price_labels = []; // dùng để hiển thị giá trị trên biểu đồ

    foreach ($stats as $type => $data) {
        echo '<tr>';
        echo '<td>' . esc_html($data['label']) . '</td>';
        echo '<td>' . esc_html($data['count']) . '</td>';

        if ($data['total_price'] >= 1000) {
            $display = number_format($data['total_price'] / 1000, 2, ',', '.') . ' tỷ';
            $price_labels[] = round($data['total_price'] / 1000, 2);
        } else {
            $display = number_format($data['total_price'], 0, ',', '.') . ' triệu';
            $price_labels[] = round($data['total_price'], 0);
        }

        echo '<td>' . $display . '</td>';
        echo '</tr>';

        $labels[] = $data['label'];
        $counts[] = $data['count'];
        $prices[] = $data['total_price'];
    }

    echo '</tbody></table>';

    // Vẽ biểu đồ
    echo '<canvas id="product-stats-chart" height="100" style="margin-top:20px;"></canvas>';
    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
    echo '<script>
        const ctx = document.getElementById("product-stats-chart").getContext("2d");
        const rawPrices = ' . json_encode($prices) . ';
        const adjustedPrices = rawPrices.map(p => p >= 1000 ? (p / 1000).toFixed(2) : p);
        const priceUnit = rawPrices.some(p => p >= 1000) ? "tỷ" : "triệu";

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ' . json_encode($labels) . ',
                datasets: [{
                    label: "Số lượng sản phẩm",
                    data: ' . json_encode($counts) . ',
                    backgroundColor: "rgba(54, 162, 235, 0.6)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: "rgba(75,192,192,0.8)",
                    hoverBorderWidth: 3
                }, {
                    label: "Tổng giá bán (" + priceUnit + ")",
                    data: adjustedPrices,
                    backgroundColor: "rgba(255, 99, 132, 0.6)",
                    borderColor: "rgba(255, 99, 132, 1)",
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: "rgba(255, 99, 132, 0.8)",
                    hoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + " " + priceUnit;
                            }
                        }
                    }
                }
            }
        });
    </script>';
    echo '</div>';
}


function render_property_type_stats_widget() {
    global $wpdb;


    // Thêm CSS cho widget
    echo '<style>
        #custom_property_stats_widget {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        }
    </style>';

    // Lấy dữ liệu từ bảng wp_dulieunhadat với property_type
    $results = $wpdb->get_results("SELECT property_type FROM wp_dulieunhadat");

    // Khởi tạo mảng thống kê cho các loại bất động sản
    $stats = [];

    // Duyệt qua các bản ghi trả về và tính toán số lượng
    foreach ($results as $row) {
        $type = $row->property_type;

        // Kiểm tra nếu loại bất động sản chưa có trong mảng thống kê
        if (!isset($stats[$type])) {
            // Lấy tên loại bất động sản từ taxonomy (category) dựa trên ID
            $term = get_term($type, 'category');  // 'property_type' là tên taxonomy

            // Nếu term không có giá trị (ví dụ không có loại bất động sản trong taxonomy), gán tên là "Không rõ"
            $stats[$type] = [
                'label' => $term && !is_wp_error($term) ? $term->name : 'Không rõ', // Tên loại bất động sản
                'count' => 0 // Số lượng
            ];
        }

        // Cập nhật số lượng
        $stats[$type]['count']++;
    }

    // Hiển thị widget
    echo '<div id="custom_property_stats_widget" class="postbox">';
    echo '<div class="inside">';
    echo '<table class="widefat striped"><thead><tr><th>Loại BĐS</th><th>Số lượng</th></tr></thead><tbody>';

    // Mảng để vẽ biểu đồ
    $labels = $counts = [];

    // Duyệt qua thống kê và hiển thị bảng
    foreach ($stats as $data) {
        echo '<tr><td>' . esc_html($data['label']) . '</td><td>' . $data['count'] . '</td></tr>';
        $labels[] = $data['label'];
        $counts[] = $data['count'];
    }

    echo '</tbody></table>';

    // Vẽ biểu đồ ngay trong cùng hàm
    echo '<canvas id="property_type_chart" height="100" style="margin-top:20px;"></canvas>';
    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
    echo '<script>
        new Chart(document.getElementById("property_type_chart").getContext("2d"), {
            type: "bar",
            data: {
                labels: ' . json_encode($labels) . ',
                datasets: [{
                    label: "Số lượng sản phẩm",
                    data: ' . json_encode($counts) . ',
                    backgroundColor: "rgba(255, 159, 64, 0.6)", /* Màu cam */
                    borderColor: "rgba(255, 159, 64, 1)", /* Màu cam */
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>';

    echo '</div></div>';
}

function render_transaction_type_stats_widget() {
    global $wpdb;
    // Thêm CSS cho widget
    echo '<style>
        #custom_transaction_stats_widget {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        }
    </style>';
    // Lấy dữ liệu từ bảng wp_dulieunhadat với transaction_type
    $results = $wpdb->get_results("SELECT transaction_type FROM wp_dulieunhadat");

    // Khởi tạo mảng thống kê
    $stats = [];

    // Duyệt qua các bản ghi trả về và tính toán số lượng
    foreach ($results as $row) {
        $type = $row->transaction_type;

        if (!isset($stats[$type])) {
            $term = get_term($type, 'category'); // Lấy tên từ taxonomy 'category'
            $stats[$type] = [
                'label' => $term && !is_wp_error($term) ? $term->name : 'Không rõ',
                'count' => 0
            ];
        }

        $stats[$type]['count']++;
    }

    // Hiển thị widget
    echo '<div id="custom_transaction_stats_widget" class="postbox">';
    echo '<div class="inside">';
    echo '<table class="widefat striped"><thead><tr><th>Loại giao dịch</th><th>Số lượng</th></tr></thead><tbody>';

    $labels = $counts = [];

    foreach ($stats as $data) {
        echo '<tr><td>' . esc_html($data['label']) . '</td><td>' . $data['count'] . '</td></tr>';
        $labels[] = $data['label'];
        $counts[] = $data['count'];
    }

    echo '</tbody></table>';

    echo '<canvas id="transaction_type_chart" height="100" style="margin-top:20px;"></canvas>';
    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
    echo '<script>
        new Chart(document.getElementById("transaction_type_chart").getContext("2d"), {
            type: "bar",
            data: {
                labels: ' . json_encode($labels) . ',
                datasets: [{
                    label: "Số lượng giao dịch",
                    data: ' . json_encode($counts) . ',
                    backgroundColor: "rgba(255, 99, 132, 0.6)", // Màu hồng đỏ
                    borderColor: "rgba(255, 99, 132, 1)",
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>';

    echo '</div></div>';
}



// Ẩn bảng Welcome Panel và xóa checkbox trong Screen Options
function hide_welcome_panel_for_all_users() {
    // Ẩn bảng chào mừng
    remove_action('welcome_panel', 'wp_welcome_panel');

    // Ẩn checkbox trong screen options
    echo '<style>
        #welcome-panel { display: none !important; }
        input[name="wp_welcome_panel"] { display: none !important; }
        label[for="wp_welcome_panel"] { display: none !important; }
    </style>';
}
add_action('admin_head-index.php', 'hide_welcome_panel_for_all_users');




// ẩn tất cả dashboard
function hide_all_dashboard_widgets_except_custom_ones() {
    global $wp_meta_boxes;

    foreach ( $wp_meta_boxes['dashboard'] as $context => $widgets ) {
        foreach ( $widgets as $priority => $widget_group ) {
            foreach ( $widget_group as $widget_id => $widget ) {
                if ( !in_array( $widget_id, ['custom_product_stats_widget', 'user_login_activity_chart',
                    'custom_property_stats_widget','custom_transaction_stats_widget'] ) ) {
                    unset( $wp_meta_boxes['dashboard'][$context][$priority][$widget_id] );
                }
            }
        }
    }
}
add_action( 'wp_dashboard_setup', 'hide_all_dashboard_widgets_except_custom_ones', 100 );

//hide language select in login
// Ẩn dropdown chọn ngôn ngữ
add_action('login_head', function () {
    ?>
    <style>
        .language-switcher {
            display: none !important;
        }
    </style>
    <?php
});

// Ẩn liên kết "← Quay lại trang chủ"
add_filter('login_footer', function () {
    ?>
    <style>
        #backtoblog {
            display: none !important;
        }
    </style>
    <?php
});

// Ép ngôn ngữ mặc định là tiếng Việt
add_action('init', function () {
    if (!is_user_logged_in()) {
        switch_to_locale('vi');
    }
});




add_action('login_enqueue_scripts', function () {
    ?>
    <style>
        /* Nút đăng nhập */
        #wp-submit {
            background-color: #008000 !important; /* Màu xanh dương */
            border-color: #008000 !important;
            color: #fff !important;
            font-weight: bold;
        }

        #wp-submit:hover {
            background-color: #ffa500 !important;
            border-color: #ffa500 !important;
        }
    </style>
    <?php
});

// them truong long lat hien thi ra api cho user
add_action('rest_api_init', function () {
    register_rest_field('user', 'location', [
        'get_callback' => function ($user) {
            return [
                'lat' => get_user_meta($user['id'], 'last_latitude', true),
                'lng' => get_user_meta($user['id'], 'last_longitude', true),
            ];
        },
        'update_callback' => function ($value, $user, $field_name) {
            if (isset($value['lat'])) {
                update_user_meta($user->ID, 'last_latitude', $value['lat']);
            }
            if (isset($value['lng'])) {
                update_user_meta($user->ID, 'last_longitude', $value['lng']);
            }
        },
        'schema' => null,
    ]);
});

add_action('rest_api_init', function () {

    register_rest_route('spiritwebs/v1', '/user-locations', [
        'methods' => 'GET',
        'callback' => function () {
            $users = get_users();
            $result = [];
            foreach ($users as $user) {
                $lat = get_user_meta($user->ID, 'last_latitude', true);
                $lng = get_user_meta($user->ID, 'last_longitude', true);

                if ($lat && $lng) {
                    $result[] = [
                        'id'        => $user->ID,
                        'username'  => $user->user_login,   // 👈 thêm username
                        'name'      => $user->display_name,
                        'email'     => $user->user_email,   // 👈 thêm email
                        'latitude'  => (float) $lat,
                        'longitude' => (float) $lng,
                    ];
                }


            }
            return $result;
        },
        'permission_callback' => '__return_true', // nếu muốn public
    ]);
});


add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/update-location', array(
        'methods' => 'POST',
        'callback' => function ($request) {
            $params = $request->get_json_params();
            $lat = isset($params['latitude']) ? floatval($params['latitude']) : null;
            $lng = isset($params['longitude']) ? floatval($params['longitude']) : null;
            $user_id = isset($params['user_id']) ? intval($params['user_id']) : null;

            if ($lat === null || $lng === null || !$user_id) {
                return new WP_Error('invalid_data', 'Thiếu latitude, longitude hoặc user_id', array('status' => 400));
            }

            // Lấy user theo ID
            $user = get_user_by('ID', $user_id);
            if (!$user) {
                return new WP_Error('user_not_found', 'User không tồn tại', array('status' => 404));
            }

            $current_user = wp_get_current_user();
            $is_admin = user_can($current_user, 'administrator');

            // Kiểm tra quyền: admin có thể update bất kỳ user, user thường chỉ update chính họ
            if (!$is_admin && $current_user->ID !== $user->ID) {
                return new WP_Error('forbidden', 'Bạn không có quyền update user này', array('status' => 403));
            }

            update_user_meta($user->ID, 'last_latitude', $lat);
            update_user_meta($user->ID, 'last_longitude', $lng);

            return array(
                'success' => true,
                'user_id' => $user->ID,
                'lat' => $lat,
                'lng' => $lng,
            );
        },
        'permission_callback' => '__return_true', // nếu dùng JWT, có thể validate token ở đây
    ));
});


add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/get-messages', array(
        'methods' => 'POST',
        'callback' => function ($request) {
            global $wpdb;
            $params = $request->get_json_params();

            $user1_id = intval($params['user1_id'] ?? 0);
            $user2_id = intval($params['user2_id'] ?? 0);

            if (!$user1_id || !$user2_id) {
                return new WP_Error('invalid_data', 'Thiếu user ID', array('status' => 400));
            }

            $table = $wpdb->prefix . 'chat_messages';

            $messages = $wpdb->get_results(
                $wpdb->prepare("
                    SELECT id, sender_id, receiver_id, message, file_url, file_name, created_at
                    FROM {$table}
                    WHERE (sender_id=%d AND receiver_id=%d)
                       OR (sender_id=%d AND receiver_id=%d)
                    ORDER BY created_at ASC
                ", $user1_id, $user2_id, $user2_id, $user1_id),
                ARRAY_A
            );

            $data = [];

            foreach ($messages as $msg) {
                $sender_id = intval($msg['sender_id']);
                $sender_user = get_userdata($sender_id);
                if (!$sender_user) continue;

                // ✅ Avatar
                $avatar_url = get_user_meta($sender_id, 'avatar_url', true);
                if (!$avatar_url) {
                    $avatar_url = get_avatar_url($sender_id);
                }

                // ✅ Chuẩn hoá file_url
                $file_url = trim($msg['file_url'] ?? '');
                if ($file_url && !preg_match('#^https?://#i', $file_url)) {
                    $file_url = site_url($file_url);
                }

                // ✅ Chuẩn hoá file_name (tự cắt từ url nếu rỗng)
                $file_name = trim($msg['file_name'] ?? '');
                if (!$file_name && $file_url) {
                    $path = parse_url($file_url, PHP_URL_PATH);
                    $file_name = basename($path);
                }

                // ✅ Detect type: text | image | file
                $type = 'text';

                if ($file_url) {
                    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $image_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                    if (in_array($ext, $image_exts)) {
                        $type = 'image';
                    } else {
                        $type = 'file';
                    }
                }

                $data[] = [
                    'id'            => intval($msg['id']),
                    'sender_id'     => $sender_id,
                    'receiver_id'   => intval($msg['receiver_id']),
                    'sender_name'   => $sender_user->display_name ?? '',
                    'avatar_url'    => $avatar_url,
                    'message'       => $msg['message'] ?? '',
                    'file_url'      => $file_url,
                    'file_name'     => $file_name,
                    'type'          => $type,
                    'created_at'    => (new DateTime($msg['created_at'], new DateTimeZone('UTC')))
                        ->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'))
                        ->format('Y-m-d H:i:s'),
                ];
            }

            return [
                'success' => true,
                'messages' => $data
            ];
        },
        'permission_callback' => '__return_true',
    ));
});




add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/send-message', array(
        'methods' => 'POST',
        'callback' => function ($request) {
            global $wpdb;

            // Lấy dữ liệu JSON
            $params = $request->get_json_params();
            $sender_id = isset($params['sender_id']) ? intval($params['sender_id']) : 0;
            $receiver_id = isset($params['receiver_id']) ? intval($params['receiver_id']) : 0;
            $message = isset($params['message']) ? sanitize_text_field($params['message']) : '';

            // Check dữ liệu
            if (!$sender_id || !$receiver_id || !$message) {
                error_log("Send message failed: invalid data | sender_id=$sender_id, receiver_id=$receiver_id, message=$message");
                return new WP_Error('invalid_data', 'Thiếu dữ liệu', array('status' => 400));
            }

            $table = $wpdb->prefix . "chat_messages";

            // Insert vào DB
            $inserted = $wpdb->insert($table, [
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'message' => $message,
                'created_at' => current_time('mysql')
            ]);

            if ($inserted === false) {
                error_log("Send message failed: DB error | " . $wpdb->last_error);
                return new WP_Error('db_error', 'Lưu tin nhắn thất bại: ' . $wpdb->last_error, ['status' => 500]);
            }

            error_log("Message saved successfully | sender_id=$sender_id, receiver_id=$receiver_id, message=$message");


            $message_id = $wpdb->insert_id;

            // ------------------- Gọi Phoenix (domain + key giống Phoenix) -------------------
            $phoenix_url = "https://socket.spiritwebs.com/api/new_chat_message";
            $api_key = "keydungkhithemsanphamhienthinotificationtoapp"; // giống bên Phoenix

            $payload = [
                "chat" => [
                    "id" => $message_id,
                    "sender_id" => $sender_id,
                    "receiver_id" => $receiver_id,
                    "message" => $message,
                    "created_at" => current_time('mysql')
                ],
                "api_key" => $api_key
            ];

            wp_remote_post($phoenix_url, [
                'method' => 'POST',
                'timeout' => 1,
                'headers' => ['Content-Type' => 'application/json'],
                'body' => wp_json_encode($payload)
            ]);


            return [
                'success' => true,
                'id' => $message_id,   // ✅ ID database thật
            ];

        },
        'permission_callback' => '__return_true',
    ));
});


add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/save-pub', array(
        'methods' => 'POST',
        'callback' => 'spiritwebs_save_pub',
        'permission_callback' => '__return_true',
    ));
});

function spiritwebs_save_pub($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'pubs';

    $params = $request->get_json_params();
    $name = sanitize_text_field($params['name'] ?? '');
    $lat = floatval($params['lat'] ?? 0);
    $lng = floatval($params['lng'] ?? 0);
    $type = sanitize_text_field($params['type'] ?? 'pub');
    $rating = floatval($params['rating'] ?? 0);
    $osm_id = intval($params['osm_id'] ?? 0);

    if (!$name || !$lat || !$lng) {
        return new WP_Error('invalid_data', 'Thiếu tên hoặc tọa độ', array('status' => 400));
    }
    // Lấy địa chỉ từ tag addr:*
    $address = '';
    if (!empty($params['tags']) && is_array($params['tags'])) {
        $addrParts = [];
        foreach ($params['tags'] as $key => $val) {
            if (strpos($key, 'addr:') === 0 && !empty($val)) {
                $addrParts[] = sanitize_text_field($val);
            }
        }
        $address = implode(', ', $addrParts);
    }

    // Kiểm tra trùng tọa độ
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE ABS(lat - %f) < 0.00001 AND ABS(lng - %f) < 0.00001",
        $lat, $lng
    ));

    if ($exists > 0) {
        return array('status' => 'exists', 'message' => 'Quán đã tồn tại');
    }

    $wpdb->insert($table, [
        'name' => $name,
        'lat' => $lat,
        'lng' => $lng,
        'type' => $type,
        'rating' => $rating,
        'osm_id' => $osm_id,
        'address' => $address,
        'created_at' => current_time('mysql'),
        'updated_at' => current_time('mysql'),
    ]);

    return array('status' => 'success', 'message' => 'Đã lưu quán thành công');
}

add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/existing-pubs', [
        'methods' => 'GET',
        'callback' => 'sw_get_existing_pubs',
        'permission_callback' => function ($request) {
            $auth = $request->get_header('Authorization');
            if (!$auth || strpos($auth, 'Bearer ') !== 0)
                return new WP_Error('unauthorized', 'Token missing', ['status' => 401]);

            $token = trim(str_replace('Bearer ', '', $auth));
            // TODO: validate JWT token nếu cần
            return true;
        },
    ]);
});
function sw_get_existing_pubs($request) {
    global $wpdb;

    $pub_table = $wpdb->prefix . 'pubs';
    $cat_table = $wpdb->prefix . 'foody_categories';

    $results = $wpdb->get_results("
        SELECT 
            p.name AS name,
            p.lat,
            p.lng,
            p.address,
            p.picture_path_large AS image,  -- ảnh chính
            p.avg_rating,
            p.foody_data,
            c.name AS type
        FROM $pub_table p
        LEFT JOIN $cat_table c ON p.type = c.id WHERE p.status = 1
    ", ARRAY_A);

    $output = [];

    foreach ($results as $row) {
        $foody = json_decode($row['foody_data'], true);

        $images = $foody['images'] ?? []; // tất cả ảnh từ foody_data

        $output[] = [
            'name' => $row['name'],
            'lat' => $row['lat'],
            'lng' => $row['lng'],
            'address' => $row['address'],
            'avg_rating' => $row['avg_rating'],
            'type' => $row['type'],
            'image' => $row['image'],    // ảnh chính từ picture_path_large
            'images' => $images,         // tất cả ảnh từ foody_data

            // Thêm các trường còn thiếu
            'open_time' => $foody['open_time'] ?? '',
            'price_range' => $foody['price_range'] ?? '',
            'ratings' => $foody['ratings'] ?? [],
        ];
    }


    return rest_ensure_response($output);
}


function sw_get_existing_pubs_bk($request) {
    global $wpdb;

    $pub_table = $wpdb->prefix . 'pubs'; // wp_pubs
    $cat_table = $wpdb->prefix . 'foody_categories'; // wp_foody_categories

    $results = $wpdb->get_results("
        SELECT 
            p.name AS name,
            p.lat,
            p.lng,
            p.address,
            p.picture_path_large as image,
            p.avg_rating,
            c.name AS type
        FROM $pub_table p
        LEFT JOIN $cat_table c ON p.type = c.id
    ", ARRAY_A);

    return rest_ensure_response($results);
}




// dang ky user
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/users/register', [
        'methods' => 'POST',
        'callback' => 'spiritwebs_register_user',
        'permission_callback' => '__return_true', // Cho phép truy cập public
    ]);
});

function spiritwebs_register_user(WP_REST_Request $request) {
    $username = sanitize_text_field($request['username']);
    $email = sanitize_email($request['email']);
    $password = $request['password'];

    // Kiểm tra thiếu thông tin
    if (empty($username) || empty($email) || empty($password)) {
        return new WP_REST_Response([
            'message' => 'Thiếu thông tin đăng ký'
        ], 400);
    }

    // Kiểm tra username hoặc email đã tồn tại
    if (username_exists($username) || email_exists($email)) {
        return new WP_REST_Response([
            'message' => 'Tài khoản hoặc email đã tồn tại'
        ], 400);
    }

    // Tạo user mới
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        return new WP_REST_Response([
            'message' => 'Không thể tạo tài khoản: ' . $user_id->get_error_message()
        ], 500);
    }

    // Gán role mặc định là "subscriber"
    wp_update_user([
        'ID' => $user_id,
        'role' => 'subscriber'
    ]);

    // =========================
    // 🔥 ADD TRUST SCORE Ở ĐÂY
    // =========================
    update_user_meta($user_id, 'trust_score', 50);

    // (optional) thêm luôn hệ thống base
    update_user_meta($user_id, 'level', 'newbie');
    update_user_meta($user_id, 'join_count', 0);

    // Lấy thông tin user vừa tạo
    $user = get_user_by('id', $user_id);

    return new WP_REST_Response([
        'message' => 'Đăng ký thành công!',
        'user_id' => $user_id,
        'username' => $user->user_login,
        'email' => $user->user_email,
        'trust_score' => 50
    ], 201);
}



add_action('rest_api_init', function() {
    register_rest_route('custom/v1', '/user/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => function($data){
            $user_id = $data['id'];
            $user = get_userdata($user_id);
            if(!$user) return new WP_Error('no_user', 'User not found', array('status' => 404));
            return array(
                'id' => $user->ID,
                'username' => $user->user_login,
                'display_name' => $user->display_name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->user_email,
                'roles' => $user->roles,
                'description' => $user->description,
                'registered_date' => $user->user_registered,
            );
        },
        'permission_callback' => '__return_true',

    ));
});


add_action('rest_api_init', function() {
    register_rest_route('spiritwebs/v1', '/get-chat-list', [
        'methods' => 'POST',
        'callback' => 'spiritwebs_get_chat_list', // tên hàm
        'permission_callback' => '__return_true',
    ]);
});

function spiritwebs_get_chat_list($request) {
    global $wpdb;

    $params = $request->get_json_params();
    $user_id = intval($params['user_id'] ?? 0);

    if (!$user_id) {
        return new WP_Error('missing_user_id', 'Thiếu user_id', ['status' => 400]);
    }

    $table = $wpdb->prefix . 'chat_messages';

    $results = $wpdb->get_results($wpdb->prepare("
        SELECT cm1.sender_id, cm1.receiver_id, cm1.message AS last_message, cm1.created_at AS updated_at
        FROM $table cm1
        INNER JOIN (
            SELECT 
                CASE 
                    WHEN sender_id = %d THEN receiver_id
                    ELSE sender_id
                END AS target_id,
                MAX(created_at) AS max_created
            FROM $table
            WHERE (sender_id = %d OR receiver_id = %d) AND sender_id != receiver_id
            GROUP BY target_id
        ) cm2 ON (
            ((cm1.sender_id = %d AND cm1.receiver_id = cm2.target_id) 
              OR (cm1.receiver_id = %d AND cm1.sender_id = cm2.target_id))
            AND cm1.created_at = cm2.max_created
            AND (cm1.sender_id = %d OR cm1.receiver_id = %d)
        )
        ORDER BY cm1.created_at DESC
    ", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id), ARRAY_A);

    $chats = [];
    foreach ($results as $chat) {
        $target_id = ($chat['sender_id'] == $user_id) ? $chat['receiver_id'] : $chat['sender_id'];
        $target_user = get_userdata($target_id);

        if ($target_user) {
            $avatar_url = get_user_meta($target_id, 'avatar_url', true);
            if (!$avatar_url) {
                $avatar_url = get_avatar_url($target_id);
            }

            $chats[] = [
                'target_id'     => $target_id,
                'target_name'   => $target_user->display_name,
                'target_email'  => $target_user->user_email,
                'last_message'  => $chat['last_message'],
                'updated_at'    => (new DateTime($chat['updated_at'], new DateTimeZone('UTC')))
                    ->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'))
                    ->format('Y-m-d H:i:s'), // giờ +7
                'avatar_url'    => $avatar_url,
            ];
        }
    }

    return ['chats' => $chats];
}

/**
 * === API cho app tìm bạn nhậu ===
 * Lấy danh sách người tham gia & tham gia lời mời
 */
add_action('rest_api_init', function () {

    // 1️⃣ API: Lấy danh sách người tham gia
    register_rest_route('custom/v1', '/invite/(?P<id>\d+)/participants', [
        'methods' => 'GET',
        'callback' => 'spiritwebs_get_invite_participants',
        'permission_callback' => '__return_true',
    ]);

    // 2️⃣ API: Tham gia lời mời
    register_rest_route('custom/v1', '/invite/(?P<id>\d+)/join', [
        'methods' => 'POST',
        'callback' => 'spiritwebs_join_invite',
        'permission_callback' => '__return_true',
    ]);
});
// 1️⃣ Hàm lấy danh sách người tham gia
function spiritwebs_get_invite_participants($request) {
    $invite_id = intval($request['id']);

    // Lấy dữ liệu meta (chỉ lưu user_id)
    $participants = get_post_meta($invite_id, 'participants', true);
    if (!$participants) $participants = [];

    $result = [];

    foreach ($participants as $p) {
        $user_id = intval($p['user_id']);
        $user_info = get_userdata($user_id);

        if ($user_info) {
            $result[] = [
                'user_id' => $user_id,
                'name' => $user_info->display_name, // ✅ luôn tên mới nhất
                'avatar' => get_avatar_url($user_id),
                'joined_at' => $p['joined_at'] ?? '', // giữ ngày tham gia nếu cần
            ];
        }
    }

    return [
        'success' => true,
        'participants' => $result
    ];
}


function spiritwebs_join_invite($request) {
    $invite_id = intval($request['id']);
    $user_id = get_current_user_id();

    if (!$user_id) {
        return new WP_Error('not_logged_in', 'Bạn cần đăng nhập để tham gia.', ['status' => 401]);
    }

    // Lấy danh sách hiện có (chỉ user_id + joined_at)
    $participants = get_post_meta($invite_id, 'participants', true);
    if (!$participants) $participants = [];

    // Lấy slots hiện tại
    $slots = intval(get_post_meta($invite_id, 'slots', true));

    if ($slots <= 0) {
        return [
            'success' => false,
            'message' => 'Đã hết chỗ trống.'
        ];
    }

    // Kiểm tra đã tham gia chưa
    foreach ($participants as $p) {
        if (isset($p['user_id']) && $p['user_id'] == $user_id) {
            return [
                'success' => false,
                'message' => 'Bạn đã tham gia lời mời này rồi.'
            ];
        }
    }

    // Thêm user mới (chỉ lưu user_id + thời gian tham gia)
    $participants[] = [
        'user_id' => $user_id,
        'joined_at' => current_time('mysql'),
    ];

    // Cập nhật meta participants
    update_post_meta($invite_id, 'participants', $participants);

    // Giảm slots
    update_post_meta($invite_id, 'slots', $slots - 1);

    // 🔥 Gửi notify sang Phoenix
    $phoenix_url = 'https://socket.spiritwebs.com/api/join_invite';

    $user = get_userdata($user_id);

    $payload = json_encode([
        'invite_id' => $invite_id,
        'user_id'   => $user_id,
        'username'  => $user->display_name,
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
        'message' => 'Tham gia thành công!',
        'participants' => $participants,
        'slots' => $slots - 1
    ];
}


add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/get-group-messages', [
        'methods' => 'POST',
        'callback' => 'spiritwebs_get_group_messages',
        'permission_callback' => '__return_true'
    ]);

});

function spiritwebs_get_group_messages($request) {
    global $wpdb;

    $body_params = $request->get_json_params();
    $group_id = intval($body_params['group_id'] ?? 0);

    if (!$group_id) {
        return [
            'success' => false,
            'message' => 'Thiếu group_id'
        ];
    }

    $table = $wpdb->prefix . 'group_messages';

    $messages = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id, sender_id, sender_name, message, created_at
             FROM {$table}
             WHERE group_id = %d
             ORDER BY created_at ASC",
            $group_id
        ),
        ARRAY_A
    );

    foreach ($messages as &$msg) {
        $user_id = intval($msg['sender_id']);
        if ($user_id > 0 && $userdata = get_userdata($user_id)) {
            // Lấy nickname từ user meta, fallback = display_name, fallback = sender_name
            $nickname = get_user_meta($user_id, 'nickname', true);
            $avatar = get_user_meta($user_id, 'avatar_url', true);

            $msg['nickname'] = !empty($nickname) ? $nickname : $userdata->display_name;
            $msg['avatar_url'] = !empty($avatar) ? $avatar : 'https://spiritwebs.com/media/2025/10/default-avatar.png';
        } else {
            // Không phải WP user
            $msg['nickname'] = $msg['sender_name'] ?: 'Người lạ';
            $msg['avatar_url'] = 'https://spiritwebs.com/media/2025/10/default-avatar.png';
        }
    }


    return [
        'success' => true,
        'messages' => $messages
    ];
}

// 🧠 Gửi tin nhắn nhóm
/*function spiritwebs_send_group_message($request) {
    global $wpdb;

    $params = $request->get_json_params();

    $group_id    = isset($params['group_id']) ? (int)$params['group_id'] : 0;
    $sender_id   = isset($params['sender_id']) ? (int)$params['sender_id'] : 0;
    $sender_name = sanitize_text_field($params['sender_name'] ?? 'Người lạ');
    $message     = sanitize_textarea_field($params['message'] ?? '');

    if (!$group_id || !$sender_id || !$message) {
        return [
            'success' => false,
            'message' => 'Thiếu dữ liệu cần thiết.'
        ];
    }

    $table = $wpdb->prefix . 'group_messages';

    // ✅ Thêm tin nhắn vào DB
    $inserted = $wpdb->insert($table, [
        'group_id'    => $group_id,
        'sender_id'   => $sender_id,
        'sender_name' => $sender_name,
        'message'     => $message,
        'created_at'  => current_time('mysql')
    ]);

    if ($inserted === false) {
        return [
            'success' => false,
            'message' => 'Không thể lưu tin nhắn: ' . $wpdb->last_error
        ];
    }

    // ✅ Lấy ID thực của tin nhắn vừa tạo
    $message_id = $wpdb->insert_id;

    // ✅ Chuẩn bị dữ liệu trả về
    $message_data = [
        'id'          => $message_id,
        'group_id'    => $group_id,
        'sender_id'   => $sender_id,
        'sender_name' => $sender_name,
        'message'     => $message,
        'created_at'  => current_time('mysql'),
    ];

    // Log để debug nếu cần
    error_log("✅ [spiritwebs_send_group_message] inserted ID={$message_id}");

    // ✅ Trả về đúng cấu trúc Flutter cần
    return [
        'success' => true,
        'data'    => $message_data
    ];
}*/

add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/send-group-message', [
        'methods' => 'POST',
        'callback' => function ($request) {
            global $wpdb;

            $params = $request->get_json_params();
            $group_id    = isset($params['group_id']) ? (int)$params['group_id'] : 0;
            $sender_id   = isset($params['sender_id']) ? (int)$params['sender_id'] : 0;
            $sender_name = sanitize_text_field($params['sender_name'] ?? 'Người lạ');
            $message     = sanitize_textarea_field($params['message'] ?? '');

            if (!$group_id || !$sender_id || !$message) {
                return new WP_Error('invalid_data', 'Thiếu dữ liệu cần thiết.', ['status' => 400]);
            }

            $table = $wpdb->prefix . 'group_messages';
            $inserted = $wpdb->insert($table, [
                'group_id'    => $group_id,
                'sender_id'   => $sender_id,
                'sender_name' => $sender_name,
                'message'     => $message,
                'created_at'  => current_time('mysql')
            ]);
            $message_id = $wpdb->insert_id;

            // Gom tất cả thành viên trong group
            $member_ids = $wpdb->get_col($wpdb->prepare(
                "SELECT DISTINCT sender_id FROM {$wpdb->prefix}group_messages WHERE group_id = %d",
                $group_id
            ));

            $phoenix_url = "https://socket.spiritwebs.com/api/new_group_chat_message";
            $api_key     = "keydungkhithemsanphamhienthinotificationtoapp";

            $payload = [
                "chat" => [
                    "id"          => $message_id,
                    "group_id"    => $group_id,
                    "sender_id"   => $sender_id,
                    "sender_name" => $sender_name,
                    "message"     => $message,
                    "created_at"  => current_time('mysql'),
                    "member_ids"  => $member_ids
                ]
            ];

            // Gọi Phoenix async với header x-api-key
            wp_remote_post($phoenix_url, [
                'method'  => 'POST',
                'timeout' => 1,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-api-key'    => $api_key
                ],
                'body'    => wp_json_encode($payload)
            ]);

            return [
                'success' => true,
                'data' => [
                    'id'          => $message_id,
                    'group_id'    => $group_id,
                    'sender_id'   => $sender_id,
                    'sender_name' => $sender_name,
                    'message'     => $message,
                    'created_at'  => current_time('mysql'),
                    'member_ids'  => $member_ids
                ]
            ];
        },
        'permission_callback' => '__return_true',
    ]);
});


add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/delete-group-message', [
        'methods' => 'POST',
        'callback' => 'spiritwebs_delete_group_message',
        'permission_callback' => '__return_true'
    ]);
});

function spiritwebs_delete_group_message($request) {
    global $wpdb;

    $params = $request->get_json_params();
    $message_id = isset($params['message_id']) ? intval($params['message_id']) : 0;

    if (!$message_id) {
        return ['success' => false, 'message' => 'Thiếu message_id'];
    }

    $table = $wpdb->prefix . 'group_messages';

    $deleted = $wpdb->delete($table, ['id' => $message_id]);

    if ($deleted === false) {
        return ['success' => false, 'message' => 'Xóa tin nhắn thất bại: ' . $wpdb->last_error];
    }

    return ['success' => true, 'message' => 'Tin nhắn đã bị xóa'];
}




// Đăng ký route REST API
add_action('rest_api_init', function () {
    register_rest_route('profile/v1', '/user/(?P<id>\d+)', [
        'methods' => 'POST',
        'callback' => 'update_user_profile_with_avatar',
        'permission_callback' => '__return_true',
        'args' => [
            'id' => [
                'validate_callback' => function($value, $request, $param) {
                    return is_numeric($value);
                },
            ],
        ],
    ]);
});

function update_user_profile_with_avatar($request) {
    $user_id = (int) $request['id'];
    $user = get_user_by('ID', $user_id);
    if (!$user) {
        return new WP_Error('no_user', 'Người dùng không tồn tại', ['status' => 404]);
    }

    // Cập nhật các field text
    $display_name = sanitize_text_field($request->get_param('display_name'));
    $first_name   = sanitize_text_field($request->get_param('first_name'));
    $last_name    = sanitize_text_field($request->get_param('last_name'));
    $description  = sanitize_textarea_field($request->get_param('description'));

    wp_update_user([
        'ID' => $user_id,
        'display_name' => $display_name,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'description' => $description,
    ]);

    // Cập nhật interests
    $interests_raw = $request->get_param('interests');
    $interests = [];
    if ($interests_raw) {
        if (is_string($interests_raw)) {
            $interests = json_decode($interests_raw, true);
            if (!is_array($interests)) $interests = [];
        } elseif (is_array($interests_raw)) {
            $interests = $interests_raw;
        }
    }
    update_user_meta($user_id, 'interests', $interests);

    // Xử lý avatar upload
    $avatar_url = get_user_meta($user_id, 'avatar_url', true);
    if (!empty($_FILES['avatar'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $uploaded = wp_handle_upload($_FILES['avatar'], ['test_form' => false]);
        if (!empty($uploaded['url'])) {
            $avatar_url = $uploaded['url'];
            update_user_meta($user_id, 'avatar_url', $avatar_url);
        }
    }

    // Trả về user data
    $updated_user = get_userdata($user_id);
    return [
        'id' => $updated_user->ID,
        'username' => $updated_user->user_login,
        'display_name' => $updated_user->display_name,
        'first_name' => $updated_user->first_name,
        'last_name' => $updated_user->last_name,
        'description' => $updated_user->description,
        'interests' => get_user_meta($user_id, 'interests', true),
        'avatar_url' => $avatar_url,
    ];
}


add_action('rest_api_init', function () {
    register_rest_route('profile/v1', '/user/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_user_profile',
        'permission_callback' => '__return_true',
        'args' => [
            'id' => [
                'validate_callback' => function($value, $request, $param) {
                    return is_numeric($value);
                },
            ],
        ],
    ]);
});

function get_user_profile($request) {
    $user_id = (int) $request['id'];
    $user = get_user_by('ID', $user_id);
    if (!$user) {
        return new WP_Error('no_user', 'Người dùng không tồn tại', ['status' => 404]);
    }

    $avatar_url = get_user_meta($user_id, 'avatar_url', true);

    // Debug xem giá trị meta
    if (!$avatar_url) {
        error_log("User $user_id has no avatar_url meta, using default.");
    }

    // Nếu không có avatar, dùng avatar mặc định
    if (empty($avatar_url)) {
        $avatar_url = 'https://spiritwebs.com/wp-content/uploads/2025/10/scaled_1000008965-1.jpg';
    }

    $interests = get_user_meta($user_id, 'interests', true);
    if (empty($interests) || !is_array($interests)) {
        $interests = [];
    }

    return [
        'id' => $user->ID,
        'username' => $user->user_login,
        'display_name' => $user->display_name,
        'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'description' => $user->description,
        'interests' => $interests,
        'avatar_url' => $avatar_url,
        'email' => $user->user_email,
        'roles' => $user->roles,
        'registered_date' => $user->user_registered,
    ];
}



add_action('rest_api_init', function() {
    register_rest_route('spiritwebs/v1', '/delete-message', [
        'methods' => 'POST',
        'callback' => function($request) {
            global $wpdb;
            $params = $request->get_json_params();
            $id = intval($params['id'] ?? 0);

            if (!$id) {
                return new WP_Error('missing_id', 'Thiếu ID tin nhắn', ['status' => 400]);
            }

            $deleted = $wpdb->delete('wp_chat_messages', ['id' => $id]);
            return ['success' => (bool)$deleted];
        },
    ]);
});



add_action('rest_api_init', function() {
    register_rest_route('spiritwebs/v1', '/delete-message', [
        'methods' => 'POST',
        'callback' => 'spiritwebs_delete_message',
        'permission_callback' => '__return_true',
    ]);
});

function spiritwebs_delete_message($request) {
    global $wpdb;

    $params = $request->get_json_params();
    $message_id = intval($params['message_id'] ?? 0);
    $group_id   = intval($params['group_id'] ?? 0);
    $user_id    = intval($params['user_id'] ?? 0);
    error_log(print_r($params, true));

    if (!$message_id || !$group_id) {
        return [
            'success' => false,
            'message' => 'Thiếu dữ liệu cần thiết.',
        ];
    }

    $table = $wpdb->prefix . 'group_messages';

    // Kiểm tra tin nhắn có tồn tại và có thuộc người gửi không
    $message = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM {$table} WHERE id = %d AND group_id = %d", $message_id, $group_id),
        ARRAY_A
    );

    if (!$message) {
        return ['success' => false, 'message' => 'Tin nhắn không tồn tại.'];
    }

    if ($user_id != $message['sender_id']) {
        return ['success' => false, 'message' => 'Bạn không có quyền xóa tin nhắn này.'];
    }

    // Xóa tin nhắn
    $deleted = $wpdb->delete($table, ['id' => $message_id], ['%d']);

    if ($deleted) {
        return ['success' => true, 'message' => 'Đã xóa tin nhắn.'];
    } else {
        return ['success' => false, 'message' => 'Không thể xóa tin nhắn.'];
    }
}



add_action('rest_api_init', function () {
    // Lấy đánh giá của 1 user
    register_rest_route('spiritwebs/v1', '/reviews/(?P<user_id>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_user_reviews',
        'permission_callback' => '__return_true',
    ]);

    // Thêm bình luận + đánh giá
    register_rest_route('spiritwebs/v1', '/reviews', [
        'methods' => 'POST',
        'callback' => 'add_user_review',
        'permission_callback' => '__return_true', // sau này thêm auth
    ]);
});

// 🔹 Lấy danh sách review
function get_user_reviews($data) {
    global $wpdb;
    $table = $wpdb->prefix . 'user_reviews';
    $user_id = intval($data['user_id']);
    $reviews = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table WHERE user_id = %d ORDER BY created_at DESC",
        $user_id
    ));
    return $reviews;
}

// 🔹 Thêm review
function add_user_review($request) {
    global $wpdb;
    $table = $wpdb->prefix . 'user_reviews';

    $user_id = intval($request['user_id']);
    $reviewer_id = intval($request['reviewer_id']);
    $comment = sanitize_text_field($request['comment']);
    $rating = floatval($request['rating']);

    $wpdb->insert($table, [
        'user_id' => $user_id,
        'reviewer_id' => $reviewer_id,
        'comment' => $comment,
        'rating' => $rating,
        'created_at' => current_time('mysql'),
    ]);

    return ['success' => true, 'id' => $wpdb->insert_id];
}



add_action('rest_api_init', function() {
    register_rest_route('custom/v1', '/invite/(?P<id>\d+)/leave', [
        'methods' => 'POST',
        'callback' => 'spiritwebs_leave_invite',
        'permission_callback' => '__return_true', // có thể kiểm tra quyền nếu muốn
    ]);
});

function spiritwebs_leave_invite($request) {
    $invite_id = intval($request['id']);
    $user_id   = get_current_user_id();

    if (!$user_id) {
        return new WP_Error(
            'not_logged_in',
            'Bạn cần đăng nhập để hủy tham gia.',
            ['status' => 401]
        );
    }

    // Lấy danh sách participants, đảm bảo là array
    $participants = (array) get_post_meta($invite_id, 'participants', true);

    // Lọc ra user hiện tại
    $new_participants = array_filter($participants, function($p) use ($user_id) {
        return isset($p['user_id']) && $p['user_id'] != $user_id;
    });

    // Cập nhật lại participants
    update_post_meta($invite_id, 'participants', array_values($new_participants));

    // Tăng slots lên 1
    $slots = intval(get_post_meta($invite_id, 'slots', true));
    update_post_meta($invite_id, 'slots', $slots + 1);

    return [
        'success'      => true,
        'message'      => 'Bạn đã hủy tham gia thành công!',
        'participants' => array_values($new_participants),
        'slots'        => $slots + 1
    ];
}



add_action('rest_api_init', function () {
    register_rest_route('profile/v1', '/users', [
        'methods' => 'POST',
        'callback' => 'get_multiple_users',
        'permission_callback' => '__return_true',
    ]);
});

function get_multiple_users($request) {
    $ids = $request->get_param('ids');
    if (!is_array($ids)) {
        return new WP_Error('invalid_ids', 'IDs phải là mảng', ['status' => 400]);
    }

    $result = [];
    foreach ($ids as $user_id) {
        $user = get_user_by('ID', $user_id);
        if ($user) {
            $avatar_url = get_user_meta($user_id, 'avatar_url', true);
            if (empty($avatar_url)) {
                $avatar_url = 'https://spiritwebs.com/wp-content/uploads/2025/10/scaled_1000008965-1.jpg';
            }
            $result[] = [
                'user_id' => $user->ID,
                'username' => $user->user_login,
                'display_name' => $user->display_name,
                'avatar_url' => $avatar_url,
            ];
        }
    }

    return ['users' => $result];
}







add_action('rest_api_init', function () {

    // 1️⃣ Lấy comment cho 1 user (target_user_id)
    register_rest_route('custom/v1', '/comments/(?P<target_user_id>\d+)', [
        'methods' => 'GET',
        'callback' => 'sw_get_comments',
        'permission_callback' => '__return_true',
    ]);

    // 2️⃣ Thêm comment mới
    register_rest_route('custom/v1', '/comments/add', [
        'methods' => 'POST',
        'callback' => 'sw_add_comment',
        'permission_callback' => '__return_true',
    ]);
});

// --------------------------
// 1️⃣ Hàm lấy comment
function sw_get_comments($request) {
    global $wpdb;
    $target_user_id = sanitize_text_field($request['target_user_id']);

    $table = $wpdb->prefix . 'app_comments';
    $comments = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table WHERE target_user_id = %s ORDER BY created_at DESC",
        $target_user_id
    ), ARRAY_A);

    return [
        'success' => true,
        'comments' => $comments
    ];
}

// --------------------------
// --------------------------
// 2️⃣ Hàm thêm comment (lấy info từ user_id thật)
function sw_add_comment($request) {
    global $wpdb;

    // Lấy dữ liệu từ body
    $data = json_decode(file_get_contents('php://input'), true);

    $user_id = intval($data['user_id'] ?? 0);           // Người comment
    $target_user_id = intval($data['target_user_id'] ?? 0); // Người được comment
    $comment_text = sanitize_text_field($data['comment'] ?? '');
    $rating = floatval($data['rating'] ?? 0);

    // Kiểm tra nội dung comment
    if (empty($comment_text)) {
        return new WP_Error('empty_comment', 'Nội dung bình luận không được để trống.', ['status' => 400]);
    }

    // Lấy thông tin người comment từ WordPress
    $user_info = get_userdata($user_id);
    $username = $user_info ? $user_info->display_name : 'Khách';
    $avatar_url = $user_info ? get_avatar_url($user_id) : '';

    // Insert comment vào bảng
    $table = $wpdb->prefix . 'app_comments';
    $wpdb->insert(
        $table,
        [
            'user_id' => $user_id,
            'target_user_id' => $target_user_id,
            'username' => $username,
            'avatar_url' => $avatar_url,
            'comment' => $comment_text,
            'rating' => $rating,
            'created_at' => current_time('mysql'),
        ]
    );

    // Lấy lại danh sách comment mới nhất
    $comments = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table WHERE target_user_id = %d ORDER BY created_at DESC",
        $target_user_id
    ), ARRAY_A);

    return [
        'success' => true,
        'message' => 'Bình luận đã được thêm!',
        'comments' => $comments
    ];
}

add_action('rest_api_init', function () {
    register_rest_route('app/v1', '/groups', [
        'methods'  => 'GET',
        'callback' => 'get_group_list_api',
    ]);
});

function get_group_list_api() {
    global $wpdb;

    $user_id = intval($_GET['user_id'] ?? 0);
    if (!$user_id) {
        return rest_ensure_response(['error' => 'Missing user_id']);
    }

    $user_lat = get_user_meta($user_id, 'last_latitude', true);
    $user_lng = get_user_meta($user_id, 'last_longitude', true);

    if (!$user_lat || !$user_lng) {
        return rest_ensure_response(['error' => 'User location not found']);
    }

    $radius_km = 10;

    $sql = $wpdb->prepare("
        SELECT 
            id,
            name,
            address,
            total_checkins,
            avg_rating,
            picture_path_large,
            lat,
            lng,
            (
                6371 * ACOS(
                    COS(RADIANS(%f)) 
                    * COS(RADIANS(lat)) 
                    * COS(RADIANS(lng) - RADIANS(%f)) 
                    + SIN(RADIANS(%f)) 
                    * SIN(RADIANS(lat))
                )
            ) AS distance
        FROM wp_pubs
        WHERE 
            status = 1
            AND lat > 0
            AND lng > 0
        HAVING distance <= %f
        ORDER BY 
            distance ASC,
            CAST(avg_rating AS DECIMAL(4,2)) DESC
    ", $user_lat, $user_lng, $user_lat, $radius_km);

    $results = $wpdb->get_results($sql);

    $data = [];

    foreach ($results as $row) {
        $image = $row->picture_path_large
            ?: 'https://down-vn.img.susercontent.com/vn-11134259-7r98o-lwdlt5he3v89d0@resize_ss640x400';

        if (!str_starts_with($image, 'http')) {
            $image = home_url($image);
        }

        $data[] = [
            'id'       => (int)$row->id,
            'name'     => $row->name,
            'address'  => $row->address,
            'members'  => (int)$row->total_checkins,
            'rating'   => (float)$row->avg_rating, // 0 → ok
            'distance' => round($row->distance, 2),
            'image'    => $image,
            'lat'      => (float)$row->lat,
            'lng'      => (float)$row->lng,
        ];
    }

    return rest_ensure_response($data);
}



add_action('rest_api_init', function () {
    register_rest_route('app/v1', '/groups/(?P<id>\d+)', [
        'methods'  => 'GET',
        'callback' => 'get_group_detail_api',
    ]);
});

function get_group_detail_api($request) {
    global $wpdb;
    $id = (int) $request['id'];

    $row = $wpdb->get_row($wpdb->prepare("
        SELECT 
            id,
            name,
            address,
            picture_path_large,
            lat,
            lng,
            foody_data
        FROM wp_pubs
        WHERE id = %d
        LIMIT 1
    ", $id));

    if (!$row) {
        return new WP_Error('not_found', 'Not found', ['status' => 404]);
    }

    // IMAGE
    $image = $row->picture_path_large;
    if (!$image) {
        $image = 'https://down-vn.img.susercontent.com/vn-11134259-7r98o-lwdlt5he3v89d0@resize_ss640x400';
    }
    if ($image && !str_starts_with($image, 'http')) {
        $image = home_url($image);
    }

    // FOODY DATA
    $foody = json_decode($row->foody_data, true) ?: [];

    return [
        'id'          => (int)$row->id,
        'name'        => $row->name,
        'address'     => $row->address,
        'image'       => $image,
        'lat'         => $row->lat,
        'lng'         => $row->lng,
        'open_time'   => strip_tags($foody['open_time'] ?? ''),
        'price_range' => $foody['price_range'] ?? '',
        'images'      => $foody['images'] ?? [],
    ];
}


add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/nearby-pubs', [
        'methods'  => 'GET',
        'callback' => 'sw_get_nearby_pubs',
        'permission_callback' => function ($request) {
            // Nếu cần auth thì giữ lại, không thì return true
            return true;
        },
    ]);
});

function sw_get_nearby_pubs(WP_REST_Request $request) {
    global $wpdb;

    $lat = floatval($request->get_param('lat'));
    $lng = floatval($request->get_param('lng'));
    $radius = floatval($request->get_param('radius')) ?: 7;

    if (!$lat || !$lng) {
        return rest_ensure_response([]);
    }

    $pub_table = $wpdb->prefix . 'pubs';
    $cat_table = $wpdb->prefix . 'foody_categories';

    $sql = $wpdb->prepare("
    SELECT
      p.id,
      p.name,
      p.lat,
      p.lng,
      p.address,
      p.picture_path_large AS image,
      p.avg_rating,
      c.name AS type,
      (
        6371 * acos(
          cos(radians(%f)) *
          cos(radians(p.lat)) *
          cos(radians(p.lng) - radians(%f)) +
          sin(radians(%f)) *
          sin(radians(p.lat))
        )
      ) AS distance
    FROM $pub_table p
    LEFT JOIN $cat_table c ON p.type = c.id
    WHERE p.status = 1
    HAVING distance <= %f
    ORDER BY distance ASC
  ", $lat, $lng, $lat, $radius);

    $results = $wpdb->get_results($sql, ARRAY_A);

    return rest_ensure_response($results);
}

add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/nearby-deals', [
        'methods'  => 'GET',
        'callback' => 'get_nearby_deals',
        'permission_callback' => '__return_true',
    ]);
});

/*function get_nearby_deals(WP_REST_Request $request) {
    global $wpdb;

    $lat = floatval($request->get_param('lat'));
    $lng = floatval($request->get_param('lng'));
    $radius = floatval($request->get_param('radius') ?? 5); // km

    if (!$lat || !$lng) {
        return new WP_REST_Response([
            'error' => 'Missing lat/lng'
        ], 400);
    }

    // Bounding box
    $delta = $radius / 111;

    $sql = $wpdb->prepare("
        SELECT
            p.ID,
            p.post_title,

            MAX(CASE WHEN pm.meta_key = 'lat'
                THEN pm.meta_value END) AS lat,

            MAX(CASE WHEN pm.meta_key = 'lng'
                THEN pm.meta_value END) AS lng,

            MAX(CASE WHEN pm.meta_key = 'time'
                THEN pm.meta_value END) AS time

        FROM {$wpdb->posts} p
        JOIN {$wpdb->postmeta} pm
            ON p.ID = pm.post_id

        WHERE p.post_type = 'product'
          AND p.post_status = 'publish'

        GROUP BY p.ID

        HAVING
            lat BETWEEN %f AND %f
            AND lng BETWEEN %f AND %f

        ORDER BY p.ID DESC
        LIMIT 200
    ",
        $lat - $delta,
        $lat + $delta,
        $lng - $delta,
        $lng + $delta
    );

    error_log("SQL Nearby Deals:");
    error_log($sql);

    $rows = $wpdb->get_results($sql, ARRAY_A);

    $now = current_time('timestamp');

    $rows = array_filter($rows, function ($row) use ($now) {

        if (empty($row['time'])) {
            return false;
        }

        $eventTime = DateTime::createFromFormat(
            'd/m/Y H:i',
            trim($row['time'])
        );

        if (!$eventTime) {
            return false;
        }

        // Hết hạn sau 24 giờ
        $expireTime = $eventTime->getTimestamp() + 86400;

        return $expireTime > $now;
    });

    $rows = array_values($rows);

    return rest_ensure_response($rows);
}*/

function get_nearby_deals(WP_REST_Request $request) {
    global $wpdb;

    $lat = floatval($request->get_param('lat'));
    $lng = floatval($request->get_param('lng'));
    $radius = floatval($request->get_param('radius') ?? 5); // km

    if (!$lat || !$lng) {
        return new WP_REST_Response([
            'error' => 'Missing lat/lng'
        ], 400);
    }

    // Bounding box
    $delta = $radius / 111;

    // Lấy term_id của "Loại kèo" (term cha trong product_cat)
    $parent_term_id = $wpdb->get_var("
        SELECT tt.term_id
        FROM {$wpdb->terms} t
        JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
        WHERE tt.taxonomy = 'product_cat'
          AND t.slug = 'loai-keo'
        LIMIT 1
    ");
    $parent_term_id = $parent_term_id ? intval($parent_term_id) : 0;

    $sql = $wpdb->prepare("
        SELECT
            d.ID,
            d.post_title,
            d.lat,
            d.lng,
            d.time,
            (
                SELECT t.name
                FROM {$wpdb->term_relationships} tr
                JOIN {$wpdb->term_taxonomy} tt
                    ON tr.term_taxonomy_id = tt.term_taxonomy_id
                JOIN {$wpdb->terms} t
                    ON tt.term_id = t.term_id
                WHERE tr.object_id = d.ID
                  AND tt.taxonomy = 'product_cat'
                  AND tt.parent = %d
                ORDER BY tt.term_id ASC
                LIMIT 1
            ) AS type

        FROM (
            SELECT
                p.ID,
                p.post_title,

                MAX(CASE WHEN pm.meta_key = 'lat'
                    THEN pm.meta_value END) AS lat,

                MAX(CASE WHEN pm.meta_key = 'lng'
                    THEN pm.meta_value END) AS lng,

                MAX(CASE WHEN pm.meta_key = 'time'
                    THEN pm.meta_value END) AS time

            FROM {$wpdb->posts} p
            JOIN {$wpdb->postmeta} pm
                ON p.ID = pm.post_id

            WHERE p.post_type = 'product'
              AND p.post_status = 'publish'

            GROUP BY p.ID
        ) d

        WHERE
            d.lat BETWEEN %f AND %f
            AND d.lng BETWEEN %f AND %f

        ORDER BY d.ID DESC
        LIMIT 200
    ",
        $parent_term_id,
        $lat - $delta,
        $lat + $delta,
        $lng - $delta,
        $lng + $delta
    );

    error_log("SQL Nearby Deals:");
    error_log($sql);

    $rows = $wpdb->get_results($sql, ARRAY_A);

    $now = current_time('timestamp');

    $rows = array_filter($rows, function ($row) use ($now) {

        if (empty($row['time'])) {
            return false;
        }

        $eventTime = DateTime::createFromFormat(
            'd/m/Y H:i',
            trim($row['time'])
        );

        if (!$eventTime) {
            return false;
        }

        // Hết hạn sau 24 giờ
        $expireTime = $eventTime->getTimestamp() + 86400;

        return $expireTime > $now;
    });

    // type có thể null nếu product chưa gắn category con nào -> trả ""
    $rows = array_map(function ($row) {
        $row['type'] = $row['type'] ?? "";
        return $row;
    }, $rows);

    $rows = array_values($rows);

    return rest_ensure_response($rows);
}

add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/product/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_product_detail_with_participants',
        'permission_callback' => '__return_true',
    ]);
});

function get_product_detail_with_participants($request) {
    $product_id = intval($request['id']);
    $product = wc_get_product($product_id);
    if (!$product) {
        return new WP_REST_Response(['error' => 'Product not found'], 404);
    }

    // Lấy participants từ postmeta
    $raw = get_post_meta($product_id, 'participants', true); // a:1:{...}
    $participants = maybe_unserialize($raw); // convert PHP serialized -> array
    if (!is_array($participants)) $participants = [];

    // Chuẩn hóa participants cho JSON
    $participants_data = array_map(function($p) {
        return [
            'user_id' => $p['user_id'] ?? 0,
            'joined_at' => $p['joined_at'] ?? '',
        ];
    }, $participants);


    // Product data cơ bản
    $data = [
        'id' => $product->get_id(),
        'name' => $product->get_name(),
        'description' => $product->get_description(),
        'price' => $product->get_price(),
        'regular_price' => $product->get_regular_price(),
        'images' => [],
        'categories' => [],
        'meta_data' => $product->get_meta_data(),
        'participants' => $participants_data,
    ];

    // Images
    $images = $product->get_gallery_image_ids();
    foreach ($images as $img_id) {
        $data['images'][] = ['id' => $img_id, 'src' => wp_get_attachment_url($img_id)];
    }
    $feat_id = $product->get_image_id();
    if ($feat_id) array_unshift($data['images'], ['id' => $feat_id, 'src' => wp_get_attachment_url($feat_id)]);

    // Categories
    $categories = wp_get_post_terms($product_id, 'product_cat');
    foreach ($categories as $cat) {
        $data['categories'][] = ['id' => $cat->term_id, 'name' => $cat->name];
    }

    return rest_ensure_response($data);
}


// Đăng ký REST API endpoint

add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/update-user-location', array(
        'methods' => 'POST',
        'callback' => 'sw_update_user_location',
        'permission_callback' => function($request) {
            // Kiểm tra JWT token hoặc quyền của user
            return true; // tạm thời bỏ qua để test
        },
    ));
});

function sw_update_user_location($request) {
    $params = $request->get_json_params();

    $user_id = isset($params['user_id']) ? intval($params['user_id']) : 0;
    $lat = isset($params['last_latitude']) ? floatval($params['last_latitude']) : 0;
    $lng = isset($params['last_longitude']) ? floatval($params['last_longitude']) : 0;

    if (!$user_id) {
        return new WP_Error('no_user', 'User ID không hợp lệ', array('status' => 400));
    }

    // Update vào user meta
    update_user_meta($user_id, 'last_latitude', $lat);
    update_user_meta($user_id, 'last_longitude', $lng);
    update_user_meta($user_id, 'last_location_updated', current_time('mysql'));

    return array(
        'status' => 'success',
        'user_id' => $user_id,
        'last_latitude' => $lat,
        'last_longitude' => $lng,
    );
}


add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/save-fcm-token', [
        'methods' => 'POST',
        'callback' => function ($request) {
            global $wpdb;

            $user_id   = intval($request->get_param('user_id'));
            $fcm_token = sanitize_text_field($request->get_param('fcm_token'));
            $device    = sanitize_text_field($request->get_param('device'));

            if (!$user_id || !$fcm_token) {
                return new WP_Error('invalid', 'Thiếu user_id hoặc token', ['status' => 400]);
            }

            $table = $wpdb->prefix . 'user_fcm_tokens';

            // Xoá token cũ của user này (tránh rác)
            $wpdb->delete($table, ['user_id' => $user_id]);

            $wpdb->insert($table, [
                'user_id'   => $user_id,
                'fcm_token' => $fcm_token,
                'device'    => $device,
                'updated_at'=> current_time('mysql')
            ]);

            return ['status' => 'ok'];
        },
        'permission_callback' => '__return_true'
    ]);
});

add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/get-fcm-tokens', array(
        'methods'  => array('GET', 'POST'),
        'callback' => 'sw_get_user_fcm_tokens',
        'permission_callback' => '__return_true'
    ));
});
function sw_get_user_fcm_tokens($request) {
    global $wpdb;

    $params = $request->get_json_params();
    $user_id = 0;

    if (!empty($params['user_id'])) {
        $user_id = intval($params['user_id']);
    } elseif (!empty($_GET['user_id'])) {
        $user_id = intval($_GET['user_id']);
    }

    if (!$user_id) {
        return new WP_Error('no_user', 'User ID không hợp lệ', ['status' => 400]);
    }

    // 🔥 LẤY USER INFO
    $user = get_user_by('id', $user_id);
    if (!$user) {
        return new WP_Error('not_found', 'User không tồn tại', ['status' => 404]);
    }

    // 🔥 LẤY TOKEN
    $table = $wpdb->prefix . 'user_fcm_tokens';
    $tokens = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT fcm_token FROM $table WHERE user_id = %d",
            $user_id
        )
    );

    return [
        'status' => 'success',
        'user' => [
            'id' => $user->ID,
            'username' => $user->user_login,
            'display_name' => $user->display_name,
            'avatar' => get_avatar_url($user->ID),
        ],
        'tokens' => $tokens
    ];
}


// ---------------- REGISTER ROUTE ----------------
add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/upload', [
        'methods' => 'POST',
        'callback' => 'sw_upload_file',
        'permission_callback' => '__return_true', // có thể thay bằng check JWT nếu muốn
    ]);
});

// ---------------- CALLBACK UPLOAD ----------------
function sw_upload_file(WP_REST_Request $request) {
    if (empty($_FILES['file'])) {
        return [
            'success' => false,
            'message' => 'No file uploaded'
        ];
    }

    $file = $_FILES['file'];

    // ------------ CHECK SIZE < 10MB ------------
    if ($file['size'] > 10 * 1024 * 1024) {
        return [
            'success' => false,
            'message' => 'File too large. Max 10MB'
        ];
    }

    // ------------ ALLOWED TYPES ------------
    $allowed_types = [
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/zip',
        'text/plain',
        'application/octet-stream' // optional nếu Flutter gửi mime không xác định
    ];


    if (!in_array($file['type'], $allowed_types)) {
        return [
            'success' => false,
            'message' => 'File type not allowed'
        ];
    }

    require_once(ABSPATH . 'wp-admin/includes/file.php');

    // ------------ CREATE CHAT FOLDER IF NOT EXIST ------------
    $upload_dir = wp_upload_dir();
    $chat_dir = $upload_dir['basedir'] . '/chat/';
    if (!file_exists($chat_dir)) mkdir($chat_dir, 0755, true);

    // ------------ UNIQUE FILENAME ------------
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('chat_', true) . '.' . $ext;
    $target_path = $chat_dir . $filename;

    // ------------ IF IMAGE, RESIZE MAX WIDTH 1024 ------------
    if (strpos($file['type'], 'image/') === 0) {
        $img_info = getimagesize($file['tmp_name']);
        $width = $img_info[0];
        $height = $img_info[1];

        if ($width > 1024) {
            $ratio = 1024 / $width;
            $new_width = 1024;
            $new_height = intval($height * $ratio);

            switch ($file['type']) {
                case 'image/jpeg': $src = imagecreatefromjpeg($file['tmp_name']); break;
                case 'image/png': $src = imagecreatefrompng($file['tmp_name']); break;
                case 'image/gif': $src = imagecreatefromgif($file['tmp_name']); break;
                default: $src = null;
            }

            if ($src) {
                $dst = imagecreatetruecolor($new_width, $new_height);

                // preserve transparency for PNG/GIF
                if ($file['type'] === 'image/png' || $file['type'] === 'image/gif') {
                    imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                }

                imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                switch ($file['type']) {
                    case 'image/jpeg': imagejpeg($dst, $target_path, 85); break;
                    case 'image/png': imagepng($dst, $target_path); break;
                    case 'image/gif': imagegif($dst, $target_path); break;
                }

                imagedestroy($src);
                imagedestroy($dst);
            } else {
                move_uploaded_file($file['tmp_name'], $target_path);
            }
        } else {
            move_uploaded_file($file['tmp_name'], $target_path);
        }
    } else {
        move_uploaded_file($file['tmp_name'], $target_path);
    }

    $file_url = $upload_dir['baseurl'] . '/chat/' . $filename;
    // ---------- INSERT VÀO DATABASE ----------
    global $wpdb;

// Nếu bạn có thông tin sender/receiver từ request
    $sender_id = intval($request->get_param('sender_id'));
    $receiver_id = intval($request->get_param('receiver_id'));
    $message_text = $request->get_param('message') ?? ''; // nếu có caption

    $wpdb->insert(
        $wpdb->prefix . 'chat_messages',
        [
            'sender_id'   => $sender_id,
            'receiver_id' => $receiver_id,
            'message'     => $message_text,
            'file_url'    => $file_url,
            'created_at'  => current_time('mysql'),
        ],
        ['%d', '%d', '%s', '%s', '%s']
    );

// ✅ LẤY ID VỪA INSERT
    $message_id = $wpdb->insert_id;

    return [
        'success'  => true,
        'id'       => $message_id,     // ⭐️ QUAN TRỌNG
        'url'      => $file_url,
        'filename' => $file['name'],
        'type'     => $file['type'],
        'size'     => $file['size']
    ];

}
add_shortcode('spirit_qr_join', function () {
    return '<img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=https://spiritwebs.com/quet-ma" />';
});

add_shortcode('join_keo_apk', function () {
    return '
    <div style="
        max-width:420px;
        margin:40px auto;
        padding:32px 24px;
        text-align:center;
        background:#111;
        color:#fff;
        border-radius:16px;
        box-shadow:0 20px 40px rgba(0,0,0,.2);
        font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto;
    ">

      <div style="font-size:40px; margin-bottom:12px">🔥</div>

      <h2 style="margin:0 0 12px; font-size:22px">
        Tham gia
      </h2>

      <p style="margin:0 0 24px; font-size:15px; color:#bbb">
        Cài ứng dụng
      </p>

    
      <a href="https://spiritwebs.com/wp-content/uploads/app-debug.apk"
         style="
           display:block;
           padding:16px;
           background:#00ff9c;
           color:#000;
           font-size:17px;
           font-weight:600;
           border-radius:12px;
           text-decoration:none;
           margin-bottom:16px;
         ">
         ⬇️ Tải & Cài App Keogo (APK)
      </a>

      <p style="font-size:13px; line-height:1.5; color:#999">
        • Chỉ hỗ trợ Android<br>
        • Sau khi tải xong, nếu trình duyệt mở lại,<br>
        vui lòng bấm <b>Cài đặt</b> để hoàn tất
      </p>

    </div>
    ';
});



// 1️⃣ Đăng ký route guest-login
add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/guest-login', [
        'methods'  => 'POST',
        'callback' => 'spiritwebs_guest_login',
        'permission_callback' => '__return_true',
    ]);
});

function spiritwebs_guest_login(WP_REST_Request $request) {

    // 1️⃣ Tạo user thật
    $password = wp_generate_password(12, false);
    //$username = 'user_' . time() . '_' . wp_rand(1000, 9999);
    $username = 'new' . wp_rand(1000, 9999);
    $email    = $username . '@spiritwebs.com';

    $user_id = wp_create_user($username, $password, $email);
    if (is_wp_error($user_id)) {
        return new WP_Error('create_user_failed', $user_id->get_error_message(), ['status' => 500]);
    }

    // 2️⃣ Đánh dấu user tạo từ QR
    update_user_meta($user_id, 'is_auto_user', 1);
    update_user_meta($user_id, 'need_update_profile', 1);
    update_user_meta($user_id, 'must_set_password', '1');


    // ❌ 3️⃣ TUYỆT ĐỐI KHÔNG LOGIN WP
    // wp_set_current_user($user_id);
    // wp_set_auth_cookie($user_id);

    // 4️⃣ LẤY JWT (stateless)
    $req = new WP_REST_Request('POST', '/jwt-auth/v1/token');
    $req->set_param('username', $username);
    $req->set_param('password', $password);

    $res  = rest_do_request($req);
    $data = $res->get_data();

    if (empty($data['token'])) {
        return new WP_Error('jwt_failed', 'Không tạo được JWT', ['status' => 500]);
    }

    // 5️⃣ Trả về cho Flutter
    return [
        'status'  => 200,
        'token'   => $data['token'],
        'user_id' => $user_id,
        'user'    => [
            'ID'           => $user_id,
            'user_login'   => $username,
            'user_email'   => $email,
            'display_name' => $username,
        ],
        'is_guest' => true
    ];
}


add_action('admin_head', function () {
    echo '<style>
        #toplevel_page_wp_companies {
            display: none !important;
        }
    </style>';
});

add_action('admin_head', function () {
    echo '<style>
        #toplevel_page_wp_companies_yellowpages {
            display: none !important;
        }
    </style>';
});

add_action('rest_api_init', function () {

    register_rest_route('spiritwebs/v1', '/me', [
        'methods'  => 'GET',
        'callback' => 'spiritwebs_me',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('spiritwebs/v1', '/set-password', [
        'methods'  => 'POST',
        'callback' => 'spiritwebs_set_password',
        'permission_callback' => '__return_true',
    ]);
});
function spiritwebs_me() {
    $user_id = get_current_user_id();
    error_log('USER_ID=' . get_current_user_id());

    if (!$user_id) {
        return [
            'logged_in' => false,
        ];
    }

    $user = get_userdata($user_id);

    return [
        'logged_in'        => true,
        'id'               => (int) $user_id,
        'email'            => $user->user_email,
        'username'         => $user->user_login,
        'is_guest'         => get_user_meta($user_id, 'is_guest', true) === '1',
        'must_set_password'=> get_user_meta($user_id, 'must_set_password', true) === '1',
    ];
}

function spiritwebs_set_password(WP_REST_Request $req) {
    $user_id = get_current_user_id();

    if (!$user_id) {
        return new WP_Error('unauthorized', 'Not logged in', ['status' => 401]);
    }

    $password = trim($req->get_param('password'));

    if (strlen($password) < 6) {
        return new WP_Error('weak_password', 'Password too short', ['status' => 400]);
    }

    wp_set_password($password, $user_id);

    update_user_meta($user_id, 'must_set_password', '0');
    update_user_meta($user_id, 'is_guest', '0');

    // 🔥 QUAN TRỌNG: trả state mới cho client
    return [
        'success'           => true,
        'must_set_password' => false,
        'is_guest'          => false,
    ];
}



// ================= SHORTCODE =================
add_shortcode('snapshots', function () {
    return snapshots_grid_render(1, 12, false);
});


// ================= AJAX =================
add_action('wp_ajax_snapshots_grid_ajax', 'snapshots_grid_ajax');
add_action('wp_ajax_nopriv_snapshots_grid_ajax', 'snapshots_grid_ajax');

function snapshots_grid_ajax() {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 12;
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

    $results = snapshots_get_data($page, $per_page, $search);

    // 👉 nếu hết data
    if (!$results) {
        echo '';
        wp_die();
    }

    echo snapshots_render_items($results);
    wp_die();
}


// ================= GET DATA =================
function snapshots_get_data($paged, $per_page, $search = '') {
    global $wpdb;

    $snapshots_table = $wpdb->prefix . "website_snapshots";
    $companies_table = $wpdb->prefix . "companies_yellowpages";
    $offset = ($paged - 1) * $per_page;

    // lấy tất cả snapshot join company
    $query = "
        SELECT s.id, s.company_id, c.company_name AS company_name, s.public_url, s.screenshot, c.issues, c.scan_score
        FROM $snapshots_table s
        INNER JOIN $companies_table c ON s.company_id = c.id
        WHERE s.public_url IS NOT NULL
        ORDER BY c.scan_score DESC
    ";

    $all_snapshots = $wpdb->get_results($query);

    // lọc trong PHP
    $filtered = array_filter($all_snapshots, function($s) use ($search) {
        // lọc theo scan_score > 70
        if ((float)$s->scan_score <= 70) {
            return false;
        }

        // lọc theo issues
        $issues = json_decode($s->issues, true) ?: [];
        if (in_array("Website không truy cập được", $issues)) {
            return false;
        }

        // nếu có search
        if ($search) {
            return str_contains((string)$s->company_id, $search) || str_contains($s->company_name, $search);
        }

        return true;
    });

    // sắp xếp theo scan_score giảm dần
    usort($filtered, function($a, $b) {
        return (float)$b->scan_score <=> (float)$a->scan_score;
    });

    // pagination trong PHP
    return array_slice($filtered, $offset, $per_page);
}
// ================= RENDER ITEMS =================
function snapshots_render_items($results) {
    ob_start();

    foreach ($results as $row): ?>
        <div class="snap-item">

            <div class="snap-code">
                Mã Giao Diện: <?php echo esc_html($row->company_id); ?>
            </div>

            <?php if (!empty($row->screenshot)): ?>
                <a href="<?php echo esc_url($row->public_url); ?>" target="_blank">
                    <div class="scroll-box">
                        <div class="img-wrap">

                            <img
                                    src="<?php echo esc_url($row->screenshot); ?>"
                                    loading="lazy"
                                    onload="this.classList.add('loaded')"
                            />

                            <div class="skeleton"></div>

                        </div>
                    </div>
                </a>
            <?php endif; ?>

            <a href="<?php echo esc_url($row->public_url); ?>" target="_blank" class="snap-btn">
                Xem demo
            </a>

        </div>
    <?php endforeach;

    return ob_get_clean();
}


// ================= MAIN RENDER =================
function snapshots_grid_render($paged, $per_page, $is_ajax = false, $search = '') {

    $results = snapshots_get_data($paged, $per_page, $search);

    if (!$results) return '<p>Không tìm thấy kết quả</p>';


    // 👉 AJAX chỉ trả item (FIX lỗi co nhỏ)
    if ($is_ajax) {
        return snapshots_render_items($results);
    }

    ob_start();
    ?>
    <style>
        .snap-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        @media (max-width:1024px){ .snap-grid{grid-template-columns:repeat(2,1fr);} }
        @media (max-width:600px){ .snap-grid{grid-template-columns:repeat(1,1fr);} }

        .snap-item {
            border: 1px solid #eee;
            border-radius: 16px;
            padding: 12px;
            background: #fff;
            transition: 0.3s;
        }
        .snap-item:hover {
            transform: translateY(-5px);
        }

        .snap-code {
            font-size: 12px;
            color: #666;
            margin-bottom: 6px;
        }

        /* ===== IMAGE BOX ===== */
        .scroll-box {
            height: 320px;
            overflow: hidden;
            position: relative;
        }

        .img-wrap {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;           /* giữ image không vượt ra ngoài */
            border-radius: 16px;        /* radius chuẩn */
        }

        .img-wrap img {
            width: 100%;
            height: auto;
            display: block;
            opacity: 0;
            transition: opacity 0.4s ease, transform 8s linear;
            border-radius: 16px;        /* 👈 quan trọng: border-radius trực tiếp */
            backface-visibility: hidden; /* fix render glitch khi transform */
        }

        .img-wrap img.loaded {
            opacity: 1;
        }

        /* hover scroll (giữ hiệu ứng của mày) */
        .scroll-box:hover img.loaded {
            transform: translateY(calc(-100% + 320px));
        }

        /* ===== SKELETON ===== */
        .skeleton {
            position: absolute;
            inset: 0;
            background: #e5e7eb;
            z-index: 1;
            border-radius: inherit;
            overflow: hidden;
        }

        /* shimmer */
        .skeleton::after {
            content: "";
            position: absolute;
            top: 0;
            left: -150px;
            width: 150px;
            height: 100%;
            background: linear-gradient(
                    90deg,
                    transparent,
                    rgba(255,255,255,0.6),
                    transparent
            );
            animation: shimmer 1.2s infinite;
        }

        @keyframes shimmer {
            0% { left: -150px; }
            100% { left: 100%; }
        }

        /* 👉 fade skeleton khi load xong */
        .img-wrap img.loaded + .skeleton {
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        /* ===== BUTTON ===== */
        .snap-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 14px;
            background: #2563eb;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
        }

        /* ===== LOADER XOAY ===== */
        .loader {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: conic-gradient(
                    from 0deg,
                    #2563eb,
                    #9333ea,
                    #2563eb
            );
            -webkit-mask: radial-gradient(farthest-side, transparent 60%, black 61%);
            mask: radial-gradient(farthest-side, transparent 60%, black 61%);
            animation: spin 0.8s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ===== SKELETON FAKE (infinite scroll) ===== */
        .snap-fake .skeleton {
            height: 320px;
            border-radius: 12px;
        }

        .snap-fake .skeleton:first-child {
            margin-bottom: 10px;
        }
        .skeleton {
            z-index: 1;
        }

        .img-wrap img {
            z-index: 2;
        }
    </style>

    <div id="snapshots-wrapper">

        <div style="margin-bottom:20px;display:flex;gap:10px;">
            <input
                    type="text"
                    id="snap-search"
                    placeholder="Nhập mã giao diện..."
                    style="flex:1;padding:10px;border:1px solid #ddd;border-radius:8px;"
            >
            <button id="snap-search-btn"
                    style="padding:10px 16px;background:#2563eb;color:#fff;border:none;border-radius:8px;">
                Tìm
            </button>
        </div>

        <div id="snapshots-grid" class="snap-grid">
            <?php echo snapshots_render_items($results); ?>
        </div>

        <div id="snap-loader" style="display:none">
            <div class="loader"></div>
        </div>
    </div>

    <script>
        let page = 1;
        let loading = false;
        let done = false;
        let searchKeyword = "";
        // ================= SEARCH =================

        // click button
        document.getElementById("snap-search-btn").addEventListener("click", doSearch);

        // enter key
        document.getElementById("snap-search").addEventListener("keypress", function(e){
            if(e.key === "Enter") doSearch();
        });

        // auto reset khi xoá input
        document.getElementById("snap-search").addEventListener("input", function(e){
            if (e.target.value.trim() === "") {
                doSearch();
            }
        });

        function doSearch() {
            searchKeyword = document.getElementById("snap-search").value.trim();

            // 👉 reset state
            page = 1;
            done = false;
            loading = false;

            // 👉 nếu KHÔNG search → load lại full
            if (searchKeyword === "") {
                document.getElementById("snapshots-grid").innerHTML = "";
                loadData();
                return;
            }

            // 👉 nếu có search
            document.getElementById("snapshots-grid").innerHTML = `
        <div class="loader"></div>
    `;

            fetchData(true);
        }

        // ================= FETCH =================

        function fetchData(replace = false) {
            loading = true;

            fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: new URLSearchParams({
                    action: "snapshots_grid_ajax",
                    page: page,
                    per_page: 12,
                    search: searchKeyword
                })
            })
                .then(res => res.text())
        .then(html => {

                // ❗ nếu không có kết quả
                if (!html.trim()) {

                done = true;
                loading = false;

                document.getElementById("snap-loader").style.display = "none";
                document.querySelectorAll(".snap-fake").forEach(el => el.remove());

                // 👉 nếu là search lần đầu (page = 1)
                if (page === 1) {
                    document.getElementById("snapshots-grid").innerHTML = `
                <p style="text-align:center;color:#999;padding:40px;">
                    Không tìm thấy mã giao diện
                </p>
            `;
                }

                return;
            }

            // 👉 nếu có data
            if (replace) {
                document.getElementById("snapshots-grid").innerHTML = html;
            } else {
                document.querySelectorAll(".snap-fake").forEach(el => el.remove());

                document.getElementById("snapshots-grid")
                    .insertAdjacentHTML("beforeend", html);
            }

            loading = false;
            document.getElementById("snap-loader").style.display = "none";
        });
        }

        // load mặc định
        function loadData() {
            fetchData(true);
        }

        // ================= INFINITE SCROLL =================

        window.addEventListener("scroll", () => {
            if (loading || done) return;

        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 300) {

            loading = true;
            page++;

            document.getElementById("snap-loader").style.display = "block";

            // 👉 skeleton fake
            let fake = "";
            for (let i = 0; i < 6; i++) {
                fake += `
                <div class="snap-item snap-fake">
                    <div class="skeleton" style="height:20px;margin-bottom:10px;"></div>
                    <div class="scroll-box">
                        <div class="skeleton"></div>
                    </div>
                </div>
            `;
            }

            document.getElementById("snapshots-grid")
                .insertAdjacentHTML("beforeend", fake);

            fetchData(false);
        }
        });
    </script>

    <?php
    return ob_get_clean();
}

// ================= PAGINATION =================
function snapshots_pagination($paged, $total_pages, $range = 2) {
    $output = '<div class="pagination">';

    if ($paged > 1) {
        $output .= '<a href="#" data-page="'.($paged - 1).'">&laquo;</a>';
    }

    if ($paged > $range + 1) {
        $output .= '<span>...</span>';
    }

    for ($i = max(1, $paged - $range); $i <= min($total_pages, $paged + $range); $i++) {
        if ($i == $paged) {
            $output .= '<span class="current">'.$i.'</span>';
        } else {
            $output .= '<a href="#" data-page="'.$i.'">'.$i.'</a>';
        }
    }

    if ($paged < $total_pages - $range) {
        $output .= '<span>...</span>';
    }

    if ($paged < $total_pages) {
        $output .= '<a href="#" data-page="'.($paged + 1).'">&raquo;</a>';
    }

    $output .= '</div>';

    return $output;
}


// chặn https://spiritwebs.com/wp-json/wp/v2/users/


add_filter('rest_endpoints', function ($endpoints) {
    if (isset($endpoints['/wp/v2/users'])) {
        unset($endpoints['/wp/v2/users']);
    }
    return $endpoints;
});

    // phần game
add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/spin', [
        'methods' => 'POST',
        'callback' => 'nhau_spin',
        'permission_callback' => '__return_true'
    ]);
});
    function nhau_spin(WP_REST_Request $request) {
        global $wpdb;

        $invite_id = intval($request->get_param('invite_id'));

        if (!$invite_id) {
            return ['success' => false, 'message' => 'Missing invite_id'];
        }

        // 🔥 lấy người trong bàn
        $members = $wpdb->get_results($wpdb->prepare("
            SELECT user_id 
            FROM wp_invite_participants
            WHERE invite_id = %d
            AND status = 'joined'
            AND attendance_status != 'not_going'
        ", $invite_id), ARRAY_A);

        if (empty($members)) {
            return ['success' => false, 'message' => 'No participants'];
        }

        // 🔥 tránh lặp người vừa quay
        $lastUser = $wpdb->get_var($wpdb->prepare("
            SELECT user_id FROM wp_invite_spins
            WHERE invite_id = %d
            ORDER BY id DESC LIMIT 1
        ", $invite_id));

        do {
            $randomMember = $members[array_rand($members)];
        } while (count($members) > 1 && $randomMember['user_id'] == $lastUser);

        $user_id = $randomMember['user_id'];

        $user = get_userdata($user_id);
        $user_name = $user ? $user->display_name : 'Unknown';

        // 🎯 action list
        $actions = [
            ['name' => 'Uống 1 ly', 'weight' => 40],
            ['name' => 'Uống 2 ly', 'weight' => 30],
            ['name' => 'Chỉ định người khác', 'weight' => 20],
            ['name' => 'Cả bàn uống', 'weight' => 10],
        ];

        $index = nhau_random_weight_index($actions);
        $action = $actions[$index]['name'];

        // log
        $wpdb->insert('wp_invite_spins', [
            'invite_id' => $invite_id,
            'user_id' => $user_id,
            'action' => $action
        ]);

        return new WP_REST_Response([
            'success' => true,
            'result' => [
                'user_id' => $user_id,
                'user_name' => $user_name,
                'action' => $action,
                'index' => $index
            ]
        ], 200);
    }
    function nhau_random_weight($items) {
        $total = array_sum(array_column($items, 'weight'));
        $rand = mt_rand(1, $total);

        foreach ($items as $item) {
            if ($rand <= $item['weight']) {
                return $item['name'];
            }
            $rand -= $item['weight'];
        }
    }
function nhau_random_weight_index($items) {
    $total = array_sum(array_column($items, 'weight'));
    $rand = mt_rand(1, $total);

    foreach ($items as $i => $item) {
        if ($rand <= $item['weight']) {
            return $i;
        }
        $rand -= $item['weight'];
    }

    return 0;
}


add_action('rest_api_init', function () {
    register_rest_route('spiritwebs/v1', '/random-phone', [
        'methods' => 'GET',
        'callback' => 'get_random_company_phone',
    ]);
});

function get_random_company_phone() {
    global $wpdb;

    $table = $wpdb->prefix . 'companies'; // wp_companies

    $phone = $wpdb->get_var("
        SELECT phone 
        FROM $table 
        WHERE phone IS NOT NULL AND phone != ''
        ORDER BY RAND()
        LIMIT 1
    ");

    return [
        'success' => true,
        'phone' => $phone,
    ];
}

add_action('rest_api_init', function () {

    register_rest_field(
        'product',
        'participants',
        [
            'get_callback' => function ($product_arr) {

                global $wpdb;

                $product_id = intval($product_arr['id']);

                // lấy invite theo product
                $invite = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM wp_invites WHERE product_id = %d LIMIT 1",
                        $product_id
                    )
                );

                if (!$invite) {
                    return [];
                }

                // lấy participants
                $rows = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT * FROM wp_invite_participants 
                         WHERE invite_id = %d 
                         AND status = 'joined'",
                        $invite->id
                    ),
                    ARRAY_A
                );

                if (!$rows) {
                    return [];
                }

                return array_map(function($p) {

                    $user_id = intval($p['user_id']);

                    $user = get_userdata($user_id);

                    $avatar = get_user_meta($user_id, 'avatar_url', true);

                    if (!$avatar) {
                        $avatar = get_avatar_url($user_id);
                    }

                    return [
                        'user_id' => $user_id,
                        'joined_at' => $p['joined_at'] ?? '',
                        'display_name' => $user ? $user->display_name : '',
                        'avatar_url' => $avatar,
                        'role' => $p['role'] ?? 'member',
                        'status' => $p['status'] ?? '',
                    ];

                }, $rows);
            },

            'schema' => null,
        ]
    );

});

add_filter('woocommerce_rest_prepare_product_object', function ($response, $post, $request) {

    global $wpdb;

    $product_id = $post->get_id();

    $invite = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM wp_invites WHERE product_id = %d LIMIT 1",
        $product_id
    ));

    if (!$invite) return $response;

    $rows = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM wp_invite_participants 
         WHERE invite_id = %d AND status = 'joined'",
        $invite->id
    ), ARRAY_A);

    $participants = array_map(function ($p) {

        $user_id = intval($p['user_id']);
        $avatar = get_user_meta($user_id, 'avatar_url', true);
        if (!$avatar) $avatar = get_avatar_url($user_id);

        return [
            'user_id' => $user_id,
            'avatar_url' => $avatar,
            'joined_at' => $p['joined_at'],
        ];

    }, $rows ?: []);

    $response->data['participants'] = $participants;

    return $response;

}, 10, 3);



//chạy đăng bài tự động
// ─── 1. ĐĂNG KÝ CRON 30 PHÚT ───────────────────────────────────────────────



add_action('nhau_auto_create_keo', function() {

    // ─── 1. LẤY USER ───────────────────────────────────────────────────────
    $users = get_users([
        'role__in' => ['subscriber', 'customer', 'administrator'],
        'number'   => 100,
        'orderby'  => 'registered',
        'order'    => 'DESC',
    ]);

    if (empty($users)) {
        error_log("[NHAU] Không có user nào");
        return;
    }

    shuffle($users);
    $user = $users[0];

    // Kiểm tra user đã có kèo trong 24h chưa
    $existing = wc_get_products([
        'status' => 'publish',
        'limit'  => 1,
        'meta_query' => [[
            'key'   => 'creator_id',
            'value' => $user->ID,
        ]],
        'date_created' => '>' . date('Y-m-d H:i:s', strtotime('-24 hours')),
    ]);

    if (!empty($existing)) {
        shuffle($users);
        $user = $users[1] ?? $users[0];
    }

    error_log("[NHAU] Tạo kèo cho user #{$user->ID} ({$user->display_name})");

    // ─── 2. GỌI AI ─────────────────────────────────────────────────────────
    $ai_content = nhau_call_ai_for_keo($user->display_name);
    error_log("[NHAU] AI: " . json_encode($ai_content, JSON_UNESCAPED_UNICODE));

    // ─── 3. RANDOM PUB ─────────────────────────────────────────────────────
    $pub = [];
    $pub_res = wp_remote_get('https://spiritwebs.com/wp-json/spiritwebs/v1/random-pub');
    if (!is_wp_error($pub_res)) {
        $pub_body = json_decode(wp_remote_retrieve_body($pub_res), true);
        if (!empty($pub_body['success'])) {
            $pub = $pub_body['data'];
        }
    }
    error_log("[NHAU] Pub: " . json_encode($pub, JSON_UNESCAPED_UNICODE));

    // ─── 4. RANDOM PHONE ───────────────────────────────────────────────────
    $phone = '';
    $phone_res = wp_remote_get('https://spiritwebs.com/wp-json/spiritwebs/v1/random-phone');
    if (!is_wp_error($phone_res)) {
        $phone_body = json_decode(wp_remote_retrieve_body($phone_res), true);
        if (!empty($phone_body['success'])) {
            $phone = $phone_body['phone'];
        }
    }

    // ─── 5. RANDOM TIME ────────────────────────────────────────────────────
    $days_ahead = rand(1, 30);
    $hour       = rand(18, 21);
    $minute     = (rand(0, 1) === 0) ? '00' : '30';
    $keo_time   = date("d/m/Y $hour:$minute", strtotime("+$days_ahead days"));

    // ─── 6. RANDOM SLOTS ───────────────────────────────────────────────────
    $slots = rand(2, 12);

    // ─── 7. RANDOM IMAGES ──────────────────────────────────────────────────
    $images = nhau_get_random_images(3);

    // ─── 8. TẠO PRODUCT ────────────────────────────────────────────────────
    $product = new WC_Product_Simple();
    $product->set_name($ai_content['title'] ?? 'Kèo nhậu tối nay 🍺');
    $product->set_description($ai_content['description'] ?? '');
    $product->set_status('publish');
    $product->set_regular_price('0');

    $product->update_meta_data('creator_id',   $user->ID);
    $product->update_meta_data('slots',        $slots);
    $product->update_meta_data('time',         $keo_time);
    $product->update_meta_data('address',      $pub['address']  ?? $ai_content['address']  ?? '');
    $product->update_meta_data('pub_name',     $pub['pub_name'] ?? $ai_content['pub_name'] ?? '');
    $product->update_meta_data('lat',          $pub['lat']      ?? $ai_content['lat']      ?? '');
    $product->update_meta_data('lng',          $pub['lng']      ?? $ai_content['lng']      ?? '');
    $product->update_meta_data('price_range',  '100-200');
    $product->update_meta_data('contact',      $phone);
    $product->update_meta_data('note',         $ai_content['note'] ?? '');
    $product->update_meta_data('participants', []);
    $product->update_meta_data('videos',       json_encode([]));

    // ─── 9. CATEGORY ───────────────────────────────────────────────────────
    $all_parents = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'parent'     => 0,
    ]);

    $type_parent    = null;
    $area_parent    = null;
    $feature_parent = null;

    foreach ($all_parents as $parent) {
        if ($parent->slug === 'uncategorized') continue;
        $name = mb_strtolower($parent->name, 'UTF-8');

        if (mb_strpos($name, 'đích') !== false) {
            $type_parent = $parent;
        } elseif (mb_strpos($name, 'tính') !== false) {
            $area_parent = $parent;
        } else {
            $feature_parent = $parent;
        }
    }

    $cat_ids = [];
    foreach ([$type_parent, $area_parent, $feature_parent] as $parent) {
        if (!$parent) continue;

        $children = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
            'parent'     => $parent->term_id,
        ]);

        if (!empty($children)) {
            shuffle($children);
            $picked    = $children[0];
            $cat_ids[] = $picked->term_id;
            error_log("[NHAU] Category '{$parent->name}' → {$picked->name}");
        }
    }

    if (!empty($cat_ids)) {
        $product->set_category_ids($cat_ids);
    }

    // ─── 10. ẢNH ───────────────────────────────────────────────────────────
    if (!empty($images)) {
        $product->set_image_id($images[0]);
        if (count($images) > 1) {
            $product->set_gallery_image_ids(array_slice($images, 1));
        }
    }

    $product_id = $product->save();
    error_log("[NHAU] Product ID: $product_id");

    // ─── 11. TẠO INVITE RECORD ─────────────────────────────────────────────
    // ─── 11. TẠO INVITE RECORD ─────────────────────────────────────────────
    if ($product_id) {
        $invite_res = wp_remote_post(
            'https://spiritwebs.com/wp-json/nhau/v1/invite/create',
            [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . nhau_get_admin_jwt(),
                ],
                'body'    => json_encode([
                    'product_id' => $product_id,
                    'max_people' => $slots,
                    'start_time' => $keo_time,
                    'creator_id' => $user->ID,
                ]),
                'timeout' => 15,
            ]
        );

        if (!is_wp_error($invite_res)) {
            $invite_body = json_decode(wp_remote_retrieve_body($invite_res), true);
            error_log("[NHAU] Invite: " . json_encode($invite_body));
        }

        nhau_broadcast_new_product($product_id);
        error_log("[NHAU] ✅ Xong! Kèo #{$product_id} cho {$user->display_name} lúc $keo_time");
    }
});

// ─── GỌI AI ─────────────────────────────────────────────────────────────────
function nhau_call_ai_for_keo($username) {
    $response = wp_remote_post('https://spiritwebs.com/api/invite-ai', [
        'headers' => ['Content-Type' => 'application/json'],
        'body'    => json_encode(['username' => $username]),
        'timeout' => 15,
    ]);

    if (is_wp_error($response)) {
        return [
            'title'       => "Kèo nhậu tối nay 🍺",
            'description' => "Anh em tụ tập, vui vẻ là chính!",
            'note'        => "Đến đúng giờ nha bạn ơi",
        ];
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    return $body['data'] ?? [
            'title'       => "Kèo nhậu tối nay 🍺",
            'description' => "Anh em tụ tập, vui vẻ là chính!",
            'note'        => "Đến đúng giờ nha bạn ơi",
        ];
}

// ─── LẤY ẢNH NGẪU NHIÊN ────────────────────────────────────────────────────
function nhau_get_random_images($limit = 3) {
    $response = wp_remote_get(
        "https://spiritwebs.com/wp-json/nhau/v1/random-images?from_year=2026&limit=$limit"
    );

    if (is_wp_error($response)) return [];

    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($body['images'])) return [];

    $image_ids = [];
    foreach ($body['images'] as $url) {
        $attachment_id = nhau_upload_image_from_url($url);
        if ($attachment_id) $image_ids[] = $attachment_id;
    }

    return $image_ids;
}

function nhau_upload_image_from_url($url) {
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $tmp = download_url($url);
    if (is_wp_error($tmp)) return null;

    $file_array = [
        'name'     => basename($url),
        'tmp_name' => $tmp,
    ];

    $id = media_handle_sideload($file_array, 0);
    @unlink($tmp);

    return is_wp_error($id) ? null : $id;
}

// ─── BROADCAST WEBSOCKET ────────────────────────────────────────────────────
function nhau_broadcast_new_product($product_id) {
    $product = wc_get_product($product_id);
    if (!$product) return;

    $payload = [
        'topic'   => 'products:lobby',
        'event'   => 'new_product',
        'payload' => ['id' => $product_id, 'name' => $product->get_name()],
        'ref'     => 'auto',
    ];

    wp_remote_post('https://socket.spiritwebs.com/api/broadcast', [
        'headers' => ['Content-Type' => 'application/json'],
        'body'    => json_encode($payload),
        'timeout' => 5,
    ]);
}

// ─── LẤY JWT ADMIN ──────────────────────────────────────────────────────────
function nhau_get_admin_jwt() {
    $token = get_transient('nhau_admin_jwt');
    if ($token) return $token;

    $res = wp_remote_post('https://spiritwebs.com/wp-json/jwt-auth/v1/token', [
        'body' => [
            'username' => 'admin',
            'password' => 'Xuanhung@2211', // ← thay password thật
        ],
    ]);

    if (is_wp_error($res)) return '';

    $body  = json_decode(wp_remote_retrieve_body($res), true);
    $token = $body['token'] ?? '';

    if ($token) {
        set_transient('nhau_admin_jwt', $token, 6 * DAY_IN_SECONDS);
    }

    return $token;
}

// ─── TEST THỦ CÔNG ──────────────────────────────────────────────────────────
add_action('init', function() {
    if (isset($_GET['run_keo']) && $_GET['run_keo'] === 'abc123') {
        do_action('nhau_auto_create_keo');
        die('✅ Done!');
    }
});