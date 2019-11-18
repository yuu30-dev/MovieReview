<?php

/**
 * セッションクラス
 */
class Session
{
    /**
     * セッションを開始します。
     *
     * @return void
     */
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * セッションを破棄します。
     *
     * @return void
     */
    public static function destroy()
    {
        session_destroy();
    }

    /**
     * セッションから指定されたキー存在するか判定します。
     *
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return !empty($_SESSION[$key]);
    }

    /**
     * セッションから指定されたキーに紐付く値を取得します。
     *
     * @param string $key
     * @return mixed
     */
    public static function get($key)
    {
        return $_SESSION[$key];
    }

    /**
     * セッションに値を設定します。
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * セッションから指定されたキーの値を削除します。
     *
     * @param string $key
     * @return void
     */
    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }
}
