<?php

use flight\net\Router;
use SARCO\App;
use SARCO\Modelos\Aula;
use Symfony\Component\Uid\UuidV4;

return function (Router $router): void {
  $router->get('/', function (): void {
    $aulas = bd()->query("
      SELECT id, fecha_registro as fechaRegistro, codigo, tipo
      FROM aulas ORDER BY tipo
    ")->fetchAll(PDO::FETCH_CLASS, Aula::class);

    bd()->beginTransaction();

    foreach ($aulas as $aula) {
      try {
        bd()->exec("DELETE FROM aulas WHERE codigo = '{$aula->codigo}'");
        $aula->sePuedeEliminar = true;
      } catch (PDOException) {
        $aula->sePuedeEliminar = false;
      }
    }

    bd()->rollBack();

    App::render('paginas/aulas/listado', compact('aulas'), 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Aulas']);
  });

  $router->post('/', function (): void {
    $aula = App::request()->data->getData();

    try {
      Aula::asegurarValidez($aula);

      $sentencia = bd()->prepare('
        INSERT INTO aulas (id, codigo, tipo)
        VALUES (:id, :codigo, :tipo)
      ');

      $sentencia->execute([
        ':id' => new UuidV4,
        ':codigo' => strtoupper($aula['codigo']),
        ':tipo' => ucfirst($aula['tipo'])
      ]);

      $_SESSION['mensajes.exito'] = 'Aula aperturada exitósamente';
      unset($_SESSION['datos']);
      exit(App::redirect('/aulas'));
    } catch (PDOException $error) {
      if (str_contains($error->getMessage(), 'aulas.tipo')) {
        $_SESSION['mensajes.error'] = 'El tipo debe ser Pequeña o Grande';
      } elseif (str_contains($error->getMessage(), 'aulas.codigo')) {
        $_SESSION['mensajes.error'] = "Aula {$aula['codigo']} ya fue aperturada";
      } else {
        throw $error;
      }
    } catch (InvalidArgumentException $error) {
      $_SESSION['mensajes.error'] = $error->getMessage();
    }

    $_SESSION['datos'] = $aula;
    App::redirect(App::request()->referrer);
  });

  $router->get('/nueva', function (): void {
    App::render('paginas/aulas/nueva', [], 'pagina');
    App::render('plantillas/privada', ['titulo' => 'Aperturar aula']);
  });

  $router->group('/@codigo', function (Router $router): void {
    $router->get('/', function (string $codigo): void {
      $sentencia = bd()->prepare('
        SELECT id, fecha_registro as fechaRegistro, codigo, tipo FROM aulas
        WHERE codigo = ?
      ');

      $sentencia->execute([$codigo]);
      $aula = $sentencia->fetchObject(Aula::class);

      App::render('paginas/aulas/editar', compact('aula'), 'pagina');
      App::render('plantillas/privada', ['titulo' => 'Editar aula']);
    });

    $router->post('/', function (string $id): void {
      $nuevaAula = App::request()->data->getData();

      try {
        Aula::asegurarValidez($nuevaAula);

        $sentencia = bd()->prepare('
          UPDATE aulas SET codigo = :nuevoCodigo, tipo = :nuevoTipo
          WHERE id = :id
        ');

        $sentencia->execute([
          ':nuevoCodigo' => strtoupper($nuevaAula['codigo']),
          ':nuevoTipo' => ucfirst($nuevaAula['tipo']),
          ':id' => $id
        ]);

        $_SESSION['mensajes.exito'] = 'Aula actualizada exitósamente';
        unset($_SESSION['datos']);
        exit(App::redirect('/aulas'));
      } catch (PDOException $error) {
        if (str_contains($error->getMessage(), 'aulas.codigo')) {
          $_SESSION['mensajes.error'] = "El aula {$nuevaAula['codigo']} ya fue aperturada";
        } elseif (str_contains($error->getMessage(), 'aulas.tipo')) {
          $_SESSION['mensajes.error'] = 'El tipo de aula debe ser Pequeña o Grande';
        } else {
          throw $error;
        }
      } catch (InvalidArgumentException $error) {
        $_SESSION['mensajes.error'] = $error->getMessage();
      }

      $_SESSION['datos'] = $nuevaAula;
      App::redirect(App::request()->referrer);
    });

    $router->get('/eliminar', function (string $codigo): void {
      $sentencia = bd()->prepare("DELETE FROM aulas WHERE codigo = ?");

      try {
        $sentencia->execute([$codigo]);
        $_SESSION['mensajes.exito'] = 'Aula eliminada exitósamente';
      } catch (PDOException) {
        $_SESSION['mensajes.error'] = 'Esta sala ya fue aperturada';
      }

      App::redirect('/aulas');
    });
  });
};
