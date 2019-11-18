<?php

require_once __DIR__ . '/../Log/Logger.php';

/**
 * シングルトン用
 */
trait Singleton
{
    /**
     * コンストラクタ
     */
    protected function __construct()
    { }

    /**
     * シングルトン
     *
     * @return mixed
     */
    final public static function getInstance()
    {
        static $instance;
        return $instance ?: $instance = new static;
    }

    /**
     * クローンを制御
     *
     * @return void
     */
    final public function __clone()
    {
        throw new Exception("this instance is singleton class.");
    }
}
