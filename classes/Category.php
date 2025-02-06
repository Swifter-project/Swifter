<?php 
    require_once realpath(dirname(__DIR__))."/config/connect.php";

    class Category{
        private $conn;
        private $table;
        public $name;
        public $feedback;

        public function __construct(){
            global $conn;
            $this -> conn = $conn;
			$this -> table = 'categories';
        }
        public function add(){
            $sql = "INSERT INTO $this->table(`name`) VALUES(:name)";

            $stmt = $this -> conn -> prepare($sql);

            $stmt -> bindParam(':name', $this -> name);

            try{
                $stmt -> execute();
                $this -> feedback = "Category added successfully";
                return true;
            }catch(PDOException $e){
                $this -> feedback = $e -> getMessage();
                return false;
            }
        }
        public function get(){
            $sql = "SELECT * FROM $this->table ORDER BY `name`";

            $stmt = $this -> conn -> prepare($sql);

            try{
                $stmt -> execute();
                $count = 0;
                $result = array();
                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                    $result[$count] = $row['name'];
                    $count++;
                }
                $this -> feedback = $result;
                return true;
            }catch(PDOException $e){
                $this -> feedback = $e -> getMessage();
                return false;
            }
        }
    }
?>