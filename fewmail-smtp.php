<?php
/*
 * Plugin Name: FewMail SMTP
 * Plugin URI: https://wenpai.org/plugins/fewmail-smtp
 * Description: A WordPress email plugin that makes it convenient for users to configure SMTP settings.
 * Version: 1.0.5
 * Author: FewMail.com
 * Author URI: https://fewmail.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * Text Domain: fewmail-smtp
 */

if (!defined('ABSPATH')) {
    exit;
}

define('FEWMAIL_SMTP_VERSION', '1.0.5');
define('FEWMAIL_SMTP_PLUGIN_SLUG', 'fewmail-smtp');
define('FEWMAIL_SMTP_PLUGIN_PAGE', plugin_basename(dirname(__FILE__)) . '%2F' . basename(__FILE__));
define('FEWMAIL_SMTP_URL', plugins_url('/', __FILE__));
define('FEWMAIL_SMTP_ASSETS_URL', FEWMAIL_SMTP_URL . 'assets/');

require_once 'vendor/autoload.php';
require_once dirname(__FILE__) . '/includes/Setting-Page.php';
require_once dirname(__FILE__) . '/includes/Config.php';

use FewMailSmtp\Config;
use FewMailSmtp\Db;

register_activation_hook(__FILE__, 'fewmail_smtp_activate');

