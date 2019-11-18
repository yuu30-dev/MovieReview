<?php

//ログを取るか
ini_set('log_errors', 'on');
//ログの出力ファイルを指定
ini_set('error_log', 'php.log');

/**
 * ログ出力レベル
 */
abstract class LogLevel
{
    const DEBUG     = 'debug';
    const INFO      = 'info';
    const WARNING   = 'warning';
    const ERROR     = 'error';
}

/**
 * ログ出力クラス
 */
class Logger
{
    /** @var bool デバッグ出力フラグ */
    private const DEBUG = false;

    /**
     * デバッグ用ログ出力
     *
     * @param string $message
     * @return void
     */
    static function debug($message)
    {
        if (Logger::DEBUG) {
            Logger::log(LogLevel::DEBUG, $message);
        }
    }

    /**
     * 情報用ログ出力
     *
     * @param string $message
     * @return void
     */
    static function info($message)
    {
        Logger::log(LogLevel::INFO, $message);
    }

    /**
     * 警告用ログ出力
     *
     * @param string $message
     * @return void
     */
    static function warning($message)
    {
        Logger::log(LogLevel::WARNING, $message);
    }

    /**
     * エラー用ログ出力
     *
     * @param string $message
     * @return void
     */
    static function error($message)
    {
        Logger::log(LogLevel::ERROR, $message);
    }

    /**
     * ログ出力
     *
     * @param string $level
     * @param string $message
     * @return void
     */
    private static function log($level, $message)
    {
        $track = debug_backtrace()[1];
        $caller = pathinfo($track['file'], PATHINFO_BASENAME) . '::' . $track['line'];
        error_log('[' . $level . '][' . $caller . '] ' . $message);
    }
}
