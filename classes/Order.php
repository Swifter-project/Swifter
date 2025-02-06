<?php 
    require_once realpath(dirname(__DIR__))."/config/connect.php";

    class Order{
        private $conn;
        private $table;
        public $id;
        public $customer;
        private $cart;
        public $status;
        public $transaction;
        public $merchantRequestId;
        public $product;
        public $date;
        public $amount;
        public $feedback;

        public function __construct(){
            global $conn;
            $this -> conn = $conn;
			$this -> table = 'orders';
        }
        public function add()
        {
            $this->id=uniqid();
            $this-> product = (int)$this->product;
            
            $this->cart=json_encode(array($this->product));
            $this -> date = date('Y-m-d');
            
            $sql = "INSERT INTO $this->table(id, customer, cart, `date`) VALUES(:id, :customer, :cart, :date)";

            $stmt = $this -> conn -> prepare($sql);

            $stmt -> bindParam(':customer', $this -> customer);
            $stmt -> bindParam(':cart', $this -> cart);
            $stmt -> bindParam(':id', $this -> id);
            $stmt -> bindParam(':date', $this->date);

            try{
                $stmt -> execute();
                $this -> feedback = "Order added successfully";
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
        public function getAllId()
        {
            $sql = "SELECT id FROM $this->table";

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
            $this->getById();
            print_r($this->feedback);

            $this->status = isset($this->status) ? $this->status : $this->feedback['status'];
            $this->transaction = isset($this->transaction) ? $this->transaction : $this->feedback['transaction'];
            $this->merchantRequestId = isset($this->merchantRequestId) ? $this->merchantRequestId : $this->feedback['MerchantRequestID'];
            $this -> amount = isset($this -> amount) ? $this -> amount : $this -> feedback['amount'];

            $sql = "UPDATE $this->table SET status=:status, transaction=:transaction, MerchantRequestID=:merchantRequestId, amount=:amount WHERE id=:id";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':status', $this->status);
            $stmt->bindParam(':transaction', $this->transaction);
            $stmt->bindParam(':merchantRequestId', $this->merchantRequestId);
            $stmt->bindParam(':amount', $this->amount);
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
        public function addToCart()
        {
            if(!$this -> getById()){
                $this -> feedback = 'Id not found';
                return false;
            }
            $cart = json_decode($this->feedback['cart'], true);
            $cart[count($cart)] = (int)$this->product;
            sort($cart);
            $this->cart = json_encode($cart);
            $sql = "UPDATE $this->table SET cart=:cart WHERE id=:id";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':cart', $this->cart);
            $stmt->bindParam(':id', $this->id);

            try {
                $stmt->execute();
                $this->feedback = 'Successfully added to cart';
                return true;
            } catch (PDOException $e) {
                $this->feedback = $e->getMessage();
                return false;
            }
        }
        public function removeFromCart(){
            if(!$this -> getById()){
                $this -> feedback = 'Id not found';
                return false;
            }
            $cart = json_decode($this->feedback['cart'], true);
            sort($cart);
            
            $key = array_search($this->product, $cart);
            if($key===false){
                $this->feedback = 'Product not found in cart';
                return false;
            }
            array_splice($cart, $key, 1);

            $this->cart = json_encode($cart);
            $sql = "UPDATE $this->table SET cart=:cart WHERE id=:id";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':cart', $this->cart);
            $stmt->bindParam(':id', $this->id);

            try {
                $stmt->execute();
                $this->feedback = 'Successfully removed from cart';
                return true;
            } catch (PDOException $e) {
                $this->feedback = $e->getMessage();
                return false;
            }
        }
        public function getByCustomer()
        {
            $this->status = 'Complete';
            $sql = "SELECT * FROM $this->table WHERE customer=:customer AND status=:status ORDER BY `date` DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':customer', $this->customer);
            $stmt->bindParam(':status', $this->status);

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
        public function getIncompleteByCustomer(){
            $this -> status = 'incomplete';
            $sql = "SELECT * FROM $this->table WHERE customer=:customer AND status=:status";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':customer', $this->customer);
            $stmt->bindParam(':status', $this->status);

            try {
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result == false) {
                    $this -> feedback = 'No incomplete orders';
                    return false;
                }
                $this->feedback = $result;
                return true;
            } catch (PDOException $e) {
                $this->feedback = $e->getMessage();
                return false;
            }
        }
        public function addTransaction(){
            $this -> status = 'Complete';
            $sql = "UPDATE $this->table SET `transaction`=:transaction, status=:status, amount=:amount WHERE MerchantRequestID=:MerchantRequestID";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':transaction', $this->transaction);
            $stmt->bindParam(':status', $this->status);
            $stmt->bindParam(':amount', $this->amount);
            $stmt->bindParam(':MerchantRequestID', $this->merchantRequestId);

            try {
                $stmt->execute();
                $this->feedback = 'Successfully added transaction';
                return true;
            } catch (PDOException $e) {
                $this->feedback = $e->getMessage();
                return false;
            }
        }
        public function getAllDate(){
            $sql = "SELECT `date` FROM $this->table ORDER BY `date` DESC";

            $stmt = $this->conn->prepare($sql);

            try {
                $stmt->execute();
                $count = 0;
                $result = array();
                while (true) {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($data != '') {
                        $result[$count] = $data['date'];
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
        public function getByDate(){
            $sql = "SELECT * FROM $this->table WHERE `date`=:date ORDER BY `status` DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':date', $this->date);

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
}
?>
