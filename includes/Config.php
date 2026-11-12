<?php
namespace FeiMailSmtp;

class Config
{
    public static function lists()
    {
        return [
            // Global Popular Services（按字母顺序 + 使用频率）
            'global_popular' => [
                'label' => __('Global Popular Services', 'feimail-smtp'),
                'services' => [
                    __('AOL', 'feimail-smtp') => [
                        'host' => 'smtp.aol.com',
                        'port' => 587,
                    ],
                    __('Gmail', 'feimail-smtp') => [
                        'host' => 'smtp.gmail.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('iCloud', 'feimail-smtp') => [
                        'host' => 'smtp.mail.me.com',
                        'port' => 587,
                    ],
                    __('Outlook365', 'feimail-smtp') => [
                        'host' => 'smtp.office365.com',
                        'port' => 587,
                        'secure' => false,
                    ],
                    __('Yahoo', 'feimail-smtp') => [
                        'host' => 'smtp.mail.yahoo.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Zoho', 'feimail-smtp') => [
                        'host' => 'smtp.zoho.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                ],
            ],

            // China Popular Services（保持原顺序，按市场占有率排列）
            'china_popular' => [
                'label' => __('China Popular Services', 'feimail-smtp'),
                'services' => [
                    __('163', 'feimail-smtp') => [
                        'host' => 'smtp.163.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('126', 'feimail-smtp') => [
                        'host' => 'smtp.126.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('QQ', 'feimail-smtp') => [
                        'host' => 'smtp.qq.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('QQex', 'feimail-smtp') => [
                        'host' => 'smtp.exmail.qq.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Aliyun', 'feimail-smtp') => [
                        'host' => 'smtp.aliyun.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Aliyun Qiye', 'feimail-smtp') => [
                        'host' => 'smtp.qiye.aliyun.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                ],
            ],

            // Business Email Services（第三方服务优先，SES按区域字母排序）
            'business' => [
                'label' => __('Business Email Services', 'feimail-smtp'),
                'services' => [
                    // 第三方服务
                    __('Mailgun', 'feimail-smtp') => [
                        'host' => 'smtp.mailgun.org',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Mailjet', 'feimail-smtp') => [
                        'host' => 'in.mailjet.com',
                        'port' => 587,
                    ],
                    __('Postmark', 'feimail-smtp') => [
                        'host' => 'smtp.postmarkapp.com',
                        'port' => 2525,
                    ],
                    __('SendGrid', 'feimail-smtp') => [
                        'host' => 'smtp.sendgrid.net',
                        'port' => 587,
                    ],
                    __('SendinBlue', 'feimail-smtp') => [
                        'host' => 'smtp-relay.brevo.com',
                        'port' => 587,
                    ],
                    __('SendPulse', 'feimail-smtp') => [
                        'host' => 'smtp-pulse.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    
                    // AWS SES 按区域字母排序
                    __('SES-AP-NORTHEAST-1', 'feimail-smtp') => [
                        'host' => 'email-smtp.ap-northeast-1.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-AP-NORTHEAST-2', 'feimail-smtp') => [
                        'host' => 'email-smtp.ap-northeast-2.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-AP-NORTHEAST-3', 'feimail-smtp') => [
                        'host' => 'email-smtp.ap-northeast-3.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-AP-SOUTH-1', 'feimail-smtp') => [
                        'host' => 'email-smtp.ap-south-1.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-AP-SOUTHEAST-1', 'feimail-smtp') => [
                        'host' => 'email-smtp.ap-southeast-1.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-AP-SOUTHEAST-2', 'feimail-smtp') => [
                        'host' => 'email-smtp.ap-southeast-2.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-EU-WEST-1', 'feimail-smtp') => [
                        'host' => 'email-smtp.eu-west-1.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-US-EAST-1', 'feimail-smtp') => [
                        'host' => 'email-smtp.us-east-1.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SES-US-WEST-2', 'feimail-smtp') => [
                        'host' => 'email-smtp.us-west-2.amazonaws.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                ],
            ],

            // Other Services（按字母顺序排列）
            'others' => [
                'label' => __('Other Services', 'feimail-smtp'),
                'services' => [
                    __('1und1', 'feimail-smtp') => [
                        'host' => 'smtp.1und1.de',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Bluewin', 'feimail-smtp') => [
                        'host' => 'smtpauths.bluewin.ch',
                        'port' => 465,
                    ],
                    __('DebugMail', 'feimail-smtp') => [
                        'host' => 'debugmail.io',
                        'port' => 25,
                    ],
                    __('Ethereal', 'feimail-smtp') => [
                        'host' => 'smtp.ethereal.email',
                        'port' => 587,
                    ],
                    __('FastMail', 'feimail-smtp') => [
                        'host' => 'smtp.fastmail.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Forward Email', 'feimail-smtp') => [
                        'host' => 'smtp.forwardemail.net',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('GandiMail', 'feimail-smtp') => [
                        'host' => 'mail.gandi.net',
                        'port' => 587,
                    ],
                    __('Godaddy', 'feimail-smtp') => [
                        'host' => 'smtpout.secureserver.net',
                        'port' => 25,
                    ],
                    __('GodaddyAsia', 'feimail-smtp') => [
                        'host' => 'smtp.asia.secureserver.net',
                        'port' => 25,
                    ],
                    __('GodaddyEurope', 'feimail-smtp') => [
                        'host' => 'smtp.europe.secureserver.net',
                        'port' => 25,
                    ],
                    __('Infomaniak', 'feimail-smtp') => [
                        'host' => 'mail.infomaniak.com',
                        'port' => 587,
                    ],
                    __('Mail.ru', 'feimail-smtp') => [
                        'host' => 'smtp.mail.ru',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('Mailcatch.app', 'feimail-smtp') => [
                        'host' => 'sandbox-smtp.mailcatch.app',
                        'port' => 2525,
                    ],
                    __('Mailosaur', 'feimail-smtp') => [
                        'host' => 'mailosaur.io',
                        'port' => 25,
                    ],
                    __('Mailtrap', 'feimail-smtp') => [
                        'host' => 'smtp.mailtrap.io',
                        'port' => 2525,
                    ],
                    __('Mandrill', 'feimail-smtp') => [
                        'host' => 'smtp.mandrillapp.com',
                        'port' => 587,
                    ],
                    __('Naver', 'feimail-smtp') => [
                        'host' => 'smtp.naver.com',
                        'port' => 587,
                    ],
                    __('OhMySMTP', 'feimail-smtp') => [
                        'host' => 'smtp.ohmysmtp.com',
                        'port' => 587,
                        'secure' => false,
                    ],
                    __('One', 'feimail-smtp') => [
                        'host' => 'send.one.com',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('OpenMailBox', 'feimail-smtp') => [
                        'host' => 'smtp.openmailbox.org',
                        'port' => 465,
                        'secure' => true,
                    ],
                    __('SendCloud', 'feimail-smtp') => [
                        'host' => 'smtp.sendcloud.net',
                        'port' => 2525,
                    ],
                    __('Sparkpost', 'feimail-smtp') => [
                        'host' => 'smtp.sparkpostmail.com',
                        'port' => 587,
                        'secure' => false,
                    ],
                    __('Tipimail', 'feimail-smtp') => [
                        'host' => 'smtp.tipimail.com',
                        'port' => 587,
                    ],
                    __('Yandex', 'feimail-smtp') => [
                        'host' => 'smtp.yandex.ru',
                        'port' => 465,
                        'secure' => true,
                    ],
                ],
            ],
        ];
    }
}