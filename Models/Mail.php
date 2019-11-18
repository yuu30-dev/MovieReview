<?php

mb_language('Japanese');
mb_internal_encoding('UTF-8');

/**
 * メール管理クラス
 */
class Mail
{
    /** 送信元 */
    private const FROM = 'From: movie@yuu30.com' . "\r\n";

    /**
     * メールを送信します。
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return void
     */
    public static function send($to, $subject, $message)
    {
        mb_send_mail($to, $subject, $message, Mail::FROM);
    }
}
