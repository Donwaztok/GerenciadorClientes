<?php
include("dbconfig.php");

ob_start();
session_start();

$error = false;
$errorMsg = "";

if (isset($_POST['btn-login'])) {
    $name = trim($_POST['username']);
    $name = strip_tags($name);
    $name = htmlspecialchars($name);

    $pass = trim($_POST['password']);
    $pass = strip_tags($pass);

    if (empty($name)) {
        $error = true;
        $errorMsg = "Por favor digite um login.";
    }

    if (empty($pass)) {
        $error = true;
        $errorMsg = "Por favor digite uma senha.";
    }

    if (!$error) {
        //sha256
        $password = hash('sha256', $pass);

        $res = mysqli_query($con, "SELECT * FROM Cliente WHERE Login like '$name'");
        $row = mysqli_fetch_array($res);
        $count = mysqli_num_rows($res);

        if ($count == 1 && $row['Senha'] == $password) {;
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $row['id'];
        } else {
            $error = true;
            $errorMsg = "Dados incorretos, Tente novamente...";
        }
    }
} else if (isset($_POST['btn-signup'])) {
    $username = trim($_POST['username']);
    $username = strip_tags($username);
    $username = htmlspecialchars($username);
    $name = trim($_POST['name']);
    $name = strip_tags($name);
    $name = htmlspecialchars($name);

    $email = trim($_POST['email']);
    $email = strip_tags($email);

    $password = trim($_POST['password']);
    $password = strip_tags($password);

    $cpassword = trim($_POST['confirm-password']);
    $cpassword = strip_tags($cpassword);

    //Validar Login
    if (empty($username)) {
        $error = true;
        $errorMsg = "Por favor digite um login. ";
    } else if (strlen($username) < 3) {
        $error = true;
        $errorMsg = "login precisa ter no minimo 3 caracteres. ";
    }
    //Validar Nome
    if (empty($name)) {
        $error = true;
        $errorMsg = "Por favor digite um Nome. ";
    }
    //Validar Email
    if (empty($email)) {
        $error = true;
        $errorMsg = "Por favor digite um Email. ";
    }
    //Validar Senha
    if (empty($password)) {
        $error = true;
        $errorMsg = "Por favor digite uma senha. ";
    } else if (strlen($password) < 4) {
        $error = true;
        $errorMsg = "Senha precisa ter mais de 4 caracteres. ";
    } else if ($password != $cpassword) {
        $error = true;
        $errorMsg = "Senhas não coincidem. ";
    }

    $password = hash('sha256', $password);

    if (!$error) {
        $res = mysqli_query($con, "SELECT * FROM Cliente WHERE Login like '$username'");
        $row = mysqli_fetch_array($res);
        $count = mysqli_num_rows($res);

        if ($count == 0) {
            $query = "INSERT INTO Cliente(Login,Nome,Email,Senha) VALUES('$username','$name','$email','$password')";
            $res = mysqli_query($con, $query);

            if ($res) {
                unset($name);
                unset($pass);
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['name'] = $_POST['username'];
            } else {
                $error = true;
                $errorMsg = "Algo deu errado, tente novamente mais tarde...";
            }
        } else {
            $error = true;
            $errorMsg = "Usuário já Cadastrado";
        }
    }
}


if ($error == true) {
    header("Location: index.php?error=$errorMsg");
    exit;
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

//Clientes
$sql = "SELECT * FROM Cliente";
$clients_result = mysqli_query($con, $sql);

mysqli_close($con);
?>
<!DOCTYPE html>
<html>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

<head>
    <title>Gerenciador de Clientes</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Images/icon.ico">
</head>

<body>
    <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="90%" heigth="400px">
        <thead>
            <tr>
                <th class="th-sm">#</th>
                <th class="th-sm">Username</th>
                <th class="th-sm">Nome</th>
                <th class="th-sm">Email</th>
                <th class="th-sm">Nascimento</th>
                <th class="th-sm">CPF</th>
                <th class="th-sm">RG</th>
                <th class="th-sm">Telefone</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($clients_result) > 0) {
                while ($clients = mysqli_fetch_assoc($clients_result)) {
                    echo "<tr>";
                    echo "  <td>" . $clients["ID_Cliente"] . "</td>";
                    echo "  <td>" . $clients["Login"] . "</td>";
                    echo "  <td>" . $clients["Nome"] . "</td>";
                    echo "  <td>" . $clients["Email"] . "</td>";
                    echo "  <td>" . $clients["Nasc"] . "</td>";
                    echo "  <td>" . $clients["CPF"] . "</td>";
                    echo "  <td>" . $clients["RG"] . "</td>";
                    echo "  <td>" . $clients["Telefone"] . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</body>

</html>
<?php ob_end_flush(); ?>