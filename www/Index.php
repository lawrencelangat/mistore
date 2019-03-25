<?php

require_once 'Functions.php';

$fun = new Functions();

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $data = json_decode(file_get_contents("php://input"));

  if(isset($data -> operation)){

   $operation = $data -> operation;

   if(!empty($operation)){

      if($operation == 'register'){

         if(isset($data -> student ) && !empty($data -> student) && isset($data -> student -> name)
            && isset($data ->  student-> email) && isset($data -> student -> regno) && isset($data -> student -> phoneno)&& isset($data -> student -> password)){

            $student = $data -> student;
            $name = $student -> name;
			$regno = $student -> regno;
			$phoneno = $student -> phoneno;
            $email = $student -> email;
            $password = $student -> password;

          if ($fun -> isEmailValid($email)) {

            echo $fun -> registerStudent($name, $email,$regno,$phoneno, $password);

          } else {

            echo $fun -> getMsgInvalidEmail();
          }

         } else {

            echo $fun -> getMsgInvalidParam();

         }

      }else if ($operation == 'login') {

        if(isset($data -> student ) && !empty($data -> student) && isset($data -> student -> email) && isset($data -> student -> password)){

          $student = $data -> student;
          $email = $student -> email;
          $password = $student -> password;

          echo $fun -> loginStudent($email, $password);

        } else {

          echo $fun -> getMsgInvalidParam();

        }
      } else if ($operation == 'chgPass') {

        if(isset($data -> student ) && !empty($data -> student) && isset($data -> student -> email) && isset($data -> student -> old_password)
          && isset($data -> student -> new_password)){

          $user = $data -> student;
          $email = $student -> email;
          $old_password = $student -> old_password;
          $new_password = $student -> new_password;

          echo $fun -> changePassword($email, $old_password, $new_password);

        } else {

          echo $fun -> getMsgInvalidParam();

        }
      }
   }else{

      echo $fun -> getMsgParamNotEmpty();

   }
  } else {

      echo $fun -> getMsgInvalidParam();

  }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET'){

  echo "MyStore Login API";

}