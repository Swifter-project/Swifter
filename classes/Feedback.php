<?php 
    require_once realpath(dirname(__DIR__))."/config/connect.php";

    class Feedback{
        private $conn;
        private $table;
        public $id;
        public $customer;
        public $message;
        public $date_time;
        public $feedback;

        public function __construct(){
            global $conn;
            $this -> conn = $conn;
			$this -> table = 'feedback';
        }
        public function add()
        {
            $this->date_time = date('Y-m-d H:i:s');
            $sql = "INSERT INTO $this->table(customer, message, date_time) VALUES(:customer, :message, :date_time)";

            $stmt = $this -> conn -> prepare($sql);

            $stmt -> bindParam(':customer', $this -> customer);
            $stmt -> bindParam(':message', $this -> message);
            $stmt -> bindParam(':date_time', $this -> date_time);

            try{
                $stmt -> execute();
                $this -> feedback = "Message added successfully";
                return true;
            }catch(PDOException $e){
                $this -> feedback = $e -> getMessage();
                return false;
            }
        }
        public function delete()
        {
            $sql = "DELETE FROM $this->table WHERE id=:id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $this->id);
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
            $sql = "SELECT * FROM $this->table ORDER BY date_time DESC";

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
        public function getAllId()
        {
            $sql = "SELECT id FROM $this->table ORDER BY date_time DESC";

            $stmt = $this->conn->prepare($sql);

            try {
                $stmt->execute();
                $count = 0;
                $result = array();
                while (true) {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($data != '') {
                        $result[$count] = $data['id'];
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
        public function getById()
        {
            $sql = "SELECT * FROM $this->table WHERE id=:id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $this->id);

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
            if(!$this -> getById()){
                $this -> feedback = 'Id not found';
                return false;
            }

            $this->customer = isset($this->customer) ? $this->customer : $this->feedback['customer'];
            $this->message = isset($this->message) ? $this->message : $this->feedback['message'];

            $sql = "UPDATE $this->table SET customer=:customer, message=:message WHERE id=:id";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':customer', $this->customer);
            $stmt->bindParam(':message', $this->message);
            $stmt->bindParam(':id', $this->id);

            try {
                $stmt->execute();
                $this->feedback = 'Successfully updated';
                return true;
            } catch (PDOException $e) {
                $this->feedback = $e->getMessage();
                return false;
            }
        }
}
?>
