<?php

require 'connect.php';

ig($_SERVER['REQUEST_METHOD'] =='POST'){

    $name=htmlspecialchars($_POST["uname"]);
    $password=htmlspecialchars($_POST["password"]);

    $sql="SELECT * FROM users WHERE name='$name'";
    $result = $mysql->query($sql);

    if($result->num_rows > 0){
        $row= $result->fetch_assoc();

        $passwordhash =$row['password']
        if(password_verify($password,$passwwordhash)){
            echo "welcome" .name;
        }
    }
}