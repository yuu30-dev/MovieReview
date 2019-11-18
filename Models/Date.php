<?php

date_default_timezone_set('Asia/Tokyo');

/**
 * DateTimeを拡張した日付クラス
 */
class Date extends DateTime
{
    /** @var array 曜日一覧 */
    private const weeks = [
        '0' => '日',
        '1' => '月',
        '2' => '火',
        '3' => '水',
        '4' => '木',
        '5' => '金',
        '6' => '土',
    ];

    /**
     * 曜日文字列を返します。
     *
     * @return string
     */
    public function week(): string
    {
        return $this::weeks[$this->format('w')];
    }

    /**
     * YYYY年MM月dd日形式の日付を返します。
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->format('Y年m月d日');
    }
}
