<?php
    namespace Entity;

    use PDO;

    /**
     * User: Guzmle2
     * Date: 11/3/2017
     * Time: 4:49 PM
     */
    class Connection extends PDO
    {

        private $typeBD = 'mysql';
        private $host = 'localhost';
        private $nameBD = 'guzmle2';
        private $user = 'guzmle2';
        private $pass = '3squ3l3t0';

        public function __construct ()
        {
            //Sobreescribo el método constructor de la clase PDO.
            try
            {
                parent::__construct( $this->typeBD . ':host=' . $this->host . ';dbname=' .
                                     $this->nameBD, $this->user, $this->pass );
            }
            catch( PDOException $e )
            {
                echo 'Error to connect BD ' . $e->getMessage();
                exit;
            }
        }
    }