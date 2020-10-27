<?php
 $bd = mysqli_connect("localhost","root","","tcc");

 if ($bd) {
	 mysqli_set_charset($bd, "utf8");
 } else {
	 echo "Não foi possível conectar o BD <br>";
	 echo "Mensagem de erro: ".mysqli_connect_error() ;
	 exit();
 }
