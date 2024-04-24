<?php

foreach (glob(__DIR__ . '/_*.php') as $definicion) {
  require $definicion;
}

Flight::map('notFound', function (): void {
  renderizar('404', '404 ~ No encontrado', 'errores');
});
