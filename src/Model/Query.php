<?php

namespace App\Model;

use Doctrine\DBAL\Driver\PDOException;
use Dotenv\Dotenv;

/**
 * Class Query
 * @package App\Model
 */
class Query
{
    /**
     * @var \PDO
     */
    private $PDO;

    /**
     * Query constructor.
     */
    public function __construct()
    {
        $dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $dbhost     = getenv('DB_HOST');
        $user       = getenv('DB_USER');
        $password   = getenv('DB_PASSWD');
        $dbname     = getenv('DB_NAME');

        $this->PDO = new \PDO("mysql:host=" . $dbhost . ";dbname=" . $dbname, $user, $password);
        $this->PDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param $tabela
     * @return array|\Exception|\PDOException
     */
    public function findAll($tabela)
    {
        try {
            $sth = $this->PDO->prepare("SELECT * FROM {$tabela}");
            $sth->execute();
            $qtd = $sth->rowCount();
            $dados = $sth->fetchAll(\PDO::FETCH_ASSOC);

            return [
                "data" => $dados,
                "qtd" => $qtd
            ];
        } catch (\PDOException $ex) {
            return $ex;
        }
    }

    /**
     * @param string $tabela
     * @param string $onefield
     * @param string $twofield
     * @param string $onevalue
     * @param string $twovalue
     * @return array|\Exception|\PDOException
     */
    public function twofields(string $tabela, string $onefield, string $twofield, string $onevalue, string $twovalue)
    {
        try {
            $sth = $this->PDO->prepare("SELECT * FROM {$tabela} WHERE {$onefield} = '{$onevalue}' AND {$twofield} = '{$twovalue}'");
            $sth->execute();
            return $sth->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            return $ex;
        }
    }
}