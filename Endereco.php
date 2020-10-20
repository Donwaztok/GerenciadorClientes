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

<head>
    <title>Gerenciador de Clientes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/201ed8426e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Images/icon.ico">
</head>

<body>
    <div class="container panel panel-login" style="padding-top: 10px; padding-bottom: 1px;">
        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
            <div class="input-group">
                <h2>Endereço</h2>
            </div>
            <div class="btn-group" role="group" aria-label="First group">
                <form id="client-form" action="Cliente.php" method="POST" role="form">
                    <input type="hidden" class="form-control" name="id" id="id" <?php echo 'value="' . $idCli . '"' ?>>
                    <button type="submit" name="view-client" id="view-client" class="btn btn-primary" value="">Voltar</button></form>
            </div>
        </div>
        <br>
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
                <?php if ($idEnd > 0 && !$view)
                    echo '<input type="submit" name="btn-edit-end" id="btn-edit-end" class="form-control btn btn-success" value="Editar">';
                else if (!$view)
                    echo '<input type="submit" name="btn-add-end" id="btn-add-end" class="form-control btn btn-primary" value="Adicionar">';
                ?>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>