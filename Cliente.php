<?php
include("dbconfig.php");

ob_start();
session_start();

$id = $_POST['id'];

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

if (isset($_POST['view-client']) || isset($_POST['edit-client'])) {
    $view = false;
    if (isset($_POST['view-client']))
        $view = true;
}

if (isset($_POST['btn-add-end'])) {
    $rua = trim($_POST['Rua']);
    $numero = $_POST['Numero'];
    $bairro = trim($_POST['Bairro']);
    $cep = trim($_POST['CEP']);
    $cidade = trim($_POST['Cidade']);
    $estado = trim($_POST['Estado']);

    if (empty($rua)) {
        $endError = true;
        $endMsg = "Rua é Obrigatório";
    }

    if (!$endError) {
        $query = "INSERT INTO Endereco(ID_Cliente,Rua";
        if (!empty($numero))
            $query = $query . ",Numero";
        $query = $query . ",Bairro,CEP,Cidade,Estado) VALUES($id,'$rua'";
        if (!empty($numero))
            $query = $query . ",$numero";
        $query = $query . ",'$bairro','$cep','$cidade','$estado')";
        $res = mysqli_query($con, $query);

        if ($res) {
            $endError = false;
            $endMsg = "Endereço cadastrado com Sucesso.";
        } else {
            $endError = true;
            $endMsg = "Falha ao cadastrar endereço.";
        }
    }
} else if (isset($_POST['btn-edit-end'])) {
    $idEnd = trim($_POST['id-end']);
    $rua = trim($_POST['Rua']);
    $numero = trim($_POST['Numero']);
    $bairro = trim($_POST['Bairro']);
    $cep = trim($_POST['CEP']);
    $cidade = trim($_POST['Cidade']);
    $estado = trim($_POST['Estado']);

    if (empty($rua)) {
        $endError = true;
        $endMsg = "Rua é Obrigatório";
    }

    if (!$endError) {
        $query = "UPDATE Endereco SET Bairro='$bairro',CEP='$cep'";
        if (!empty($numero))
            $query = $query . ",Numero=$numero";
        $query = $query . ",Estado='$estado',Cidade='$cidade',Rua='$rua' WHERE ID_Endereco=$idEnd;";
        $res = mysqli_query($con, $query);

        if ($res) {
            $endError = false;
            $endMsg = "Endereço alterado com Sucesso.";
        } else {
            $endError = true;
            $endMsg = "Falha ao alterar endereço. $query";
        }
    }
} else if (isset($_GET['del'])) {
    $del = $_GET['del'];
    $id = $_GET['id'];

    if ($del > 0) {
        $query = "DELETE FROM Endereco WHERE ID_Endereco=$del;";
        $res = mysqli_query($con, $query);

        if ($res) {
            $endError = false;
            $endMsg = "Endereço deletado com Sucesso.";
        } else {
            $endError = true;
            $endMsg = "Falha ao deletar endereço.";
        }
    }
}

if ($id > 0) {
    $res = mysqli_query($con, "SELECT * FROM Cliente WHERE ID_Cliente = $id");
    $row = mysqli_fetch_array($res);
}

