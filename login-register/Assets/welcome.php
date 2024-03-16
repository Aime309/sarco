<?php 
//esto permite cerrar sesion de forma segura al destruir la sesion con unset
session_start();
if(isset($_POST['cerrarSesion'])){
    unset($_SESSION['usuario']);
    header('Location: index.php');
}
?>
<!-- se verifica si existe la variable-->
<?php if(isset($_SESSION['usuario'])) { ?>
    <!-- se incluye el archivo de cabecera -->
<?php include 'partials/header.php' ?>
 <?php include('menu.php') ?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
    <div class="container">
        <div class="center">
            <div class="absolute">
                <h1> Bienvenido  Administrador <?php echo '<strong>'.$_SESSION['usuario'].'</strong>'; ?></h1>
            </div>

            </div>
                <div class="row mt-3 justify-content-md-center">
                    <form action="" method="POST">
                    <button type="submit" class="btn btn-primary btn-block" name="cerrarSesion"> Cerrar Sesion </button>
                    </form>
                </div>
                <p> <a href="usuario.php">Registrar Usuario</a></p>
    </div>

<?php include 'partials/footer.php'; ?>
<?php }else{ 
    header('Location: index.php');
 } ?>