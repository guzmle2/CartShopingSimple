<?php
/**
 * User: Guzmle2
 * Date: 11/3/2017
 * Time: 4:34 PM
 */
namespace Entity;
require_once  'Connection.php';


class User
{
    private $id;

    /**
     * User constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }


    function getBalanceUserId()
    {
        $conexion = new Connection();
        $stmt = $conexion->prepare("SELECT * FROM user where id = :id");
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $prueba = $stmt->fetchAll();
        return $prueba[0]['balance'];
    }

    function insertUser()
    {

        $conexion = new Connection();
        $stmt = $conexion->prepare("INSERT INTO `user` (`balance`) VALUES ('100');");
        $stmt->execute();
        $prueba = $conexion->lastInsertId();
        return $prueba;
    }


}