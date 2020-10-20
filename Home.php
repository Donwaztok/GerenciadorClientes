<?php
include("dbconfig.php");

ob_start();
session_start();

$error = false;
$errorMsg = "";

if (isset($_POST['btn-login'])) {
    $name = trim($_POST['username']);
    $pass = trim($_POST['password']);

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

        $res = mysqli_query($con, "SELECT * FROM Usuario WHERE Login like '$name'");
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
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $cpassword = trim($_POST['confirm-password']);

    //Validar Login
    if (empty($username)) {
        $error = true;
        $errorMsg = "Por favor digite um login. ";
    } else if (strlen($username) < 3) {
        $error = true;
        $errorMsg = "login precisa ter no minimo 3 caracteres. ";
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
        $res = mysqli_query($con, "SELECT * FROM Usuario WHERE Login like '$username'");
        $row = mysqli_fetch_array($res);
        $count = mysqli_num_rows($res);

        if ($count == 0) {
            $query = "INSERT INTO Usuario(Login,Email,Senha) VALUES('$username','$email','$password')";
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
} else if (isset($_POST['btn-add-client'])) {
    $nome = trim($_POST['nome']);
    $date = trim($_POST['date']);
    $cpf = trim($_POST['cpf']);
    $rg = trim($_POST['rg']);
    $tel = trim($_POST['tel']);

    if (empty($nome)) {
        $clientError = true;
        $clientMsg = "Nome é Obrigatório";
    }

    if (!$clientError) {
        $query = "INSERT INTO Cliente(Nome,Nasc,CPF,RG,Telefone) VALUES('$nome','$date','$cpf','$rg','$tel')";
        $res = mysqli_query($con, $query);

        if ($res) {
            $clientError = false;
            $clientMsg = "Cliente cadastrado com Sucesso.";
        } else {
            $clientError = true;
            $clientMsg = "Falha ao cadastrar cliente.";
        }
    }
} else if (isset($_POST['btn-edit-client'])) {
    $id = trim($_POST['id']);
    $nome = trim($_POST['nome']);
    $date = trim($_POST['date']);
    $cpf = trim($_POST['cpf']);
    $rg = trim($_POST['rg']);
    $tel = trim($_POST['tel']);

    if (empty($nome)) {
        $clientError = true;
        $clientMsg = "Nome é Obrigatório";
    }

    if (!$clientError) {
        $query = "UPDATE Cliente SET Nasc='$date',CPF='$cpf',Telefone='$tel',RG='$rg',Nome='$nome' WHERE ID_Cliente=$id;";
        $res = mysqli_query($con, $query);

        if ($res) {
            $clientError = false;
            $clientMsg = "Cliente alterado com Sucesso.";
        } else {
            $clientError = true;
            $clientMsg = "Falha ao alterar cliente.";
        }
    }
} else if (isset($_GET['del'])) {
    $del = $_GET['del'];

    if ($del > 0) {
        $query = "DELETE FROM Cliente WHERE ID_Cliente=$del;";
        $res = mysqli_query($con, $query);

        if ($res) {
            $clientError = false;
            $clientMsg = "Cliente deletado com Sucesso.";
        } else {
            $clientError = true;
            $clientMsg = "Falha ao deletar cliente.";
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

<head>
    <title>Gerenciador de Clientes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/201ed8426e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Images/icon.ico">
</head>

<body>
    <div class="container panel panel-login" style="padding-top: 10px; padding-bottom: 1px;">
        <div class="row">
            <?php
            if (!empty($clientMsg))
                if ($clientError)
                    echo "<div class=\"alert alert-danger\" role=\"alert\">$clientMsg</div>";
                else
                    echo "<div class=\"alert alert-success\" role=\"alert\">$clientMsg</div>";
            ?>
        </div>
        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
            <div class="input-group">
                <h2>Gerenciador de Clientes</h2>
            </div>
            <div class="btn-group" role="group" aria-label="First group">
                <a class="btn btn-primary" href="Cliente.php" role="button">Adicionar Cliente</a>
            </div>
        </div>
        <br>
        <table class="table table-striped table-bordered table-hover table-sm">
            <thead>
                <tr>
                    <th class="th-sm">#</th>
                    <th class="th-sm">Nome</th>
                    <th class="th-sm">Nascimento</th>
                    <th class="th-sm">CPF</th>
                    <th class="th-sm">RG</th>
                    <th class="th-sm">Telefone</th>
                    <th class="th-sm">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($clients_result) > 0) {
                    while ($clients = mysqli_fetch_assoc($clients_result)) { ?>
                        <tr>
                            <td> <?php echo $clients["ID_Cliente"] ?> </td>
                            <td> <?php echo $clients["Nome"]  ?> </td>
                            <?php $date = new DateTime($clients["Nasc"]); ?>
                            <td> <?php echo $date->format('d/m/Y') ?> </td>
                            <td> <?php echo $clients["CPF"]  ?> </td>
                            <td> <?php echo $clients["RG"] ?> </td>
                            <td> <?php echo $clients["Telefone"]  ?> </td>
                            <td>
                                <form id="client-form" action="Cliente.php" method="POST" role="form">
                                    <input type="hidden" class="form-control" name="id" id="id" <?php echo 'value="' . $clients["ID_Cliente"] . '"' ?>>
                                    <button type="submit" name="view-client" id="view-client" class="btn" value="">
                                        <i class="fas fa-eye"></i></button>
                                    <button type="submit" name="edit-client" id="edit-client" class="btn" value="">
                                        <i class="fas fa-edit"></i></button>
                                    <a <?php echo 'href="?del=' . $clients["ID_Cliente"] . '"' ?> class="btn btn-light"><i style="color: Tomato;" class="fas fa-trash"></i></a>
                                </form>
                            </td>
                        </tr> <?php
                            }
                        }
                                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>