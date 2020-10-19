<?php
include("dbconfig.php");

ob_start();
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

$idCli = $_POST['id-client'];

if (isset($_POST['view-end']) || isset($_POST['edit-end'])) {
    $view = false;
    if (isset($_POST['view-end']))
        $view = true;
    $idEnd = $_POST['id-end'];

    if ($idEnd > 0) {
        $res = mysqli_query($con, "SELECT * FROM Endereco WHERE ID_Endereco = $idEnd");
        $row = mysqli_fetch_array($res);
    }
}
mysqli_close($con);
?>
<!DOCTYPE html>
<html>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/201ed8426e.js" crossorigin="anonymous"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

<head>
    <title>Gerenciador de Clientes</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Images/icon.ico">
</head>

<body>
    <div class="container">
        <form id="client-form" action="Cliente.php" method="POST" role="form">
            <input type="hidden" class="form-control" name="id" id="id" <?php echo 'value="' . $idCli . '"' ?>>
            <button type="submit" name="view-client" id="view-client" class="btn btn-login" value="">Voltar</button></form>
        <div class="row">
            <form id="client-form" action="Cliente.php" method="post" role="form">
                <?php
                if ($idEnd > 0) echo '<input type="hidden" class="form-control" name="id-end" id="id-end" value="' . $idEnd . '">';
                ?>
                <input type="hidden" class="form-control" name="id" id="id" <?php echo 'value="' . $idCli . '"' ?>>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Rua <color style="color:red">*</color>
                    </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="Rua" id="Rua" placeholder="Rua" <?php if ($idEnd > 0) echo 'value="' . $row['Rua'] . '"';
                                                                                                        if ($view) echo ' disabled'; ?>>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Numero</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="number" name="Numero" id="Numero" <?php if ($idEnd > 0) echo 'value="' . $row['Numero'] . '"';
                                                                                            if ($view) echo ' disabled'; ?>>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Bairro</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="Bairro" id="Bairro" <?php if ($idEnd > 0) echo 'value="' . $row['Bairro'] . '"';
                                                                                            if ($view) echo ' disabled'; ?>>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">CEP</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="CEP" id="CEP" <?php if ($idEnd > 0) echo 'value="' . $row['CEP'] . '"';
                                                                                    if ($view) echo ' disabled'; ?>>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-tel-input" class="col-sm-2 col-form-label">Cidade</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" name="Cidade" id="Cidade" <?php if ($idEnd > 0) echo 'value="' . $row['Cidade'] . '"';
                                                                                            if ($view) echo ' disabled'; ?>>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-tel-input" class="col-sm-2 col-form-label">Estado</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" name="Estado" id="Estado" <?php if ($idEnd > 0) echo 'value="' . $row['Estado'] . '"';
                                                                                            if ($view) echo ' disabled'; ?>>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <?php if ($idEnd > 0 && !$view)
                                echo '<input type="submit" name="btn-edit-end" id="btn-edit-end" class="form-control btn btn-login" value="Editar">';
                            else if (!$view)
                                echo '<input type="submit" name="btn-add-end" id="btn-add-end" class="form-control btn btn-login" value="Adicionar">';
                            ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>