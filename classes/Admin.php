<?php 
include_once(realpath(dirname(__DIR__))."/config/connect.php");

class Admin{
	public $conn;
	public $username;
	public $password;
	
	public function __construct(){
		global $conn;
		$this -> conn = $conn;
	}
	public function login(){
		$this -> password = md5($this -> password);
		$sql = "SELECT * FROM admin WHERE username=:username AND password=:password";
		$stmt = $this -> conn -> prepare($sql);
		$stmt -> bindParam(':username', $this -> username);
		$stmt -> bindParam(':password', $this -> password);
		
		try{
			$stmt -> execute();
			$result = $stmt -> fetch(PDO::FETCH_ASSOC);
			if($result == false){
				return 0;
			}
			return 1;
		}catch(PDOException $e){
			echo "ERROR:::::::::::::" . $e -> getMessage();
			return 0;
		}
	}
}
?>

