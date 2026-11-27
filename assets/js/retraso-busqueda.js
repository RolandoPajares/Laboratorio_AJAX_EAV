function debounce(funcion, espera = 400) {
  let temporizador;
  const debounced = function (...argumentos) {
    clearTimeout(temporizador);
    temporizador = setTimeout(() => funcion.apply(this, argumentos), espera);
  };
  debounced.cancelar = () => clearTimeout(temporizador);
  return debounced;
}

function retrasarBusqueda(funcion, espera = 400) {
  return debounce(funcion, espera);
}
