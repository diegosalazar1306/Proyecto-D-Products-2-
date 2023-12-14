<?php
//funciones/conecta.php
//nombre de la constante junto a su valor
define("HOST", 'localhost');  //se conecta al localhost de nuestra computadora
define("BD", 'empresa01');  //se conecta a la base de datos creada
define("USER_BD", 'root');  //se conecta al usuario por default 'root'
define("PASS_BD", '');      //el root no tiene contraseña por eso esta vacio

function conecta(){
    $con = new mysqli(HOST,USER_BD, PASS_BD, BD); //la variable $con se conecta a las variables anteriores
    return $con;
}
?>