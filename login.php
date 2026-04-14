


<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['user_email'];
$password = $_POST['user_password'];

    if ($email == "admin@gmail.com" && $password == "1234") {
        $_SESSION['user'] = $email;
        header("Location: View/list.php");
    } else {
        $error = "Email ou mot de passe incorrect ❌";
    }
}
?>


<div class="container">

    <div class="left">
        <h1>OrgaSync</h1>
        <p>Gérez vos offres facilement et rapidement</p>
    </div>

    <div class="login-box">

        <form method="POST" autocomplete="off">

            <input type="text" name="fakeuser" style="display:none">
            <input type="password" name="fakepass" style="display:none">

            <input type="email" name="user_email" placeholder="Email">

            <input type="password" name="user_password" placeholder="Mot de passe">

            <button type="submit">Se connecter</button>

        </form>

    </div>

</div>
<style>
    .bubbles {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
    top: 0;
    left: 0;
    z-index: 0;
}

.bubbles span {
    position: absolute;
    bottom: -50px;
    width: 30px;
    height: 30px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    animation: rise 10s infinite;
}

.bubbles span:nth-child(1) { left: 10%; animation-duration: 8s; }
.bubbles span:nth-child(2) { left: 30%; animation-duration: 12s; }
.bubbles span:nth-child(3) { left: 50%; animation-duration: 10s; }
.bubbles span:nth-child(4) { left: 70%; animation-duration: 14s; }
.bubbles span:nth-child(5) { left: 90%; animation-duration: 9s; }

@keyframes rise {
    0% { transform: translateY(0); opacity: 0; }
    50% { opacity: 1; }
    100% { transform: translateY(-1000px); opacity: 0; }
}
    
body {
    background-color: #F1EFE8;
    font-family: Calibri;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
body {
    background: linear-gradient(to right, #0F6E56, #1D9E75);
}
body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.login-box {
    position: relative;
    z-index: 1;
    transition: 0.3s;
}

.login-box:hover {
    transform: scale(1.02);
}
.login-box {
    background: white;
    padding: 30px;
    border-radius: 10px;
    width: 300px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
}

input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    border: 1px solid #ccc;
}

button {
    background-color: #1D9E75;
    color: white;
    border: none;
    padding: 10px;
    width: 100%;
    border-radius: 5px;
    cursor: pointer;
}
button:hover {
    transform: scale(1.05);
    transition: 0.2s;
}
button {
    transition: 0.2s;
}

.container {
    display: flex;
    width: 800px;
    height: 400px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    border-radius: 10px;
    overflow: hidden;
}

.left {
    width: 50%;
    color: white;
    padding: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.left h1 {
    font-size: 40px;
}

.left p {
    margin-top: 10px;
    color: #d0f0e0;
}

.login-box {
    width: 50%;
}

</style>