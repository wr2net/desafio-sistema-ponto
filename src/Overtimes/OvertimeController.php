<?php

namespace App\Overtimes;

use App\Model\Query;

/**
 * Class OvertimeController
 * @package App\Overtimes
 */
class OvertimeController
{
    CONST TABLE = 'overtimes';

    /**
     * @return array|\Exception|\PDOException
     */
    public function index()
    {
        return (new Query())->findAll(self::TABLE);
    }

    /**
     * @param $table
     * @param $key
     * @param $value
     * @return array|\Exception|\PDOException
     */
    public function findJoin($table)
    {
        return (new Query())->getJoin(self::TABLE, $table);
    }

    /**
     * @param $data
     * @return bool|\Exception|\PDOException
     */
    public function store($data)
    {
        return (new Query())->execInsert(self::TABLE, $data);
    }

    /**
     * @param $id
     * @return \Exception|mixed|\PDOException
     */
    public function show($id)
    {
        return (new Query())->findById(self::TABLE, $id);
    }

    /**
     * @param $id
     * @return \Exception|mixed|\PDOException
     */
    public function findByUser($id)
    {
        return (new Query())->findByUser(self::TABLE, $id);
    }

    /**
     * @param $data
     * @param $id
     * @return bool|\Exception|\PDOException
     */
    public function update($data, $id)
    {
        return (new Query())->execUpdate(self::TABLE, $data, $id);
    }

    /**
     * @param $id
     * @return bool|\Exception|\PDOException
     */
    public function destroy($id)
    {
        return (new Query())->excluir(self::TABLE, $id);
    }
}