<?php
session_start();
include_once '../../Model/config.php';

// 🔒 Si déjà connecté → rediriger
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if (isset($_POST['email'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $db = config::getConnexion();

    $sql = "SELECT * FROM admin WHERE email = :email AND password = :password";
    $req = $db->prepare($sql);

    $req->execute([
        'email' => $email,
        'password' => $password
    ]);

    $user = $req->fetch();

    if ($user) {
        $_SESSION['admin'] = $user['email'];

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #F4F6F9;
        }

        .login-box {
            width: 350px;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center" style="height:100vh;">

<div class="card p-4 shadow login-box">
    <h4 class="mb-3 text-center">Connexion Admin</h4>

    <!-- 🔴 Message erreur -->
    <?php if ($error) { ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php } ?>

    <!-- 🔒 Formulaire -->
    <form method="POST" autocomplete="off">

        <!-- Anti auto-fill -->
        <input type="text" style="display:none">
        <input type="password" style="display:none">

        <input 
            type="email" 
            name="email" 
            class="form-control mb-2" 
            placeholder="Email" 
            required 
            autocomplete="off"
        >

        <input 
            type="password" 
            name="password" 
            class="form-control mb-3" 
            placeholder="Mot de passe" 
            required 
            autocomplete="new-password"
        >

        <button class="btn w-100" style="background:#1D9E75;color:white;">
            Se connecter
        </button>

    </form>
</div>

</body>
</html>