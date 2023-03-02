<?php
class conexion {

    protected $servidor;
    protected $usuario;
    protected $password;
    protected $database;
    protected $port;
    public $mysqli;
     
    public function __construct() {

        $this->servidor = getenv('mysql_host');
        $this->usuario = getenv('mysql_user');
        $this->password = getenv('mysql_password');
        $this->database = getenv('mysql_database');
        $this->port = getenv('mysql_port');

    }
 
    public function conectar() {
         
        $this->mysqli = new mysqli($this->servidor, $this->usuario, $this->password, $this->database, $this->port);
        if (mysqli_connect_errno()) {
            echo 'Error en base de datos: '. mysqli_connect_error();
            exit();
        }
         
        $this->mysqli->query("SET NAMES 'utf8'");
        $this->mysqli->query("SET CHARACTER SET utf8");
    }
 
    public function desconectar() {
        mysqli_close($this->mysqli);
    }
}
