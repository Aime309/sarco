<?php

namespace SARCOV2\Compartido\Dominio;

use SARCOV2\Compartido\Dominio\Excepciones\TelefonoInvalido;
use Stringable;

final readonly class Telefono implements Stringable {
  private string $codigoPais;
  private string $codigoTelefonica;
  private string $numero;

  function __construct(string $telefono) {
    $telefono = str_replace([' ', '-', '/', '+', '_'], '', $telefono);
    self::asegurarValidez($telefono);

    $this->codigoPais = self::obtenerCodigoPais($telefono);
    $this->codigoTelefonica = self::obtenerCodigoTelefonica($telefono);
    $this->numero = self::obtenerNumero($telefono);
  }

  function __toString(): string {
    $numero = substr($this->numero, 0, 3) . ' ' . substr($this->numero, 3);

    return "+$this->codigoPais $this->codigoTelefonica $numero";
  }

  private static function obtenerCodigoPais(string $telefono): string {
    return $telefono[0] . $telefono[1];
  }

  private static function obtenerCodigoTelefonica(string $telefono): string {
    return $telefono[2] . $telefono[3] . $telefono[4];
  }

  private static function obtenerNumero(string $telefono): string {
    return substr($telefono, 5);
  }

  private static function asegurarValidez(string $telefono): void {
    if (!preg_match('/^\d{12}$/', $telefono)) {
      throw (new TelefonoInvalido($telefono))->debidoA('Debe seguir el formato +XX XXX XXX XXXX');
    }
  }
}
