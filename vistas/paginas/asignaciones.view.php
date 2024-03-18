<style>
  .container {
    width: 500px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
  }

  .form-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  label {
    display: block;
    margin-top: 10px;
    width: 48%;
  }

  input[type="text"],
  input[type="number"],
  select {
    width: 100%;
    padding: 5px;
    box-sizing: border-box;
    -moz-appearance: textfield;
    appearance: textfield;
  }

  input[type="submit"],
  button {
    background-color: #4CAF50;
    color: white;
    padding: 10px;
    border: none;
    cursor: pointer;
    margin-top: 20px;
    width: 48%;
  }
</style>
<div class="container">
  <form action="./asignar" method="post">
    <div class="form-container">
      <label for="nombre">Nombre del Niño:</label>
      <input id="nombre" name="nombre" required minlength="3" pattern="[A-ZÁÉÍÓÚÑ]{1}[a-záéíóúñ]{2,}" title="El nombre sólo puede contener letras con la inicial en mayúscula" />

      <label for="edad">Edad:</label>
      <input type="number" id="edad" name="edad" min="1" max="18" required />

      <label for="sala">Sala:</label>
      <select id="sala" name="sala" required>
        <option selected disabled>Selecciona una sala</option>
        <option value="sala1">Sala 1</option>
        <option value="sala2">Sala 2</option>
        <option value="sala3">Sala 3</option>
      </select>

      <label for="periodo">Periodo:</label>
      <input id="periodo" name="periodo" required minlength="9" maxlength="9" pattern="[0-9]{4}-[0-9]{4}" title="El período debe tener el formato 'AAAA-AAAA', ejemplo: 2023-2024" />

      <label for="momento">Momento:</label>
      <input id="momento" name="momento" required pattern="Momento [1-3]{1}" title="Ejemplo: Momento 1, 2 o 3" />

      <button>Registrar</button>
    </div>
  </form>
</div>