//Endereços
$sql = "SELECT * FROM Endereco WHERE ID_Cliente = $id";
$endereco_result = mysqli_query($con, $sql);

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
    <div class="container panel panel-login" style="padding-top: 10px;">
        <?php
        if (!empty($endMsg))
            if ($endError)
                echo "<div class=\"alert alert-danger\"  style=\"width: 100%;\" role=\"alert\">$endMsg</div>";
            else
                echo "<div class=\"alert alert-success\"  style=\"width: 100%;\" role=\"alert\">$endMsg</div>";
        ?>
        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
            <div class="input-group">
                <h2>Cliente</h2>
            </div>
            <div class="btn-group" role="group" aria-label="First group">
                <a class="btn btn-primary" href="Home.php" role="button">Voltar</a>
            </div>
        </div>
        <br>
        <form id="client-form" action="Home.php" method="post" role="form">
            <?php
            if ($id > 0) echo '<input type="hidden" class="form-control" name="id" id="id" value="' . $id . '">';
            ?>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Nome <color style="color:red">*</color>
                </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome" <?php if ($id > 0) echo 'value="' . $row['Nome'] . '"';
                                                                                                        if ($view) echo ' disabled'; ?>>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-2 col-form-label">Nascimento</label>
                <div class="col-sm-10">
                    <input class="form-control" type="date" name="date" id="date" <?php if ($id > 0) echo 'value="' . $row['Nasc'] . '"';
                                                                                    else echo 'value="2000-01-01"';
                                                                                    if ($view) echo ' disabled'; ?>>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">CPF</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="cpf" id="cpf" placeholder="999.999.999-99" <?php if ($id > 0) echo 'value="' . $row['CPF'] . '"';
                                                                                                                if ($view) echo ' disabled'; ?>>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">RG</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="rg" id="rg" placeholder="99.999.999-9" <?php if ($id > 0) echo 'value="' . $row['RG'] . '"';
                                                                                                            if ($view) echo ' disabled'; ?>>
                </div>
            </div>
            <div class="form-group row">
                <label for="example-tel-input" class="col-sm-2 col-form-label">Telefone</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="tel" id="tel" placeholder="(99) 9 9999-9999" <?php if ($id > 0) echo 'value="' . $row['Telefone'] . '"';
                                                                                                                if ($view) echo ' disabled'; ?>>
                </div>
            </div>
            <?php if ($id > 0 && !$view)
                echo '<input type="submit" name="btn-edit-client" id="btn-edit-client" class="form-control btn btn-success" value="Editar">';
            else if (!$view)
                echo '<input type="submit" name="btn-add-client" id="btn-add-client" class="form-control btn btn-primary" value="Adicionar">';
            ?>
        </form>
        <?php if ($id > 0) { ?>
            <hr>
            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                <div class="input-group">
                    <h2>Endereços</h2>
                </div>
                <div class="btn-group" role="group" aria-label="First group">
                    <form id="client-form" action="Endereco.php" method="POST" role="form">
                        <input type="hidden" class="form-control" name="id-client" id="id-client" <?php echo 'value="' . $id . '"' ?>>
                        <button type="submit" class="btn btn-primary" value="">Adicionar Endereço</button></form>
                </div>
            </div>
            <br>
            <table class="table table-striped table-bordered table-hover table-sm">
                <thead>
                    <tr>
                        <th class="th-sm">#</th>
                        <th class="th-sm">Rua</th>
                        <th class="th-sm">Numero</th>
                        <th class="th-sm">Bairro</th>
                        <th class="th-sm">CEP</th>
                        <th class="th-sm">Cidade</th>
                        <th class="th-sm">Estado</th>
                        <th class="th-sm">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($endereco_result) > 0) {
                        while ($endereco = mysqli_fetch_assoc($endereco_result)) { ?>
                            <tr>
                                <td> <?php echo $endereco["ID_Endereco"] ?> </td>
                                <td> <?php echo $endereco["Rua"]  ?> </td>
                                <td> <?php echo $endereco["Numero"] ?> </td>
                                <td> <?php echo $endereco["Bairro"]  ?> </td>
                                <td> <?php echo $endereco["CEP"] ?> </td>
                                <td> <?php echo $endereco["Cidade"]  ?> </td>
                                <td> <?php echo $endereco["Estado"]  ?> </td>
                                <td>
                                    <form id="client-form row" action="Endereco.php" method="POST" role="form">
                                        <input type="hidden" class="form-control" name="id-client" id="id-client" <?php echo 'value="' . $endereco["ID_Cliente"] . '"' ?>>
                                        <input type="hidden" class="form-control" name="id-end" id="id-end" <?php echo 'value="' . $endereco["ID_Endereco"] . '"' ?>>
                                        <div class="input-group-prepend">
                                            <button type="submit" name="view-end" id="view-end" class="btn" value="">
                                                <i class="fas fa-eye"></i></button>
                                            <button type="submit" name="edit-end" id="edit-end" class="btn" value="">
                                                <i class="fas fa-edit"></i></button>
                                            <a <?php echo 'href="?del=' . $endereco["ID_Endereco"] . "&id=" . $endereco["ID_Cliente"] . '"' ?> class="btn btn-light" role="button"><i style="color: Tomato;" class="fas fa-trash"></i></a>
                                        </div>
                                    </form>
                                </td>
                            </tr> <?php
                                }
                            }
                                    ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>