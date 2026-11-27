(() => {
  let categoriaActual = "";
  let filtrosActuales = {};
  let paginaActual = 1;
  let filtrosController;
  let productosController;
  let versionSolicitud = 0;
  const $ = (id) => document.getElementById(id);
  const elementos = {
    categoria: $("categoria"),
    busqueda: $("busqueda"),
    filtros: $("filtros"),
    tabla: $("tabla-productos").querySelector("tbody"),
    paginacion: $("paginacion"),
    mensaje: $("mensaje"),
    carga: $("estado-carga"),
    vacio: $("sin-resultados"),
    contadorProductos: $("contador-productos"),
    contadorFiltros: $("contador-filtros"),
  };

  function parametrosFiltros() {
    const params = new URLSearchParams();
    Object.entries(filtrosActuales).forEach(([id, valores]) =>
      valores.forEach((valor) => params.append("filtros[" + id + "][]", valor)),
    );
    return params;
  }
  function mostrarCargando(activo) {
    elementos.carga.hidden = !activo;
  }
  function mostrarError(error) {
    elementos.mensaje.textContent =
      error.message || "Ocurrió un error de conexión.";
    elementos.mensaje.className = "mensaje mensaje--error";
    elementos.mensaje.hidden = false;
  }
  function limpiarMensaje() {
    elementos.mensaje.hidden = true;
    elementos.mensaje.textContent = "";
  }
  function mostrarSinResultados(activo) {
    elementos.vacio.hidden = !activo;
  }
  function obtenerFiltrosSeleccionados() {
    return structuredClone(filtrosActuales);
  }
  function contarFiltros() {
    elementos.contadorFiltros.textContent =
      Object.values(filtrosActuales).reduce(
        (total, valores) => total + valores.length,
        0,
      ) + " activos";
  }

  async function cargarCategorias() {
    try {
      const categorias = await eavApi.obtener("/api/categorias");
      categorias.forEach((categoria) => {
        const option = document.createElement("option");
        option.value = categoria.id;
        option.textContent = categoria.nombre;
        elementos.categoria.append(option);
      });
      if (categorias.length) {
        elementos.categoria.value = categorias[0].id;
        await cambiarCategoria();
      }
    } catch (error) {
      mostrarError(error);
    }
  }
  async function cargarFiltros() {
    if (!categoriaActual) return;
    if (filtrosController) filtrosController.abort();
    filtrosController = new AbortController();
    try {
      const data = await eavApi.obtener(
        "/api/filtros",
        { categoria_id: categoriaActual },
        { signal: filtrosController.signal },
      );
      renderizarFiltros(data);
    } catch (error) {
      if (error.name !== "AbortError") mostrarError(error);
    }
  }
  async function cargarProductos() {
    if (!categoriaActual) return;
    if (productosController) productosController.abort();
    productosController = new AbortController();
    const solicitud = ++versionSolicitud;
    let tiempoAgotado = false;
    const temporizador = setTimeout(() => {
      tiempoAgotado = true;
      productosController.abort();
    }, 20000);
    mostrarCargando(true);
    limpiarMensaje();
    try {
      const params = parametrosFiltros();
      params.set("categoria_id", categoriaActual);
      params.set("pagina", paginaActual);
      params.set("limite", 12);
      if (elementos.busqueda.value.trim())
        params.set("q", elementos.busqueda.value.trim());
      const data = await eavApi.obtener("/api/productos", params, {
        signal: productosController.signal,
      });
      if (solicitud !== versionSolicitud) return;
      renderizarProductos(data);
    } catch (error) {
      if (
        solicitud === versionSolicitud &&
        (error.name !== "AbortError" || tiempoAgotado)
      )
        mostrarError(
          tiempoAgotado
            ? new Error(
                "La solicitud tardó más de 20 segundos. Verifica Apache, MariaDB y la conexión.",
              )
            : error,
        );
    } finally {
      clearTimeout(temporizador);
      if (solicitud === versionSolicitud) mostrarCargando(false);
    }
  }
  function renderizarFiltros(filtros) {
    elementos.filtros.replaceChildren();
    if (!filtros.length) {
      const p = document.createElement("p");
      p.className = "texto-suave";
      p.textContent = "No hay filtros disponibles.";
      elementos.filtros.append(p);
      return;
    }
    filtros.forEach((filtro) => {
      const grupo = document.createElement("fieldset");
      grupo.className = "grupo-filtro";
      const legend = document.createElement("legend");
      legend.textContent = filtro.nombre;
      grupo.append(legend);
      filtro.valores.forEach((opcion) => {
        const label = document.createElement("label");
        label.className = "opcion-filtro";
        const input = document.createElement("input");
        input.type = "checkbox";
        input.value = opcion.valor;
        input.dataset.atributoId = filtro.id;
        input.checked = (filtrosActuales[filtro.id] || []).includes(
          opcion.valor,
        );
        input.disabled = opcion.conteo === 0 && !input.checked;
        input.addEventListener("change", aplicarFiltros);
        const texto = document.createElement("span");
        texto.textContent = opcion.valor;
        const conteo = document.createElement("small");
        conteo.textContent = String(opcion.conteo);
        label.append(input, texto, conteo);
        grupo.append(label);
      });
      elementos.filtros.append(grupo);
    });
  }
  function renderizarProductos(data) {
    elementos.tabla.replaceChildren();
    elementos.contadorProductos.textContent = data.total + " productos";
    mostrarSinResultados(data.productos.length === 0);
    data.productos.forEach((producto) => {
      const fila = document.createElement("tr");
      [producto.id, producto.nombre, producto.categoria].forEach((valor) => {
        const celda = document.createElement("td");
        celda.textContent = String(valor);
        fila.append(celda);
      });
      const atributos = document.createElement("td");
      atributos.className = "atributos";
      Object.entries(producto.atributos || {}).forEach(([nombre, valores]) => {
        const etiqueta = document.createElement("span");
        etiqueta.className = "etiqueta";
        etiqueta.textContent = nombre + ": " + valores.join(", ");
        atributos.append(etiqueta);
      });
      fila.append(atributos);
      elementos.tabla.append(fila);
    });
    renderizarPaginacion(data);
  }
  function renderizarPaginacion(data) {
    elementos.paginacion.replaceChildren();
    if (data.total_paginas <= 1) return;
    for (let pagina = 1; pagina <= data.total_paginas; pagina += 1) {
      const boton = document.createElement("button");
      boton.type = "button";
      boton.className =
        "pagina" + (pagina === data.pagina ? " pagina--activa" : "");
      boton.textContent = String(pagina);
      boton.setAttribute("aria-label", "Página " + pagina);
      boton.addEventListener("click", () => {
        paginaActual = pagina;
        cargarProductos();
      });
      elementos.paginacion.append(boton);
    }
  }
  function aplicarFiltros() {
    filtrosActuales = {};
    elementos.filtros
      .querySelectorAll('input[type="checkbox"]:checked')
      .forEach((input) => {
        (filtrosActuales[input.dataset.atributoId] ||= []).push(input.value);
      });
    paginaActual = 1;
    contarFiltros();
    cargarProductos();
    cargarFiltros();
  }
  function limpiarFiltros() {
    if (busquedaDebounced.cancelar) busquedaDebounced.cancelar();
    elementos.busqueda.value = "";
    filtrosActuales = {};
    paginaActual = 1;
    contarFiltros();
    cargarFiltros();
    cargarProductos();
  }
  async function cambiarCategoria() {
    busquedaDebounced.cancelar();
    categoriaActual = elementos.categoria.value;
    filtrosActuales = {};
    paginaActual = 1;
    contarFiltros();
    if (!categoriaActual) {
      elementos.filtros.replaceChildren();
      elementos.tabla.replaceChildren();
      return;
    }
    await Promise.all([cargarFiltros(), cargarProductos()]);
  }
  const busquedaDebounced = debounce(() => {
    paginaActual = 1;
    cargarProductos();
    cargarFiltros();
  }, 400);
  elementos.categoria.addEventListener("change", cambiarCategoria);
  elementos.busqueda.addEventListener("input", busquedaDebounced);
  $("limpiar-filtros").addEventListener("click", limpiarFiltros);
  window.inicializarCatalogo = cargarCategorias;
  window.cargarCategorias = cargarCategorias;
  window.cargarFiltros = cargarFiltros;
  window.cargarProductos = cargarProductos;
  window.aplicarFiltros = aplicarFiltros;
  window.limpiarFiltros = limpiarFiltros;
  window.renderizarFiltros = renderizarFiltros;
  window.renderizarProductos = renderizarProductos;
  window.obtenerFiltrosSeleccionados = obtenerFiltrosSeleccionados;
  window.mostrarCargando = mostrarCargando;
  window.mostrarError = mostrarError;
  window.mostrarSinResultados = mostrarSinResultados;
  document.addEventListener("DOMContentLoaded", inicializarCatalogo);
})();
