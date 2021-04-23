<?php

namespace App\Model;

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

    /**
     * @param $tabela
     * @param $dados
     * @return bool|\Exception|\PDOException
     */
    public function execInsert($tabela, $dados)
    {
        try {
            foreach ($dados as $key => $value) {
                if ($key == 'password') {
                    $value = base64_encode(md5($value));
                }
                if ($key != 'id') {
                    $fields[] = $key;
                    $links[] = "'" . str_replace("'", '"', $value) . "'";
                }

                $qtd = ['qtd' => 0];
                if ($key == 'email' && $tabela == 'users') {
                    $qtd = $this->listaSessao("users", "email", $value);
                }
            }

            if ($qtd['qtd'] > 0) {
                return false;
            }

            $fields = implode(', ', $fields);
            $links = implode(', ', $links);
            $sth = $this->PDO->prepare("INSERT INTO {$tabela} ({$fields}) VALUES ({$links})");
            $sth->execute();
            return true;
        } catch (\PDOException $ex) {
            return $ex;
        }
    }

    /**
     * @param $tabela
     * @param $field
     * @param $value
     * @return array|\Exception|\PDOException
     */
    public function listaSessao($tabela, $field, $value)
    {
        try {
            $sth = $this->PDO->prepare("SELECT * FROM {$tabela} WHERE {$field} = '{$value}'");
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
     * @param $tabela
     * @param $id
     * @return \Exception|mixed|\PDOException
     */
    public function findById($tabela, $id)
    {
        try {
            $sth = $this->PDO->prepare("SELECT * FROM {$tabela} WHERE id = '{$id}'");
            $sth->execute();
            return $sth->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            return $ex;
        }
    }

    /**
     * @param $tabela
     * @param $id
     * @return \Exception|mixed|\PDOException
     */
    public function findByUser($tabela, $id)
    {
        try {
            $sth = $this->PDO->prepare("SELECT * FROM {$tabela} WHERE employee_id = '{$id}'");
            $sth->execute();
            return $sth->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            return $ex;
        }
    }

    /**
     * @param $tabela1
     * @param $tabela2
     * @param $key
     * @param $value
     * @return array|\Exception|\PDOException
     */
    public function getJoin($tabela1, $tabela2)
    {
        try {
            $sth = $this->PDO->prepare("SELECT {$tabela1}.*,{$tabela2}.*,{$tabela1}.id AS idKey 
                         FROM {$tabela1} INNER JOIN {$tabela2} ON {$tabela1}.employee_id = {$tabela2}.id;");
            $sth->execute();
            $dados = $sth->fetchAll(\PDO::FETCH_ASSOC);
            return [
                "data" => $dados
            ];
        } catch (\PDOException $ex) {
            return $ex;
        }
    }

    /**
     * @param $tabela
     * @param $id
     * @return bool|\Exception|\PDOException
     */
    public function excluir($tabela, $id)
    {
        try {
            $sth = $this->PDO->prepare("DELETE FROM {$tabela} WHERE id = '{$id}'");
            $sth->execute();
            return true;
        } catch (\PDOException $ex) {
            return $ex;
        }
    }

    public function execUpdate($tabela, $dados, $id)
    {
        try {
            $sets = [];
            foreach ($dados as $key => $values) {
                if ($key != "id") {
                    if ($tabela == "users" AND $key == "password") {
                        $pw = base64_encode(md5($values));
                        $sets[] = "{$key} = '{$pw}'";
                    } else {
                        $sets[] = "{$key} = '". str_replace("'", '"',$values) . "'";
                    }
                }
            }

            $sth = $this->PDO->prepare("UPDATE {$tabela} SET " . implode(',', $sets) . " WHERE id = '{$id}'");
            $sth->execute();
            return true;
        } catch (\PDOException $ex) {
            return $ex;
        }
    }
}