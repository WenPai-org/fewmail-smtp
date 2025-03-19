<?php
if (!defined('ABSPATH')) exit;

use FewMailSmtp\Db;

function fewmail_smtp_setting_page() {
    if (!current_user_can('manage_options')) wp_die('Insufficient privileges!');
    $smtpOptions = get_option('fewmail_smtp_options', true);
    $currentTab = fewmail_smtp_get_current_tab();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('FewMail SMTP', 'fewmail-smtp'); ?>
            <span style="font-size: 13px; padding-left: 10px;"><?php printf(esc_html__('Version: %s', 'fewmail-smtp'), esc_html(FEWMAIL_SMTP_VERSION)); ?></span>
            <a href="https://fewmail.com/document/" target="_blank" class="button button-secondary" style="margin-left: 10px;"><?php esc_html_e('Document', 'fewmail-smtp'); ?></a>
            <a href="https://fewmail.com/forums/" target="_blank" class="button button-secondary"><?php esc_html_e('Support', 'fewmail-smtp'); ?></a>
        </h1>

        <div id="fewmail-smtp-notices"></div>

        <div class="card">
            <div class="styles-sync-tabs">
                <?php foreach (fewmail_smtp_setting_page_tabs() as $tab => $label): ?>
                    <a href="<?php echo esc_url(admin_url('tools.php?page=' . FEWMAIL_SMTP_PLUGIN_SLUG . '&tab=' . $tab)); ?>"
                       class="styles-tab <?php echo esc_attr($currentTab == $tab ? 'active' : ''); ?>"
                       data-tab="<?php echo esc_attr($tab); ?>">
                        <?php echo esc_html($label); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="styles-sync-content">
                <div class="styles-section" data-section="config" style="<?php echo $currentTab === 'config' ? '' : 'display: none;'; ?>">
                    <h2><?php esc_html_e('SMTP Configuration', 'fewmail-smtp'); ?></h2>
                    <p><?php esc_html_e('Configure your SMTP settings to send emails from your WordPress site.', 'fewmail-smtp'); ?></p>
                    <form id="fewmail-smtp-config-form" method="post">
                        <table class="wp-list-table widefat fixed">
                            <tbody>
                                <tr>
                                    <th><?php esc_html_e('From', 'fewmail-smtp'); ?></th>
                                    <td>
                                        <input type="email" name="from" value="<?php echo esc_attr($smtpOptions['from']); ?>" class="regular-text" placeholder="your-email@example.com" required />
                                        <p style="color: #646970; font-size: 12px; margin: 5px 0 0 0;"><?php esc_html_e('The email address shown as the sender.', 'fewmail-smtp'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('From Name', 'fewmail-smtp'); ?></th>
                                    <td>
                                        <input type="text" name="from_name" value="<?php echo esc_attr($smtpOptions['from_name']); ?>" class="regular-text" placeholder="Your Name" required />
                                        <p style="color: #646970; font-size: 12px; margin: 5px 0 0 0;"><?php esc_html_e('The sender name shown in emails.', 'fewmail-smtp'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Service Provider', 'fewmail-smtp'); ?></th>
                                    <td>
                                        <?php fewmail_smtp_init_host_select(); ?>
                                        <p style="color: #646970; font-size: 12px; margin: 5px 0 0 0;"><?php esc_html_e('Pick your email service to auto-fill settings.', 'fewmail-smtp'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('SMTP Host', 'fewmail-smtp'); ?></th>
                                    <td>
                                        <input type="text" name="host" id="host" value="<?php echo esc_attr($smtpOptions['host']); ?>" class="regular-text" placeholder="smtp.example.com" required />
                                        <p style="color: #646970; font-size: 12px; margin: 5px 0 0 0;"><?php esc_html_e('The address of your email server.', 'fewmail-smtp'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('SMTP Port', 'fewmail-smtp'); ?></th>
                                    <td>
                                        <input type="text" name="port" id="port" value="<?php echo esc_attr($smtpOptions['port']); ?>" class="regular-text" placeholder="587" required />
                                        <p style="color: #646970; font-size: 12px; margin: 5px 0 0 0;"><?php esc_html_e('The port number for your email server.', 'fewmail-smtp'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('SMTP Secure', 'fewmail-smtp'); ?></th>
                                    <td>
                                        <?php foreach (['' => 'None', 'ssl' => 'SSL', 'tls' => 'TLS'] as $secure => $secureName): ?>
                                            <label style="margin-right: 15px;">
                                                <input name="smtp_secure" class="secure" type="radio" value="<?php echo esc_attr($secure); ?>" <?php checked($smtpOptions['smtp_secure'], $secure); ?> />
                                                <?php echo esc_html($secureName); ?>
                                            </label>
                                        <?php endforeach; ?>
                                        <p style="color: #646970; font-size: 12px; margin: 5px 0 0 0;"><?php esc_html_e('Encryption type for secure email sending.', 'fewmail-smtp'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('SMTP Authentication', 'fewmail-smtp'); ?></th>
                                    <td>
                                        <?php foreach (['no' => 'No', 'yes' => 'Yes'] as $auth => $authName): ?>
                                            <label style="margin-right: 15px;">
                                                <input name="smtp_auth" type="radio" value="<?php echo esc_attr($auth); ?>" <?php checked($smtpOptions['smtp_auth'], $auth); ?> />
                                                <?php echo esc_html($authName); ?>
                                            </label>
                                        <?php endforeach; ?>
                                        <p style="color: #646970; font-size: 12px; margin: 5px 0 0 0;"><?php esc_html_e('Set to “Yes” if login is required.', 'fewmail-smtp'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Username', 'fewmail-smtp'); ?></th>
                                    <td>
                                        <input type="text" name="username" value="<?php echo esc_attr(base64_decode($smtpOptions['username'])); ?>" class="regular-text" placeholder="your-email@example.com" required />
                                        <p style="color: #646970; font-size: 12px; margin: 5px 0 0 0;"><?php esc_html_e('Your email server login username.', 'fewmail-smtp'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Password', 'fewmail-smtp'); ?></th>
                                    <td>
                                        <input type="password" name="password" value="<?php echo esc_attr(base64_decode($smtpOptions['password'])); ?>" class="regular-text" placeholder="your-email-password" required />
                                        <p style="color: #646970; font-size: 12px; margin: 5px 0 0 0;"><?php esc_html_e('Your email server login password.', 'fewmail-smtp'); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Disable Logs', 'fewmail-smtp'); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="disable_logs" value="yes" <?php checked($smtpOptions['disable_logs'], 'yes'); ?> />
                                            <?php esc_html_e('Disable the email logging feature', 'fewmail-smtp'); ?>
                                        </label>
                                        <p style="color: #646970; font-size: 12px; margin: 5px 0 0 0;"><?php esc_html_e('Check to stop logging sent emails.', 'fewmail-smtp'); ?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="submit">
                            <input type="hidden" name="action" value="fewmail_smtp_save_config" />
                            <?php wp_nonce_field('fewmail_smtp_config', 'fewmail_smtp_config-nonce'); ?>
                            <input type="submit" class="button button-primary" value="<?php esc_html_e('Save Changes', 'fewmail-smtp'); ?>" />
                        </p>
                    </form>
                </div>

                <div class="styles-section" data-section="testing" style="<?php echo $currentTab === 'testing' ? '' : 'display: none;'; ?>">
                    <h2><?php esc_html_e('Test Email', 'fewmail-smtp'); ?></h2>
                    <p><?php esc_html_e('Send a test email to verify your SMTP settings.', 'fewmail-smtp'); ?></p>
                    <form id="fewmail-smtp-test-form" method="post">
                      <table class="wp-list-table widefat fixed">
                          <tbody>
                              <tr>
                                  <th><?php esc_html_e('To', 'fewmail-smtp'); ?></th>
                                  <td><input type="email" name="to" value="<?php echo esc_attr(get_option('admin_email')); ?>" class="regular-text" required /></td>
                              </tr>
                              <tr>
                                  <th><?php esc_html_e('Subject', 'fewmail-smtp'); ?></th>
                                  <td><input type="text" name="subject" value="<?php echo esc_attr__('Welcome to FewMail', 'fewmail-smtp'); ?>" class="regular-text" required /></td>
                              </tr>
                              <tr>
                                  <th><?php esc_html_e('Message', 'fewmail-smtp'); ?></th>
                                  <td><textarea name="message" cols="50" rows="5" class="large-text" required><?php echo esc_textarea(__('Congratulations, your website\'s email sending function is working. Start using it now!', 'fewmail-smtp')); ?></textarea></td>
                              </tr>
                          </tbody>
                      </table>
                        <p class="submit">
                            <input type="hidden" name="action" value="fewmail_smtp_test_email" />
                            <?php wp_nonce_field('fewmail_smtp_testing', 'fewmail_smtp_testing-nonce'); ?>
                            <input type="submit" class="button button-primary" value="<?php esc_html_e('Send Test', 'fewmail-smtp'); ?>" />
                        </p>
                    </form>
                </div>

                <div class="styles-section" data-section="logs" style="<?php echo $currentTab === 'logs' ? '' : 'display: none;'; ?>">
                    <h2><?php esc_html_e('Email Logs', 'fewmail-smtp'); ?></h2>
                    <p><?php esc_html_e('View and manage your email sending logs.', 'fewmail-smtp'); ?></p>
                    <div id="md-security" data-security="<?php echo esc_attr(wp_create_nonce('fewmail_smtp_logs')); ?>"></div>
                    <div style="position: relative;">
                        <table id="fewmail-smtp-log" class="wp-list-table widefat fixed striped" style="width:100%;">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('ID', 'fewmail-smtp'); ?></th>
                                    <th><?php esc_html_e('To', 'fewmail-smtp'); ?></th>
                                    <th><?php esc_html_e('Timestamp', 'fewmail-smtp'); ?></th>
                                    <th><?php esc_html_e('Subject', 'fewmail-smtp'); ?></th>
                                    <th><?php esc_html_e('Error', 'fewmail-smtp'); ?></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th><?php esc_html_e('ID', 'fewmail-smtp'); ?></th>
                                    <th><?php esc_html_e('To', 'fewmail-smtp'); ?></th>
                                    <th><?php esc_html_e('Timestamp', 'fewmail-smtp'); ?></th>
                                    <th><?php esc_html_e('Subject', 'fewmail-smtp'); ?></th>
                                    <th><?php esc_html_e('Error', 'fewmail-smtp'); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="loading-dots" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <span></span><span></span><span></span>
                        </div>
                    </div>
                    <p class="submit">
                        <button id="refresh-logs" class="button button-secondary"><?php esc_html_e('Refresh', 'fewmail-smtp'); ?></button>
                        <button id="clear-logs" class="button button-secondary"><?php esc_html_e('Clear Logs', 'fewmail-smtp'); ?></button>
                        <input type="number" id="auto-clear-days" name="auto_clear_days" min="1" value="<?php echo esc_attr(get_option('fewmail_smtp_auto_clear_days', 180)); ?>" class="small-text" />
                        <label for="auto-clear-days"><?php esc_html_e('days auto-clear', 'fewmail-smtp'); ?></label>
                        <button id="save-auto-clear" class="button button-primary"><?php esc_html_e('Save Auto-Clear', 'fewmail-smtp'); ?></button>
                    </p>
                </div>
            </div>
        </div>

        <div class="card">
            <h2><?php esc_html_e('Email Statistics', 'fewmail-smtp'); ?></h2>
            <p><?php esc_html_e('View statistics of emails sent through your SMTP configuration.', 'fewmail-smtp'); ?></p>
            <?php $stats = get_option('fewmail_smtp_stats', []); ?>
            <table class="wp-list-table widefat fixed">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Metric', 'fewmail-smtp'); ?></th>
                        <th><?php esc_html_e('Value', 'fewmail-smtp'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th><?php esc_html_e('Total Emails Attempted', 'fewmail-smtp'); ?></th>
                        <td><?php echo esc_html($stats['total_emails'] ?? 0); ?></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Successful Sends', 'fewmail-smtp'); ?></th>
                        <td><?php echo esc_html($stats['successful_sends'] ?? 0); ?></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Failed Sends', 'fewmail-smtp'); ?></th>
                        <td><?php echo esc_html($stats['failed_sends'] ?? 0); ?></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Last Send Time', 'fewmail-smtp'); ?></th>
                        <td><?php echo esc_html($stats['last_send_time'] ?? __('Never Sent', 'fewmail-smtp')); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var dt = null;
        var logsLoaded = false;

        function showNotice(message, isSuccess) {
            var noticeClass = isSuccess ? 'notice-success' : 'notice-error';
            var html = '<div class="notice ' + noticeClass + '"><p><strong>' + message + '</strong></p></div>';
            $('#fewmail-smtp-notices').html(html).show();
            setTimeout(function() {
                $('#fewmail-smtp-notices').fadeOut('slow', function() { $(this).empty(); });
            }, 3000);
        }

        $('#fewmail-smtp-config-form').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.post(fewmail_smtp.ajaxurl, formData, function(response) {
                showNotice(response.data.message, response.success);
                if (response.data.disable_logs === 'yes') {
                    $('.styles-tab[data-tab="logs"]').hide();
                    if ($('.styles-tab.active').data('tab') === 'logs') {
                        $('.styles-tab[data-tab="config"]').click();
                    }
                } else {
                    $('.styles-tab[data-tab="logs"]').show();
                }
            });
        });

        $('#fewmail-smtp-test-form').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.post(fewmail_smtp.ajaxurl, formData, function(response) {
                showNotice(response.data.message, response.success);
            });
        });

        $('.styles-tab').on('click', function(e) {
            e.preventDefault();
            $('.styles-tab').removeClass('active');
            $(this).addClass('active');
            var tab = $(this).data('tab');
            $('.styles-section').hide();
            $('.styles-section[data-section="' + tab + '"]').show();
            window.location.hash = tab;

            if (tab === 'logs' && !logsLoaded) {
                $('.loading-dots').show();
                dt = new DataTable('#fewmail-smtp-log', {
                    "idSrc": "id",
                    rowId: 'id',
                    paging: true,
                    "pagingType": "full_numbers",
                    "processing": true,
                    "serverSide": true,
                    scrollX: true,
                    "ajax": fewmail_smtp.ajaxurl + '?action=fewmail_smtp_get_logs&security=' + $('#md-security').data('security'),
                    "columns": [
                        {"class": "details-control", "orderable": true, "data": "id", "defaultContent": ""},
                        {"data": "to"},
                        {"data": "timestamp"},
                        {"data": "subject"},
                        {"data": "error"},
                    ],
                    "order": [[0, 'desc']],
                    "initComplete": function() {
                        $('.loading-dots').hide();
                        logsLoaded = true;
                    }
                });

                var detailRows = [];
                $('#fewmail-smtp-log tbody').on('click', 'tr td.details-control', function () {
                    var td = $(this).closest('tr');
                    var row = dt.row(td);
                    var idx = $.inArray(td.attr('id'), detailRows);
                    if (row.child.isShown()) {
                        td.removeClass('details');
                        row.child.hide();
                        detailRows.splice(idx, 1);
                    } else {
                        td.addClass('details');
                        row.child(format(row.data())).show();
                        if (idx === -1) detailRows.push(td.attr('id'));
                    }
                });

                dt.on('draw', function () {
                    $.each(detailRows, function (i, id) {
                        $('#' + id + ' td.details-control').trigger('click');
                    });
                });
                $('#refresh-logs').on('click', function () {
                    if (dt) {
                        $('.loading-dots').show();
                        dt.ajax.reload(function() {
                            $('.loading-dots').hide();
                        });
                    }
                });
                $('#clear-logs').on('click', function () {
                    if (confirm('<?php echo esc_js(__('Are you sure you want to clear all logs?', 'fewmail-smtp')); ?>')) {
                        $.post(fewmail_smtp.ajaxurl, {
                            action: 'fewmail_smtp_clear_logs',
                            security: $('#md-security').data('security')
                        }, function (response) {
                            if (response.success) {
                                dt.ajax.reload();
                                showNotice(response.data.message, true);
                            }
                        });
                    }
                });

                $('#save-auto-clear').on('click', function () {
                    var days = $('#auto-clear-days').val();
                    $.post(fewmail_smtp.ajaxurl, {
                        action: 'fewmail_smtp_save_auto_clear',
                        security: $('#md-security').data('security'),
                        days: days
                    }, function (response) {
                        if (response.success) showNotice(response.data.message, true);
                    });
                });

                function format(d) {
                    return d.message;
                }
            }
        });

        var hash = window.location.hash.replace('#', '');
        if (hash && $('.styles-tab[data-tab="' + hash + '"]').length) {
            $('.styles-tab').removeClass('active');
            $('.styles-tab[data-tab="' + hash + '"]').addClass('active');
            $('.styles-section').hide();
            $('.styles-section[data-section="' + hash + '"]').show();
        } else {
            $('.styles-section').hide();
            $('.styles-section[data-section="<?php echo esc_js($currentTab); ?>"]').show();
        }

        if ('<?php echo $smtpOptions['disable_logs']; ?>' === 'yes') {
            $('.styles-tab[data-tab="logs"]').hide();
        }

        $('#configSelect').change(function() {
            var host = $(this).find('option:selected').data('host');
            var port = $(this).find('option:selected').data('port');
            var secure = $(this).find('option:selected').data('secure');
            var providerName = $(this).find('option:selected').data('providerName');
            var providerValue = $(this).val();

            $('#host').val(host);
            $('#port').val(port);
            if (secure === 'ssl') $('.secure[value=ssl]').prop('checked', true);
            else if (secure === '') $('.secure[value=""]').prop('checked', true);
            else $('.secure[value=tls]').prop('checked', true);

            if (providerValue) {
                var linkText = '<?php echo esc_js(__('Get settings for %s', 'fewmail-smtp')); ?>'.replace('%s', providerName);
                $('#provider-link').html('<a href="https://fewmail.com/document/' + providerValue + '" target="_blank" style="color: #007cba; text-decoration: none;">' + linkText + ' ↗</a>');
                var tipText = '<?php echo esc_js(__('Please ensure you have the correct username and password for %s.', 'fewmail-smtp')); ?>'.replace('%s', providerName);
                $('#provider-tip').text(tipText);
            } else {
                $('#provider-link').empty();
                $('#provider-tip').empty();
            }
        });
    });
    </script>

    <style>
    .wp-core-ui select {
        max-width: -webkit-fill-available;
    }
    .card {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        max-width: unset;
        margin-top: 20px;
        padding: 20px;
    }
    .styles-sync-tabs {
        display: flex;
        gap: 5px;
        border-bottom: 1px solid #c3c4c7;
        margin-bottom: 20px;
    }
    .styles-tab {
        padding: 8px 16px;
        border: none;
        background: none;
        cursor: pointer;
        font-size: 14px;
        border-bottom: 2px solid transparent;
        text-decoration: none;
        color: #23282d;
    }
    .styles-tab.active {
        border-bottom: 2px solid #007cba;
        font-weight: 600;
        background: #f0f0f1;
    }
    .styles-tab:hover:not(.active) {
        background: #f0f0f1;
        border-bottom-color: #dcdcde;
    }
    .styles-sync-content {
        flex: 1;
    }
    .styles-section {
        display: none;
    }
    .notice {
        border-radius: 3px;
        margin-bottom: 20px;
    }
    .notice-success {
        background-color: #dff0d8;
        border-left: 4px solid #46b450;
    }
    .notice-error {
        background-color: #f2dede;
        border-left: 4px solid #dc3232;
    }
    td { vertical-align: middle; }
    #fewmail-smtp-log_wrapper #dt-length-0 { width: 50px; }
    .details-control:before { content: '\25B6'; padding-right: 5px; }
    .details-control.details:before { content: '\25BC'; padding-right: 5px; }
    </style>
    <?php
}
