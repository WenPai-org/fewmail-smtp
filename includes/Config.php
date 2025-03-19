<?php
namespace FewMailSmtp;

class Config
{
    public static function lists()
    {
        return [
            // Global Popular Services（按字母顺序 + 使用频率）
            'global_popular' => [
                'label' => __('Global Popular Services', 'fewmail-smtp'),
                'services' => [
                    __('AOL', 'fewmail-smtp') => [
                        'host' => 'smtp.aol.com',
                        'port' => 587,
                    ],
                    __('Gmail', 'fewmail-smtp') => [
                        'host' => 'smtp.gmail.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('iCloud', 'fewmail-smtp') => [
                        'host' => 'smtp.mail.me.com',
                        'port' => 587,
                    ],
                    __('Outlook365', 'fewmail-smtp') => [
                        'host' => 'smtp.office365.com',
                        'port' => 587,
                        'secure' => false,
                    ],
                    __('Yahoo', 'fewmail-smtp') => [
                        'host' => 'smtp.mail.yahoo.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Zoho', 'fewmail-smtp') => [
                        'host' => 'smtp.zoho.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                ],
            ],

            // China Popular Services（保持原顺序，按市场占有率排列）
            'china_popular' => [
                'label' => __('China Popular Services', 'fewmail-smtp'),
                'services' => [
                    __('163', 'fewmail-smtp') => [
                        'host' => 'smtp.163.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('126', 'fewmail-smtp') => [
                        'host' => 'smtp.126.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('QQ', 'fewmail-smtp') => [
                        'host' => 'smtp.qq.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('QQex', 'fewmail-smtp') => [
                        'host' => 'smtp.exmail.qq.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Aliyun', 'fewmail-smtp') => [
                        'host' => 'smtp.aliyun.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Aliyun Qiye', 'fewmail-smtp') => [
                        'host' => 'smtp.qiye.aliyun.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                ],
            ],

            // Business Email Services（第三方服务优先，SES按区域字母排序）
            'business' => [
                'label' => __('Business Email Services', 'fewmail-smtp'),
                'services' => [
                    // 第三方服务
                    __('Mailgun', 'fewmail-smtp') => [
                        'host' => 'smtp.mailgun.org',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Mailjet', 'fewmail-smtp') => [
                        'host' => 'in.mailjet.com',
                        'port' => 587,
                    ],
                    __('Postmark', 'fewmail-smtp') => [
                        'host' => 'smtp.postmarkapp.com',
                        'port' => 2525,
                    ],
                    __('SendGrid', 'fewmail-smtp') => [
                        'host' => 'smtp.sendgrid.net',
                        'port' => 587,
                    ],
                    __('SendinBlue', 'fewmail-smtp') => [
                        'host' => 'smtp-relay.brevo.com',
                        'port' => 587,
                    ],
                    __('SendPulse', 'fewmail-smtp') => [
                        'host' => 'smtp-pulse.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    
                    // AWS SES 按区域字母排序
                    __('SES-AP-NORTHEAST-1', 'fewmail-smtp') => [
                        'host' => 'email-smtp.ap-northeast-1.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-AP-NORTHEAST-2', 'fewmail-smtp') => [
                        'host' => 'email-smtp.ap-northeast-2.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-AP-NORTHEAST-3', 'fewmail-smtp') => [
                        'host' => 'email-smtp.ap-northeast-3.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-AP-SOUTH-1', 'fewmail-smtp') => [
                        'host' => 'email-smtp.ap-south-1.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-AP-SOUTHEAST-1', 'fewmail-smtp') => [
                        'host' => 'email-smtp.ap-southeast-1.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-AP-SOUTHEAST-2', 'fewmail-smtp') => [
                        'host' => 'email-smtp.ap-southeast-2.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-EU-WEST-1', 'fewmail-smtp') => [
                        'host' => 'email-smtp.eu-west-1.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-US-EAST-1', 'fewmail-smtp') => [
                        'host' => 'email-smtp.us-east-1.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-US-WEST-2', 'fewmail-smtp') => [
                        'host' => 'email-smtp.us-west-2.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                ],
            ],

            // Other Services（按字母顺序排列）
            'others' => [
                'label' => __('Other Services', 'fewmail-smtp'),
                'services' => [
                    __('1und1', 'fewmail-smtp') => [
                        'host' => 'smtp.1und1.de',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Bluewin', 'fewmail-smtp') => [
                        'host' => 'smtpauths.bluewin.ch',
                        'port' => 465,
                    ],
                    __('DebugMail', 'fewmail-smtp') => [
                        'host' => 'debugmail.io',
                        'port' => 25,
                    ],
                    __('Ethereal', 'fewmail-smtp') => [
                        'host' => 'smtp.ethereal.email',
                        'port' => 587,
                    ],
                    __('FastMail', 'fewmail-smtp') => [
                        'host' => 'smtp.fastmail.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Forward Email', 'fewmail-smtp') => [
                        'host' => 'smtp.forwardemail.net',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('GandiMail', 'fewmail-smtp') => [
                        'host' => 'mail.gandi.net',
                        'port' => 587,
                    ],
                    __('Godaddy', 'fewmail-smtp') => [
                        'host' => 'smtpout.secureserver.net',
                        'port' => 25,
                    ],
                    __('GodaddyAsia', 'fewmail-smtp') => [
                        'host' => 'smtp.asia.secureserver.net',
                        'port' => 25,
                    ],
                    __('GodaddyEurope', 'fewmail-smtp') => [
                        'host' => 'smtp.europe.secureserver.net',
                        'port' => 25,
                    ],
                    __('Infomaniak', 'fewmail-smtp') => [
                        'host' => 'mail.infomaniak.com',
                        'port' => 587,
                    ],
                    __('Mail.ru', 'fewmail-smtp') => [
                        'host' => 'smtp.mail.ru',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Mailcatch.app', 'fewmail-smtp') => [
                        'host' => 'sandbox-smtp.mailcatch.app',
                        'port' => 2525,
                    ],
                    __('Mailosaur', 'fewmail-smtp') => [
                        'host' => 'mailosaur.io',
                        'port' => 25,
                    ],
                    __('Mailtrap', 'fewmail-smtp') => [
                        'host' => 'smtp.mailtrap.io',
                        'port' => 2525,
                    ],
                    __('Mandrill', 'fewmail-smtp') => [
                        'host' => 'smtp.mandrillapp.com',
                        'port' => 587,
                    ],
                    __('Naver', 'fewmail-smtp') => [
                        'host' => 'smtp.naver.com',
                        'port' => 587,
                    ],
                    __('OhMySMTP', 'fewmail-smtp') => [
                        'host' => 'smtp.ohmysmtp.com',
                        'port' => 587,
                        'secure' => false,
                    ],
                    __('One', 'fewmail-smtp') => [
                        'host' => 'send.one.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('OpenMailBox', 'fewmail-smtp') => [
                        'host' => 'smtp.openmailbox.org',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SendCloud', 'fewmail-smtp') => [
                        'host' => 'smtp.sendcloud.net',
                        'port' => 2525,
                    ],
                    __('Sparkpost', 'fewmail-smtp') => [
                        'host' => 'smtp.sparkpostmail.com',
                        'port' => 587,
                        'secure' => false,
                    ],
                    __('Tipimail', 'fewmail-smtp') => [
                        'host' => 'smtp.tipimail.com',
                        'port' => 587,
                    ],
                    __('Yandex', 'fewmail-smtp') => [
                        'host' => 'smtp.yandex.ru',
                        'port' => 465,
                        'secure' => true,
                    ],
                ],
            ],
        ];
    }
}