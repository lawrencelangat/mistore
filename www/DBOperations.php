<?php

class DBOperations{

    private $host = '127.0.0.1';
    private $user = 'root';
    private $db = 'mistore';
    private $pass = '';
    private $conn;

public function __construct() {

   $this -> conn = new PDO("mysql:host=".$this -> host.";dbname=".$this -> db, $this -> user, $this -> pass);

}

 public function insertData($name,$email,$regno,$phoneno,$password){

   $unique_id = uniqid('', true);
    $hash = $this->getHash($password);
    $encrypted_password = $hash["encrypted"];
   $salt = $hash["salt"];

   $sql = 'INSERT INTO students SET unique_id =:unique_id,name =:name,
    email =:email,regno =:regno,phoneno =:phoneno,encrypted_password =:encrypted_password,salt =:salt,created_at = NOW()';

   $query = $this ->conn ->prepare($sql);
   $query->execute(array('unique_id' => $unique_id, ':name' => $name, ':email' => $email,':regno'=>$regno,':phoneno'=>$phoneno,
     ':encrypted_password' => $encrypted_password, ':salt' => $salt));

    if ($query) {

        return true;

    } else {

        return false;

    }
 }

 public function checkLogin($email, $password) {

    $sql = 'SELECT * FROM students WHERE email = :email';
    $query = $this -> conn -> prepare($sql);
    $query -> execute(array(':email' => $email));
    $data = $query -> fetchObject();
    $salt = $data -> salt;
    $db_encrypted_password = $data -> encrypted_password;

    if ($this -> verifyHash($password.$salt,$db_encrypted_password) ) {

        $student["name"] = $data -> name;
        $student["email"] = $data -> email;
        $student["unique_id"] = $data -> unique_id;
        return $student;

    } else {

        return false;
    }
 }

 public function changePassword($email, $password){

    $hash = $this -> getHash($password);
    $encrypted_password = $hash["encrypted"];
    $salt = $hash["salt"];

    $sql = 'UPDATE students SET encrypted_password = :encrypted_password, salt = :salt WHERE email = :email';
    $query = $this -> conn -> prepare($sql);
    $query -> execute(array(':email' => $email, ':encrypted_password' => $encrypted_password, ':salt' => $salt));

    if ($query) {

        return true;

    } else {

        return false;

    }
 }

 public function checkStudentExist($email){

    $sql = 'SELECT COUNT(*) from students WHERE email =:email';
    $query = $this -> conn -> prepare($sql);
    $query -> execute(array('email' => $email));

    if($query){

        $row_count = $query -> fetchColumn();

        if ($row_count == 0){

            return false;

        } else {

            return true;

        }
    } else {

        return false;
    }
 }

 public function getHash($password) {

     $salt = sha1(rand());
     $salt = substr($salt, 0, 10);
     $encrypted = password_hash($password.$salt, PASSWORD_DEFAULT);
     $hash = array("salt" => $salt, "encrypted" => $encrypted);

     return $hash;

}

public function verifyHash($password, $hash) {

    return password_verify ($password, $hash);
}
}