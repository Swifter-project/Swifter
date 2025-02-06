<?php 
    require_once realpath(dirname(__DIR__))."/config/connect.php";

    class Customer{
        private $conn;
        private $table;
        public $email;
        public $name;
        public $phone;
        public $password;
        public $feedback;

        public function __construct(){
            global $conn;
            $this -> conn = $conn;
			$this -> table = 'customers';
        }
        public function add(){
            $this->password = md5($this -> password);

            $sql = "INSERT INTO $this->table(email, name, phone, password) VALUES(:email, :name, :phone, :password)";

            $stmt = $this -> conn -> prepare($sql);

            $stmt -> bindParam(':name', $this -> name);
            $stmt -> bindParam(':phone', $this -> phone);
            $stmt -> bindParam(':email', $this -> email);
            $stmt -> bindParam(':password', $this -> password);

            try{
                $stmt -> execute();
                $this -> feedback = "Customer added successfully";
                return true;
            }catch(PDOException $e){
                $this -> feedback = $e -> getMessage();
                return false;
            }
        }
        public function delete()
        {
            $sql = "DELETE FROM $this->table WHERE email=:email";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $this->email);
            try {
                $stmt->execute();
                $this->feedback = 'Successfully deleted';
                return true;
            } catch (PDOException $e) {
                $this->feedback = $e->getMessage();
                return false;
            }
        }
        public function get()
        {
            $sql = "SELECT * FROM $this->table";

            $stmt = $this->conn->prepare($sql);

            try {
                $stmt->execute();
                $count = 0;
                $result = array();
                while (true) {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($data != '') {
                        $result[$count] = $data;
                        $count += 1;
                        $data = '';
                    } else {
                        break;
                    }
                }
                $this->feedback = $result;
                return true;
            } catch (PDOException $e) {
                $this->feedback = $e->getMessage();
                return false;
            }
        }
        public function getAllEmail()
        {
            $sql = "SELECT email FROM $this->table";

            $stmt = $this->conn->prepare($sql);

            try {
                $stmt->execute();
                $count = 0;
                $result = array();
                while (true) {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($data != '') {
                        $result[$count] = $data['email'];
                        $count += 1;
                        $data = '';
                    } else {
                        break;
                    }
                }
                $this->feedback = $result;
                return true;
            } catch (PDOException $e) {
                $this->feedback = $e->getMessage();
                return false;
            }
        }
        public function getByEmail()
        {
            $sql = "SELECT * FROM $this->table WHERE email=:email";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $this->email);

            try {
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result == false) {
                    return false;
                }
                $this->feedback = $result;
                return true;
            } catch (PDOException $e) {
                $this->feedback = $e->getMessage();
                return false;
            }
        }
        public function update()
        {
            if(!$this -> getByEmail()){
                $this -> feedback = 'Email not found';
                return false;
            }

            $this->name = isset($this->name) ? $this->name : $this->feedback['name'];
            $this->phone = isset($this->phone) ? $this->phone : $this->feedback['phone'];
            $this->password = isset($this->password) ? md5($this->password) : $this->feedback['password'];

            $sql = "UPDATE $this->table SET name=:name, phone=:phone, password=:password WHERE email=:email";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':phone', $this->phone);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);

            try {
                $stmt->execute();
                $this->feedback = 'Successfully updated';
                return true;
            } catch (PDOException $e) {
                $this->feedback = $e->getMessage();
                return false;
            }
        }
        public function login(){
            $this->password = md5($this -> password);
            $sql = "SELECT * FROM $this->table WHERE email=:email AND password=:password";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':password', $this->password);

            try {
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result == false) {
                    $this->feedback = 'Email or password is incorrect';
                    return false;
                }
                $this->feedback = $result;
                return true;
            } catch (PDOException $e) {
                $this->feedback = $e->getMessage();
                return false;
            }
        }
}
?>
