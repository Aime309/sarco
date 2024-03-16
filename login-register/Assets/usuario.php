<?php 
 include 'UserController.php';
include 'Database/Db.php';
include 'UserModel.php';


?>

<?php include 'partials/header.php'?>




<div class="container">
    <div class="row mt-3 justify-content-md-center">
        <div class="col-md-6">
            <form action="" method="POST">
                <h1>Registro de Usuario</h1>
                <div class="form-group">
                <label for="username"><strong>Cedula</strong></label>
                    <input class="form-control" name="cedula" placeholder="Cedula" value="" type="text">
                </div>
                <div class="form-group">
                <label for="username"><strong>Nombres</strong></label>
                    <input class="form-control" name="nombre" placeholder="Nombre" value="" type="text">
                </div>
                <div class="form-group">
                <label for="username"><strong>Apellidos</strong></label>
                    <input class="form-control" name="apellido" placeholder="Apellidos" value="" type="text">
                </div>
                <div class="form-group">
                <label for="username"><strong>Nombre de Usuario</strong></label>
                    <input class="form-control" name="username" placeholder="Nombre de Usuario" value="" type="text">
                </div>
                <div class="form-group">
                    <label for="password"><strong>Clave</strong></label>
                    <input class="form-control" name="password" value="" type="password">
                </div>
                <button type="submit" name="submit" class="btn btn-sm btn-block btn-primary">Registrar</button>
                <p> <a href="welcome.php">Salir </a></p>
            </form>
        </div>
    </div>
</div>


<?php include 'partials/footer.php'?>
