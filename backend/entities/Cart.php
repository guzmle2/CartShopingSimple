<?php
    /**
     * User: Guzmle2
     * Date: 11/3/2017
     * Time: 4:36 PM
     */
    namespace Entity;

    use Exception;
    use PDO;

    class Cart
    {

        private $idUser;
        private $idProduct;
        private $qty;
        private $iDqty;

        /**
         * Cart constructor.
         * @param $idUser
         * @param $idProduct
         */
        public function __construct ( $idUser, $idProduct )
        {
            $this->idUser = $idUser;
            $this->idProduct = $idProduct;
        }

        public function getCarsCount ()
        {
            $conexion = new Connection();
            $stmt = $conexion->prepare( "SELECT  sum(qty) as result from cart where idUsuario = :idUsuario" );
            $stmt->bindParam( ':idUsuario', $this->idUser );
            $stmt->execute();

            return json_encode( $stmt->fetchAll( PDO::FETCH_OBJ ) );
        }

        function addProductCarUser ( $qty )
        {
            if( $this->searchProductCarUser() )
            {
                $this->AddUpdateQtyProductCarUser( $qty );
            } else
            {
                $conexion = new Connection();
                $stmt = $conexion->prepare( "INSERT INTO `cart` (`idProducto`, `idUsuario`, `qty`)"
                                            . "VALUES (:idProducto, :idUsuario, :qty)" );
                $stmt->bindParam( ':idProducto', $this->idProduct );
                $stmt->bindParam( ':idUsuario', $this->idUser );
                $stmt->bindParam( ':qty', $qty );
                $stmt->execute();
            }

            return true;
        }

        function searchProductCarUser ()
        {
            $conexion = new Connection();
            $stmt = $conexion->prepare( "SELECT * FROM cart where idUsuario = :idUsuario "
                                        . "and idProducto = :idProducto" );
            $stmt->bindParam( ':idProducto', $this->idProduct );
            $stmt->bindParam( ':idUsuario', $this->idUser );
            $stmt->execute();
            $retorno = $stmt->fetchAll();
            if( count( $retorno ) > 0 )
            {
                $this->iDqty = $retorno[ 0 ][ 'id' ];
                $this->qty = $retorno[ 0 ][ 'qty' ];

                return true;
            } else
            {
                return false;
            }
        }

        function AddUpdateQtyProductCarUser ( $qty )
        {
            $qty2 = $this->qty + $qty;
            $conexion = new Connection();
            $stmt = $conexion->prepare( "UPDATE `cart` SET `qty` = :qty "
                                        . "WHERE `idUsuario` = :idUsuario and `idProducto` = :idProducto;" );
            $stmt->bindParam( ':idProducto', $this->idProduct );
            $stmt->bindParam( ':idUsuario', $this->idUser );
            $stmt->bindParam( ':qty', $qty2 );
            $stmt->execute();
        }

        function delUpdateQtyProductCarUser ( $qtyy )
        {
            $this->searchProductCarUser();
            $conexion = new Connection();
            if( $qtyy <= $this->qty )
            {
                if( $this->qty <= 1 || $this->qty == $qtyy )
                {
                    $stmt = $conexion->prepare( "DELETE FROM `cart` WHERE `id`= :id" );
                    $stmt->bindParam( ':id', $this->iDqty );
                } else
                {
                    $qty2 = $this->qty - $qtyy;
                    $stmt = $conexion->prepare( "UPDATE `cart` SET `qty` = :qty "
                                                . "WHERE `idUsuario` = :idUsuario and `idProducto` = :idProducto;" );
                    $stmt->bindParam( ':idProducto', $this->idProduct );
                    $stmt->bindParam( ':idUsuario', $this->idUser );
                    $stmt->bindParam( ':qty', $qty2 );
                }
                $stmt->execute();
            }
        }

        public function getCarUser ()
        {
            $conexion = new Connection();
            $stmt =
                $conexion->prepare( "SELECT cart.id, cart.idProducto as idProducto, qty, price, nombre, "
                                    . "ROUND(product.price * cart.qty, 1)"
                                    . " as totalPrice from cart inner join product on cart.idProducto = product.id " .
                                    "where cart.idUsuario = :idUsuario" );
            $stmt->bindParam( ':idUsuario', $this->idUser );
            $stmt->execute();
            $result = $stmt->fetchAll();

            return json_encode( $result );
        }

        function getTotalCart ()
        {
            $conexion = new Connection();
            $stmt = $conexion->prepare( "SELECT sum(ROUND(product.price * cart.qty, 1)) as total "
                                        . "from cart inner join product on cart.idProducto = product.id  "
                                        . "where idUsuario = :idUsuario" );
            $stmt->bindParam( ':idUsuario', $this->idUser );
            $stmt->execute();

            return json_encode( $stmt->fetch() );
        }

        function payCar ( $balance, $cart )
        {
           try{
               $conexion2 = new Connection();
               $total =   $balance - floatval($cart) ;
               $stmtp2 = $conexion2->prepare( "UPDATE `user` SET `balance`= :balance WHERE `id`= :idUser" );
               $stmtp2->bindParam( ':balance', $total );
               $stmtp2->bindParam( ':idUser', $this->idUser );
               $stmtp2->execute();
               $conexion = new Connection();
               $stmt = $conexion->prepare( "DELETE FROM `cart` WHERE `idUsuario`= :id" );
               $stmt->bindParam( ':id', $this->idUser );
               $stmt->execute();
           }catch(Exception $e)
           {
               return $e;
           }

        }
    }