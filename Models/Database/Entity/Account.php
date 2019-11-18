<?php

/**
 * アカウント情報クラス
 */
class Account
{
    /** @var int アカウントID */
    public $id;

    /** @var string Eメールアドレス */
    public $email;

    /** @var string パスワード */
    public $password;

    /** @var string ニックネーム */
    public $nickname;

    /** @var string 登録日 */
    public $register_date;

    /** @var string 誕生日 */
    public $birthday;

    /** @var int 性別 0:男、1：女 */
    public $gender;

    /** @var string アイコン */
    public $icon;
}
