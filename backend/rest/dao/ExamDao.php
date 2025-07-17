<?php

class ExamDao {

    private $conn;

    /**
     * constructor of dao class
     */
    public function __construct(){
        try {
          /** TODO
           * List parameters such as servername, username, password, schema. Make sure to use appropriate port
           */
          $servername='localhost';
          $username='root';
          $password='';
          $schema='webfinal';
          $port=3306;

          /** TODO
           * Create new connection
           */
          $this->conn = new PDO("mysql:host=$servername;dbname=$schema;port=$port",$username,$password);
          $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
          echo "Connected successfully";
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }

    /** TODO
     * Implement DAO method used to get customer information
     */
    public function get_customers(){
      $query="SELECT * from customers";
      $stmt=$this->conn->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** TODO
     * Implement DAO method used to get customer meals
     */
    public function get_customer_meals($customer_id) {
      $query="SELECT f.name as food_name, f.brand as food_brand, m.created_at as meal_date
      FROM foods f
      join meals m on f.id=m.food_id
      WHERE m.customer_id=:customer_id ";
      $stmt=$this->conn->prepare($query);
      $stmt->execute(['customer_id' => $customer_id]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** TODO
     * Implement DAO method used to save customer data
     */
    public function add_customer($data){
      if (!isset($data['status']) || empty($data['status'])) {
        $data['status'] = 'active'; 
    }
      $query="INSERT INTO customers(first_name,last_name,birth_date,status) VALUES (:first_name,:last_name,:birth_date,:status)";
      $stmt = $this->conn->prepare($query);
      $stmt->execute($data);
      $data['id'] = $this->conn->lastInsertId();
      return $data;
    }

    /** TODO
     * Implement DAO method used to get foods report
     */
    public function get_foods_report(){
      $query="SELECT f.name, f.brand, f.image_url,
              COALESCE(n.energy, 0) as energy,
              COALESCE(n.protein, 0) as protein, 
              COALESCE(n.fat, 0) as fat,
              COALESCE(n.fiber, 0) as fiber,
              COALESCE(n.carbs, 0) as carbs
              FROM foods f
              LEFT JOIN nutrients n ON f.id = n.food_id";
      $stmt=$this->conn->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
