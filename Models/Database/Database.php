<?php

require_once __DIR__ . '/../Singleton.php';

/**
 * データベースラッパークラス
 */
class Database
{
    use Singleton;

    /** @var PDO PDOオブジェクト */
    private $dbh;
    /** @var PDOStatement PDOステートメント */
    private  $stmt;

    /**
     * コンストラクタ
     */
    protected function __construct()
    {
        $host = 'localhost';
        $database = 'movie';
        $dsn = 'mysql:dbname=' . $database . ';host=' . $host . ';charset=utf8';
        $user = 'root';
        $pass = 'root';
        $options = array(
            // SQL実行失敗時に例外をスロー
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // デフォルトフェッチモードを連想配列形式に設定
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // バッファー度クエリを使う（一度に結果セットを全て取得し、サーバー負荷を軽減）
            // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        );

        $this->dbh = new PDO($dsn, $user, $pass, $options);
    }

    /**
     * SQL文を設定します。
     *
     * @param string $query
     * @return Database
     */
    public function prepare($query): Database
    {
        $this->stmt = $this->dbh->prepare($query);
        return $this;
    }

    /**
     * データを紐付けます。
     *
     * @param string $param
     * @param mixed $value
     * @return Database
     */
    public function bind($param, $value): Database
    {
        $type = null;
        if (is_int($value)) {
            $type = PDO::PARAM_INT;
        } else if (is_bool($value)) {
            $type = PDO::PARAM_BOOL;
        } else if (is_null($value)) {
            $type = PDO::PARAM_NULL;
        } else {
            $type = PDO::PARAM_STR;
        }

        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }

    /**
     * 指定されたクラスで読み込みます。
     *
     * @param string $className
     * @return void
     */
    public function setClassFetchMode($className): Database
    {
        $this->stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $className);
        return $this;
    }

    /**
     * SQL文を実行します。
     *
     * @return bool
     */
    public function excute(): bool
    {
        return $this->stmt->execute();
    }

    /**
     * SQL文を実行し、結果を全て返します。
     *
     * @return array
     */
    public function resultAll(): array
    {
        $this->stmt->execute();
        return $this->stmt->fetchAll();
    }

    /**
     * SQL文を実行し、1行の結果を返します。
     *
     * @return array|bool
     */
    public function single()
    {
        $this->stmt->execute();
        return $this->stmt->fetch();
    }

    /**
     * SQL文を実行し、結果の行の数を返します。
     *
     * @return int
     */
    public function rowCount()
    {
        $this->stmt->execute();
        return $this->stmt->rowCount();
    }

    /**
     * 最後に挿入したIDを返します。
     *
     * @return int
     */
    public function lastInsertId(): int
    {
        return (int) $this->dbh->lastInsertId();
    }
}
