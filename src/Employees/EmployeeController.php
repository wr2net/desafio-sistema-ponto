<?php

namespace App\Employees;

use App\Model\Query;

/**
 * Class EmployeeController
 * @package App\Employees
 */
class EmployeeController
{
    CONST TABLE = 'employees';

    /**
     * @return array|\Exception|\PDOException
     */
    public function index()
    {
        return (new Query())->findAll(self::TABLE);
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