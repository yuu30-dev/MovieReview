<?php

/**
 * 評価クラス
 */
class Rate
{
    /** 評価に対する星一覧 */
    private const stars = [
        1 => '★☆☆☆☆',
        2 => '★★☆☆☆',
        3 => '★★★☆☆',
        4 => '★★★★☆',
        5 => '★★★★★'
    ];

    /**
     * 評価を星に変換します。
     *
     * @param string $rate
     * @return string
     */
    public static function convertRateToStar($rate): string
    {
        return Rate::stars[floor($rate)] ?? '';
    }
}
