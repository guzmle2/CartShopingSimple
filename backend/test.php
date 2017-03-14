<?php
    /**
     * User: Guzmle2
     * Date: 11/3/2017
     * Time: 4:31 PM
     */
    require_once 'entities/Connection.php';
    require_once 'entities/Product.php';
    require_once 'entities/User.php';
    require_once 'entities/Cart.php';
    use Entity\Cart;
    use Entity\Product;
    use Entity\User;

    session_start();
    if( !isset( $_SESSION[ 'user' ] ) )
    {
        insertUser();
    } else
    {
        $_POST[ 'id' ] = $_SESSION[ 'user' ];
        $_POST[ 'idUser' ] = $_SESSION[ 'user' ];
    }
    if( !isset( $_SESSION[ 'idProducVote' ] ) )
    {
        $_SESSION[ 'idProducVote' ] = array();
    }
    if( isset( $_POST[ 'function' ] ) && !empty( $_POST[ 'function' ] ) )
    {
        $action = $_POST[ 'function' ];
        switch( $action )
        {
            case 'getBalanceUser' :
                getBalanceUser();
                break;
            case 'getAllProduct' :
                getAllProduct();
                break;
            case 'addProductCart' :
                getAddProduct();
                break;
            case 'getCart' :
                getCartUser();
                break;
            case 'delProd' :
                delProduct();
                break;
            case 'cartUser' :
                cartUser();
                break;
            case 'getCarsCount' :
                getCarsCount();
                break;
            case 'getTotalCart' :
                getTotalCart();
                break;
            case 'payCar' :
                payCar();
                break;
            case 'addRanking' :
                addRanking();
                break;
            case 'getMedia' :
                getMedia();
                break;
            case 'getElementVote' :
                getElementVote();
                break;
            case 'resetSession' :
                resetSession();
                break;
        }
    }
    function getBalanceUser ()
    {
        if( isset( $_POST[ 'id' ] ) && !empty( $_POST[ 'id' ] ) )
        {
            $balance = new User( $_POST[ 'id' ] );
            echo $balance->getBalanceUserId();
        }
    }

    function getAllProduct ()
    {
        $products = new Product();
        echo $products->getAllProduct();
    }

    function getAddProduct ()
    {
        if( isset( $_POST[ 'idProduct' ] ) && !empty( $_POST[ 'idProduct' ] )
            && isset( $_POST[ 'idUser' ] )
            && !empty( $_POST[ 'idUser' ] )
            && isset( $_POST[ 'qty' ] )
            && !empty( $_POST[ 'qty' ] )
        )
        {
            $balance = new Cart( $_POST[ 'idUser' ], $_POST[ 'idProduct' ] );
            echo $balance->addProductCarUser( $_POST[ 'qty' ] );
        }
    }

    function getCartUser ()
    {
        if( isset( $_POST[ 'idUser' ] ) && !empty( $_POST[ 'idUser' ] ) )
        {
            $balance = new Cart( $_POST[ 'idUser' ], null );
            echo $balance->getCarUser();
        }
    }

    function getTotalCart ()
    {
        if( isset( $_POST[ 'idUser' ] ) && !empty( $_POST[ 'idUser' ] ) )
        {
            $balance = new Cart( $_POST[ 'idUser' ], null );
            echo $balance->getTotalCart();
        }
    }

    /**
     *
     */
    function delProduct ()
    {
        if( isset( $_POST[ 'idProduct' ] ) && !empty( $_POST[ 'idProduct' ] )
            && isset( $_POST[ 'idUser' ] )
            && !empty( $_POST[ 'idUser' ] )
            && isset( $_POST[ 'qty' ] )
            && !empty( $_POST[ 'qty' ] )
        )
        {
            $cars = new Cart( $_POST[ 'idUser' ], $_POST[ 'idProduct' ] );
            echo $cars->delUpdateQtyProductCarUser( $_POST[ 'qty' ] );
        }
    }

    function cartUser ()
    {
        if( isset( $_POST[ 'idUser' ] ) && !empty( $_POST[ 'idUser' ] ) )
        {
            $balance = new Cart( $_POST[ 'idUser' ], null );
            echo $balance->getCarUser();
        }
    }

    function getCarsCount ()
    {
        if( isset( $_POST[ 'idUser' ] ) && !empty( $_POST[ 'idUser' ] ) )
        {
            $balance = new Cart( $_POST[ 'idUser' ], null );
            echo $balance->getCarsCount();
        }
    }

    function payCar ()
    {
        if( isset( $_POST[ 'idUser' ] ) && !empty( $_POST[ 'idUser' ] )
            && isset( $_POST[ 'cart' ] )
            && !empty( $_POST[ 'cart' ] )
        )
        {
            $balancte = new User( $_POST[ 'idUser' ] );
            $balance = new Cart( $_POST[ 'idUser' ], null );
            $number = $balancte->getBalanceUserId();
            echo $balance->payCar( $number, $_POST[ 'cart' ] );
        }
    }

    function addRanking ()
    {
        if( isset( $_POST[ 'idUser' ] ) && !empty( $_POST[ 'idUser' ] )
            && isset( $_POST[ 'idProduct' ] )
            && !empty( $_POST[ 'idProduct' ] )
            && isset( $_POST[ 'ranking' ] )
            && !empty( $_POST[ 'ranking' ] )
        )
        {
            if( !in_array( $_SESSION[ 'idProducVote' ], $_POST[ 'idProduct' ] ) )
            {
                array_push( $_SESSION[ 'idProducVote' ], $_POST[ 'idProduct' ] );
            };
            $balancte = new Product( $_POST[ 'idUser' ] );
            echo $balancte->addRanking( $_POST[ 'idUser' ], $_POST[ 'idProduct' ], $_POST[ 'ranking' ] );
        }
    }

    function getMedia ()
    {
        $balancte = new Product();
        echo $balancte->getMedia();
    }

    function getElementVote ()
    {
        echo json_encode( $_SESSION[ 'idProducVote' ] );
    }

    function resetSession ()
    {
        session_unset();
        session_destroy();
        insertUser();
    }

    function insertUser ()
    {
        $user = new User( null );
        $_SESSION[ 'user' ] = $user->insertUser();
        $_POST[ 'idUser' ] = $_SESSION[ 'user' ];
    }