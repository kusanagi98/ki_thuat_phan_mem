<?php
 
 
class DB_Functions {
 
    private $conn;
 
    // constructor
    function __construct() {
        require_once '../include/DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }
 
    // destructor
    function __destruct() {
         
    }
 
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name,$username, $password,$age,$gender,$weight,$height,$trainer_id,$program_id) {
       /** $hash = $this->hashSSHA($password);
        *$encrypted_password = $hash["encrypted"]; // encrypted password
        *$salt = $hash["salt"]; // salt*/
 
        $stmt = $this->conn->prepare("INSERT INTO t_users(name, username,password, age, gender, weight, height, trainer_id,program_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisiiii",$name,$username,$password,$age,$gender,$weight,$height,$trainer_id,$program_id);
        $result = $stmt->execute();
        $stmt->close();
 
        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM t_users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            return $user;
        } else {
            return false;
        }
    }
 
    /**
     * Get user by email and password
     */
    public function getUserByUsernameAndPassword($username, $password) {
 
        $stmt = $this->conn->prepare("SELECT * FROM t_users WHERE username = ?");
 
        $stmt->bind_param("s", $username);
 
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $password1=$user['password'];
            if($password1 == $password){
 
            /**
            *$salt = $user['salt'];
            *$encrypted_password = $user['encrypted_password'];
            *$hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            *if ($encrypted_password == $hash) {
            // user authentication details are correct
            */
                return $user;
            }
            
        } else {
            return NULL;
        }
    }
 
    /**
     * Check user is existed or not
     */
    public function isUserExisted($username) {
        $stmt = $this->conn->prepare("SELECT username from t_users WHERE username = ?");
 
        $stmt->bind_param("s", $username);
 
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }
 
    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 
    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }
 
}
 
?>