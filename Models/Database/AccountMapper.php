<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Entity/Account.php';
require_once __DIR__ . '/../../Models/Date.php';

/**
 * アカウントテーブル管理クラス
 */
class AccountMapper
{
    /**
     * アカウント情報を追加します。
     *
     * @param string $email
     * @param string $password
     * @param string $nickname
     * @return int
     */
    public static function add($email, $password, $nickname): int
    {
        $sql = 'INSERT INTO accounts (email, password, nickname, register_date) 
        VALUES(:email, :password, :nickname, :register_date)';

        // パスワードをハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // 登録日を作成
        $registerDate = (new Date())->format('Y-m-d');

        $db = Database::getInstance();
        $db->prepare($sql)
            ->bind(':email', $email)
            ->bind(':password', $hashedPassword)
            ->bind(':nickname', $nickname)
            ->bind(':register_date', $registerDate)
            ->excute();

        return $db->lastInsertId();
    }

    /**
     * アカウントのパスワードを更新します。
     *
     * @param int $accountId
     * @param string $password
     * @return bool
     */
    public static function updatePassword($accountId, $password): bool
    {
        // Logger::debug('update password');

        $sql = 'UPDATE accounts SET password=:password WHERE id=:id';

        // パスワードをハッシュ化
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        return Database::getInstance()
            ->prepare($sql)
            ->bind(':id', $accountId)
            ->bind(':password', $hashedPassword)
            ->excute();
    }

    /**
     * アカウントのプロフィール情報を更新します。
     *
     * @param Account $account
     * @return bool
     */
    public static function updateProfile(Account $account): bool
    {
        // Logger::debug('update profile : ' . print_r($account, true));

        $sql = 'UPDATE accounts 
        SET email=:email, nickname=:nickname, birthday=:birthday, gender=:gender, icon=:icon
        WHERE id=:id';

        return Database::getInstance()
            ->prepare($sql)
            ->bind(':id',       $account->id)
            ->bind(':email',    $account->email)
            ->bind(':nickname', $account->nickname)
            ->bind(':birthday', $account->birthday ?? null)
            ->bind(':gender',   $account->gender ?? null)
            ->bind(':icon',     $account->icon)
            ->excute();
    }

    /**
     * 指定されたキー、値を元にアカウント情報を取得します。
     *
     * @param string $key
     * @param string $value
     * @return Account|bool
     */
    public static function get($key, $value)
    {
        $sql = 'SELECT * FROM accounts WHERE ' . $key . ' = :' . $key;
        $accounts = Database::getInstance()
            ->prepare($sql)
            ->bind(':' . $key, $value)
            ->setClassFetchMode('Account')
            ->resultAll();

        // Logger::debug('account : ' . print_r($accounts, true));

        return empty($accounts) ? false : current($accounts);
    }
}
