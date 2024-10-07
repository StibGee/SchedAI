<?php
require_once('../database/config.php');

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim(stripslashes(htmlspecialchars($_POST['username'])));
    $password = trim(stripslashes(htmlspecialchars($_POST['password'])));

    $stmt = $pdo->prepare("SELECT * FROM faculty WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    if(!isset($_SESSION)){
        session_start();
    }
    if (!$user){
        $_SESSION['error']='nouser';
        header('Location: ../index.php');
    }
    if ($user && password_verify($password, $user['password'])) {
        
        $role = $user['role'];
        $collegeid = $user['collegeid'];
        $id = $user['id'];
        $name = $user['fname'];

        $_SESSION['id']=$id;
        $_SESSION['collegeid']=$collegeid;
        $_SESSION['fname']=$name;

        if (!isset($_SESSION['role'])){
            $_SESSION['role']=$role;
        }
       
        
        if ($role=='collegesecretary'){
            header('Location: ../admin/facultyloading.php');
        }else{
            header('Location: ../faculty/profiling.php');
        }
         
    } else {
        $_SESSION['error']='incorrect';
        header('Location: ../index.php');
    }
}
?>