add_action('plugins_loaded', 'fewmail_smtp_load_textdomain');
function fewmail_smtp_load_textdomain() {
    load_plugin_textdomain('fewmail-smtp', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

function fewmail_smtp_activate() {
    ob_start();
    fewmail_smtp_set_options();
    fewmail_smtp_set_stats();
    $output = ob_get_contents();
    if (!empty($output)) {
        error_log('FewMail SMTP Activation Output: ' . bin2hex($output));
    }
    ob_end_clean();
}


function fewmail_smtp_set_options() {
    $options = [
        'from' => '',
        'from_name' => '',
        'host' => '',
        'port' => '',
        'smtp_secure' => '',
        'smtp_auth' => 'yes',
        'username' => '',
        'password' => '',
        'disable_logs' => '',
    ];
    if (!get_option('fewmail_smtp_options')) {
        add_option('fewmail_smtp_options', $options, '', 'yes');
    }
    fewmail_smtp_install_table();
}

function fewmail_smtp_set_stats() {
    $stats = [
        'total_emails' => 0,
        'successful_sends' => 0,
        'failed_sends' => 0,
        'last_send_time' => null
    ];
    if (!get_option('fewmail_smtp_stats')) {
        add_option('fewmail_smtp_stats', $stats, '', 'yes');
    }
}

function fewmail_smtp_install_table() {
    global $wpdb;
    $tableName = $wpdb->prefix . 'fewmail_smtp_logs';
    $sql = "CREATE TABLE IF NOT EXISTS `" . $tableName . "` (
                `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
                `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `to` VARCHAR(200) NOT NULL DEFAULT '0',
                `subject` VARCHAR(200) NOT NULL DEFAULT '0',
                `message` TEXT NULL,
                `headers` TEXT NULL,
                `error` TEXT NULL,
                PRIMARY KEY (`id`)
            ) DEFAULT CHARACTER SET = utf8 DEFAULT COLLATE utf8_general_ci;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
    if ($wpdb->last_error) {
        error_log('FewMail SMTP table creation error: ' . $wpdb->last_error);
    }
}

function fewmail_smtp_setting_page_tabs() {
    return [
        'config' => esc_html__('Config', 'fewmail-smtp'),
        'testing' => esc_html__('Testing', 'fewmail-smtp'),
        'logs' => esc_html__('Logs', 'fewmail-smtp'),
    ];
}

function fewmail_smtp_get_current_tab() {
    $tabs = fewmail_smtp_setting_page_tabs();
    $current = sanitize_text_field($_GET['tab'] ?? 'config');
    return !isset($tabs[$current]) ? 'config' : $current;
}

add_action('admin_menu', 'fewmail_smtp_add_setting_page');

$options = get_option('fewmail_smtp_options', []);
if (is_array($options) && isset($options['disable_logs']) && 'yes' === $options['disable_logs']) {
} else {
    add_filter('wp_mail', 'fewmail_smtp_log_mails', PHP_INT_MAX);
}

add_action('wp_mail_failed', 'fewmail_smtp_update_failed_status', PHP_INT_MAX);
add_action('wp_mail_succeeded', 'fewmail_smtp_update_success_status');

function fewmail_smtp_log_mails($parts) {
    $data = $parts;
    unset($data['attachments']);
    $stats = get_option('fewmail_smtp_stats', []);
    $stats['total_emails'] = ($stats['total_emails'] ?? 0) + 1;
    update_option('fewmail_smtp_stats', $stats);
    Db::$id = Db::create()->insert($data);
    return $parts;
}

function fewmail_smtp_update_failed_status($wp_error) {
    Db::$phpmailer_error = $wp_error;
    $options = get_option('fewmail_smtp_options');
    $stats = get_option('fewmail_smtp_stats', []);
    $stats['failed_sends'] = ($stats['failed_sends'] ?? 0) + 1;
    $stats['last_send_time'] = current_time('mysql');
    update_option('fewmail_smtp_stats', $stats);
    if ('yes' !== $options['disable_logs']) {
        $data = $wp_error->get_error_data('wp_mail_failed');
        unset($data['phpmailer_exception_code']);
        unset($data['attachments']);
        $data['error'] = $wp_error->get_error_message();
        if (!is_numeric(Db::$id)) {
            Db::create()->insert($data);
        } else {
            Db::create()->update($data, ['id' => Db::$id]);
        }
    }
}

function fewmail_smtp_update_success_status($mail_data) {
    $stats = get_option('fewmail_smtp_stats', []);
    $stats['successful_sends'] = ($stats['successful_sends'] ?? 0) + 1;
    $stats['last_send_time'] = current_time('mysql');
    update_option('fewmail_smtp_stats', $stats);
}

add_action('phpmailer_init', 'fewmail_smtp_init');
function fewmail_smtp_init($phpmailer) {
    $options = get_option('fewmail_smtp_options');
    if (!is_email($options['from']) || empty($options['host'])) {
        return;
    }
    $phpmailer->Timeout = 5;
    $phpmailer->Mailer = 'smtp';
    $phpmailer->From = $options['from'];
    $phpmailer->FromName = $options['from_name'];
    $phpmailer->Sender = $phpmailer->From;
    $phpmailer->AddReplyTo($phpmailer->From, $phpmailer->FromName);
    $phpmailer->Host = $options['host'];
    $phpmailer->SMTPAuth = 'yes' === $options['smtp_auth'];
    $phpmailer->Port = $options['port'];
    $phpmailer->SMTPSecure = $options['smtp_secure'];
    if ($phpmailer->SMTPAuth) {
        $phpmailer->Username = base64_decode($options['username']);
        $phpmailer->Password = base64_decode($options['password']);
    }
}

function fewmail_smtp_check_credentials($options = []) {
    if (!is_admin()) return false;
    if (empty($options)) $options = get_option('fewmail_smtp_options');
    $keys = ['username', 'password', 'host', 'port', 'smtp_auth'];
    foreach ($keys as $key) {
        if (empty($options[$key])) return false;
    }
    global $phpmailer;
    if (!($phpmailer instanceof PHPMailer\PHPMailer\PHPMailer)) {
        require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
        require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
        require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
        $phpmailer = new PHPMailer\PHPMailer\PHPMailer(true);
    }
    $smtp = $phpmailer->getSMTPInstance();
    $smtp->Timeout = 5;
    $phpmailer->Timeout = 5;
    $phpmailer->Mailer = 'smtp';
    $phpmailer->Host = $options['host'];
    $phpmailer->SMTPAuth = 'yes' === $options['smtp_auth'];
    $phpmailer->Port = $options['port'];
    $phpmailer->SMTPKeepAlive = false;
    if ($phpmailer->SMTPAuth) {
        $phpmailer->Username = base64_decode($options['username']);
        $phpmailer->Password = base64_decode($options['password']);
    }
    $phpmailer->SMTPSecure = $options['smtp_secure'];
    try {
        return $phpmailer->smtpConnect();
    } catch (Throwable $e) {
        error_log('FewMail SMTP credential check failed: ' . $e->getMessage());
        return false;
    }
}

function fewmail_smtp_plugin_action_links($links, $file) {
    if ($file == urldecode(FEWMAIL_SMTP_PLUGIN_PAGE)) {
        $page = FEWMAIL_SMTP_PLUGIN_SLUG;
        $links[] = "<a href='tools.php?page={$page}'>" . esc_html__('Settings', 'fewmail-smtp') . "</a>";
    }
    return $links;
}
add_filter('plugin_action_links', 'fewmail_smtp_plugin_action_links', 10, 2);

function fewmail_smtp_add_setting_page() {
    add_submenu_page(
        'tools.php',
        esc_html__('FewMail SMTP', 'fewmail-smtp'),
        esc_html__('SMTP', 'fewmail-smtp'),
        'manage_options',
        FEWMAIL_SMTP_PLUGIN_SLUG,
        'fewmail_smtp_setting_page'
    );
}

function fewmail_smtp_init_host_select() {
    $lists = Config::lists();
    $html = '<select id="configSelect" class="regular-text">';
    $html .= "<option value='' data-host='' data-port='' data-secure=''>" . esc_html__('Select a provider', 'fewmail-smtp') . "</option>";

    foreach ($lists as $group_key => $group) {
        $html .= "<optgroup label='" . esc_attr($group['label']) . "'>";
        foreach ($group['services'] as $key => $value) {
            $secure = isset($value['secure']) && $value['secure'] ? 'ssl' : '';
            $provider_key = sanitize_title($key);
            $html .= "<option value='{$provider_key}' data-host='{$value['host']}' data-port='{$value['port']}' data-secure='{$secure}' data-provider-name='" . esc_attr($key) . "'>{$key}</option>";
        }
        $html .= "</optgroup>";
    }
    $html .= '</select>';
    $html .= '<div id="provider-info" style="margin-top: 5px;">';
    $html .= '<span id="provider-link"></span>';
    $html .= '<p id="provider-tip" style="color: #646970; font-size: 12px; margin: 5px 0 0 0;"></p>';
    $html .= '</div>';

    echo wp_kses($html, [
        'select' => ['id' => [], 'class' => []],
        'optgroup' => ['label' => []],
        'option' => ['value' => [], 'data-host' => [], 'data-port' => [], 'data-secure' => [], 'data-provider-name' => []],
        'div' => ['id' => [], 'style' => []],
        'span' => ['id' => []],
        'p' => ['id' => [], 'style' => []],
    ]);
}

add_action('wp_ajax_fewmail_smtp_get_logs', 'fewmail_smtp_get_logs');
add_action('wp_ajax_fewmail_smtp_clear_logs', 'fewmail_smtp_clear_logs');
add_action('wp_ajax_fewmail_smtp_save_auto_clear', 'fewmail_smtp_save_auto_clear');
add_action('wp_ajax_fewmail_smtp_save_config', 'fewmail_smtp_save_config');
add_action('wp_ajax_fewmail_smtp_test_email', 'fewmail_smtp_test_email');
add_action('admin_enqueue_scripts', 'fewmail_smtp_enqueue_scripts');

function fewmail_smtp_enqueue_scripts() {
    $screen = get_current_screen();
    if (!$screen) return;
    if (strpos($screen->id, 'fewmail-smtp') !== false) {
        wp_enqueue_style('datatable', FEWMAIL_SMTP_ASSETS_URL . 'datatables.min.css', [], FEWMAIL_SMTP_VERSION);
        wp_register_script('datatable', FEWMAIL_SMTP_ASSETS_URL . 'datatables.min.js', ['jquery'], FEWMAIL_SMTP_VERSION, true);
        wp_localize_script('datatable', 'fewmail_smtp', ['ajaxurl' => admin_url('admin-ajax.php')]);
        wp_enqueue_script('datatable');
    }
}

function fewmail_smtp_get_logs() {
    check_ajax_referer('fewmail_smtp_logs', 'security');
    $result = Db::create()->get();
    $records_count = Db::create()->records_count();
    foreach ($result as $key => $value) {
        foreach ($value as $index => $data) {
            if ($index == 'message') {
                if (!preg_match('/<br>/', $data) && !preg_match('/<p>/', $data)) $data = nl2br($data);
                $result[$key][$index] = wp_kses_post($data);
            } elseif (is_serialized($data)) {
                $result[$key][$index] = implode(',', array_map('esc_html', maybe_unserialize($data)));
            } else {
                $result[$key][$index] = esc_html($data);
            }
        }
    }
    $response = [
        'draw' => isset($_GET['draw']) ? absint($_GET['draw']) : 1,
        'recordsTotal' => $records_count,
        'recordsFiltered' => $records_count,
        'data' => $result
    ];
    if (isset($_GET['search']['value']) && !empty($_GET['search']['value'])) {
        $response['recordsFiltered'] = count($result);
    }
    wp_send_json($response);
}

function fewmail_smtp_clear_logs() {
    check_ajax_referer('fewmail_smtp_logs', 'security');
    global $wpdb;
    $table = $wpdb->prefix . 'fewmail_smtp_logs';
    $wpdb->query("TRUNCATE TABLE $table");
    wp_send_json_success(['message' => esc_html__('Logs cleared successfully!', 'fewmail-smtp')]);
}

function fewmail_smtp_save_auto_clear() {
    check_ajax_referer('fewmail_smtp_logs', 'security');
    $days = isset($_POST['days']) ? absint($_POST['days']) : 30;
    update_option('fewmail_smtp_auto_clear_days', $days);
    if (!wp_next_scheduled('fewmail_smtp_auto_clear_logs')) {
        wp_schedule_event(time(), 'daily', 'fewmail_smtp_auto_clear_logs');
    }
    wp_send_json_success(['message' => esc_html__('Auto-clear settings saved!', 'fewmail-smtp')]);
}

function fewmail_smtp_save_config() {
    check_ajax_referer('fewmail_smtp_config', 'fewmail_smtp_config-nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => esc_html__('Insufficient privileges!', 'fewmail-smtp')]);
    }
    $options = [];
    $options['from'] = sanitize_email(wp_unslash(trim($_POST['from'])));
    $options['from_name'] = sanitize_text_field(trim($_POST['from_name']));
    $options['host'] = sanitize_text_field(wp_unslash(trim($_POST['host'])));
    $options['smtp_secure'] = sanitize_text_field(wp_unslash(trim($_POST['smtp_secure'])));
    $options['port'] = is_numeric(trim($_POST['port'])) ? absint(trim($_POST['port'])) : '';
    $options['smtp_auth'] = sanitize_text_field(wp_unslash(trim($_POST['smtp_auth'])));
    $options['username'] = base64_encode(sanitize_text_field(wp_unslash(trim($_POST['username']))));
    $options['password'] = base64_encode(sanitize_text_field(trim($_POST['password'])));
    $options['disable_logs'] = isset($_POST['disable_logs']) ? 'yes' : '';
    update_option('fewmail_smtp_options', $options);
    if (fewmail_smtp_check_credentials($options)) {
        wp_send_json_success(['message' => esc_html__('Configuration saved successfully!', 'fewmail-smtp')]);
    } else {
        wp_send_json_error(['message' => esc_html__('Configuration error, please check and resave!', 'fewmail-smtp')]);
    }
}

function fewmail_smtp_test_email() {
    check_ajax_referer('fewmail_smtp_testing', 'fewmail_smtp_testing-nonce');
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => esc_html__('Insufficient privileges!', 'fewmail-smtp')]);
    }
    $to = sanitize_email(wp_unslash(trim($_POST['to'])));
    $subject = sanitize_text_field(trim($_POST['subject']));
    $message = sanitize_textarea_field(trim($_POST['message']));
    if (!empty($to) && is_email($to) && !empty($subject) && !empty($message)) {
        global $phpmailer;
        if (!($phpmailer instanceof PHPMailer\PHPMailer\PHPMailer)) {
            require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
            require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
            require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
            $phpmailer = new PHPMailer\PHPMailer\PHPMailer(true);
        }
        $phpmailer->Timeout = 5;
        try {
            $result = wp_mail($to, $subject, $message);
            if ($result === true) {
                wp_send_json_success(['message' => esc_html__('Email sent!', 'fewmail-smtp')]);
            } else {
                $error = Db::$phpmailer_error ? Db::$phpmailer_error->get_error_message() : esc_html__('Email failed to send.', 'fewmail-smtp');
                wp_send_json_error(['message' => $error]);
            }
        } catch (Exception $e) {
            wp_send_json_error(['message' => esc_html__('SMTP connection failed: ' . $e->getMessage(), 'fewmail-smtp')]);
        }
    } else {
        wp_send_json_error(['message' => esc_html__('Fields are not filled in or there is an error.', 'fewmail-smtp')]);
    }
}

add_action('fewmail_smtp_auto_clear_logs', 'fewmail_smtp_auto_clear_logs');
function fewmail_smtp_auto_clear_logs() {
    global $wpdb;
    $days = get_option('fewmail_smtp_auto_clear_days', 30);
    $table = $wpdb->prefix . 'fewmail_smtp_logs';
    $wpdb->query($wpdb->prepare("DELETE FROM $table WHERE timestamp < DATE_SUB(NOW(), INTERVAL %d DAY)", $days));
}

// Integrate UpdatePulse Server for updates using PUC v5.3
require_once plugin_dir_path(__FILE__) . 'lib/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5p3\PucFactory;

$fewmailSmtpUpdateChecker = PucFactory::buildUpdateChecker(
    'https://updates.weixiaoduo.com/fewmail-smtp.json',
    __FILE__,
    'fewmail-smtp'
);
