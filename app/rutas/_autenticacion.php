<?php

use Leaf\{Auth, Router};
use SARCO\Controladores\Web\ControladorDeAutenticacion;

Router::all('/salir', fn () => Auth::logout('./'));

Router::post('/ingresar', [ControladorDeAutenticacion::class, 'procesarCredenciales']);
