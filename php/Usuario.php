<?php

class Usuario{

	/*
	 * Las propiedades privadas son para evitar mostrarlas por error (por ejemplo haciendo un 'echo').
	 * De esta forma evitamos pasar la contrase�a (m�s all� de que vaya a estar encriptada) a frontend.
	 * Para poder acceder a �stos datos vamos a necesitar funciones especiales (para modificarlos o leerlos).
	 * Es importante que las propiedades se llamen igual a como est�n en la base de datos.
	 */

	public $ID;
	public $Nombre_Completo;
	public $DNI;
	public $Conectado;

	public function ObtenerNombreCompleto($nombre, $pdo){
		return $this->Nombre_Completo;
	}

	public function ObtenerConectadoEstado($str, $pdo){
		$params = array(':str' => $str);
		$statement = $pdo->prepare('
			SELECT Conectado
			FROM Usuario
			WHERE (Nombre_Completo = :str OR DNI = :str)
			LIMIT 1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
		return $statement->fetch();
	}

	public function UpdateConectado($str, $nuevoEstado, $pdo){
		$params = array(':str' => $str, ':nuevoEstado' => $nuevoEstado);
		$statement = $pdo->prepare('
			UPDATE Usuario
			SET Conectado = :nuevoEstado
			WHERE (Nombre_Completo = :str OR DNI = :str)
			LIMIT 1');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
	}

	public function ObtenerTodos($pdo){
		$params = array();
		$statement = $pdo->prepare('
			SELECT *
			FROM Usuario');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
		return $statement->fetchAll(); // fetch trae uno s�lo (o debe iterarse). fetchAll trae todos los registros.
	}

	public function BuscarUsuarios($str, $pdo){
		$params = array(':str' => '%'.$str.'%');
		$statement = $pdo->prepare('
			SELECT Nombre_Completo, Conectado
			FROM Usuario
			WHERE Nombre_Completo LIKE :str
			LIMIT 5');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
		return $statement->fetchAll();
	}

	public function Random($pdo){
		$params = array();
		$statement = $pdo->prepare('
			SELECT Nombre_Completo 
			FROM Usuario 
			ORDER BY RAND() 
			LIMIT 0,1;');
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
		return $statement->fetch();
	}

} //Usuario

?>