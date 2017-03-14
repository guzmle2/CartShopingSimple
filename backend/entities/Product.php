<?php
    /**
     * User: Guzmle2
     * Date: 11/3/2017
     * Time: 4:36 PM
     */
    namespace Entity;

    use PDO;

    require_once 'Connection.php';

    class Product
    {

        private $id;
        private $name;
        private $qty;
        private $price;

        /**
         * Product constructor.
         */
        public function __construct ()
        {
        }

        function getAllProduct ()
        {
            $conexion = new Connection();
            $stmt = $conexion->prepare( "SELECT * FROM product" );
            $stmt->execute();

            return json_encode( $stmt->fetchAll() );
        }

        function addRanking ( $idUsuario, $idProducto, $ranking )
        {
            $date = date( 'm/d/Y h:i:s', time() );
            $conexion = new Connection();
            $stmt = $conexion->prepare( "INSERT INTO `ranking` (`idUsuario`, `idProducto`,"
                                        . " `date`, `ranking`) VALUES (:idUsuario, :idProducto, :dateT, :ranking)" );
            $stmt->bindParam( ':idProducto', $idProducto );
            $stmt->bindParam( ':idUsuario', $idUsuario );
            $stmt->bindParam( ':dateT', $date );
            $stmt->bindParam( ':ranking', $ranking );
            $stmt->execute();
            return true;
        }

        function getMedia ()
        {
            $conexion = new Connection();
            $stmt = $conexion->prepare( "SELECT product.id, round(AVG(ranking.ranking), 1) "
                                        . "as media from product inner join ranking on product.id= ranking.idProducto  "
                                        . "GROUP BY product.id;" );
            $stmt->execute();
            return json_encode( $stmt->fetchAll() );
        }
    }