<?php 
    require_once realpath(dirname(__DIR__))."/config/connect.php";

    class Product{
        private $conn;
        private $table;
        public $id;
        public $swahili_name;
        public $english_name;
        public $category;
        public $description;
        public $price;
        public $image;
        private $img;
        public $feedback;

        public function __construct(){
            global $conn;
            $this -> conn = $conn;
			$this -> table = 'products';
            $this -> img = 'food.png';
        }
        public function add(){
            $sql = "INSERT INTO $this->table(swahili_name, english_name, category, `description`, price, `image`) VALUES(:swahili_name, :english_name, :category, :description, :price, :image)";

            $stmt = $this -> conn -> prepare($sql);

            $stmt -> bindParam(':swahili_name', $this -> swahili_name);
            $stmt -> bindParam(':english_name', $this -> english_name);
            $stmt -> bindParam(':description', $this -> description);
            $stmt -> bindParam(':category', $this -> category);
            $stmt -> bindParam(':price', $this->price);
            $stmt -> bindParam(':image', $this -> image);

            try{
                $stmt -> execute();
                $this -> feedback = "Product added successfully";
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
                        $data['image'] = isset($data['image']) ? $data['image'] : $this->img;

                        if (strlen($data['description']) > 40) {
                            $data['description'] = substr($data['description'], 0, 40) . "...";
                        }
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
        public function getFilteredBySwahili()
        {
            $this->swahili_name = '%' . $this->swahili_name . '%';

            $sql = "SELECT * FROM $this->table WHERE swahili_name LIKE :swahili_name";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':swahili_name', $this->swahili_name);

            try {
                $stmt->execute();
                $count = 0;
                $result = array();
                while (true) {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($data != '') {
                        $data['image'] = isset($data['image']) ? $data['image'] : $this->img;
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
        public function getFilteredByEnglish()
        {
            $this->english_name = '%' . $this->english_name . '%';

            $sql = "SELECT * FROM $this->table WHERE english_name LIKE :english_name";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':english_name', $this->english_name);

            try {
                $stmt->execute();
                $count = 0;
                $result = array();
                while (true) {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($data != '') {
                        $data['image'] = isset($data['image']) ? $data['image'] : $this->img;
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
                $result['image'] = isset($result['image']) ? $result['image'] : $this->img;
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

            $this->swahili_name = isset($this->swahili_name) ? $this->swahili_name : $this->feedback['swahili_name'];
            $this->english_name = isset($this->english_name) ? $this->english_name : $this->feedback['english_name'];
            $this->category = isset($this->category) ? $this->category : $this->feedback['category'];
            $this->description = isset($this->description) ? $this->description : $this->feedback['description'];
            $this->price = isset($this->price) ? $this->price : $this->feedback['price'];
            $this->image = isset($this->image) ? $this->image : $this->feedback['image'];


            $sql = "UPDATE $this->table SET swahili_name=:swahili_name, english_name=:english_name, category=:category, `description`=:description, price=:price, image=:image WHERE id=:id";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':swahili_name', $this->swahili_name);
            $stmt->bindParam(':english_name', $this->english_name);
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':image', $this->image);


            try {
                $stmt->execute();
                $this->feedback = 'Successfully updated';
                return true;
            } catch (PDOException $e) {
                $this->feedback = $e->getMessage();
                return false;
            }
        }
        public function getByCategory(){
            $sql = "SELECT * FROM $this->table WHERE category=:category";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':category', $this->category);

            try {
                $stmt->execute();
                $count = 0;
                $result = array();
                while (true) {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($data != '') {
                        $data['image'] = isset($data['image']) ? $data['image'] : $this->img;
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