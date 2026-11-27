<?php require __DIR__ . '/../layouts/header.php'; ?>
<header class="encabezado">
  <div class="contenedor encabezado__contenido">
    <div class="marca">
      <span class="marca__icono">E</span>
      <div><strong>EAV STORE</strong><small>Catálogo Producto</small></div>
    </div>
    <span class="insignia">AJAX · MODELO EAV</span>
  </div>
</header>

<main class="contenedor catalogo" id="catalogo" data-base-url="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>">
  <section class="hero hero--compact">
    <div>
      <p class="eyebrow">DESARROLLO DE APLICACIONES WEB II</p>
      <h1>Catálogo de productos</h1>
      <p class="hero__texto">Explora y filtra productos mediante atributos dinámicos.</p>
    </div>
  </section>

  <section class="toolbar" aria-label="Controles del catálogo">
    <label class="campo campo--busqueda">
      <span class="sr-only">Buscar producto</span>
      <span class="icono" aria-hidden="true"><i class="bi bi-search"></i></span>
      <input id="busqueda" type="search" maxlength="100" placeholder="Buscar por nombre o categoría..." autocomplete="off">
    </label>
    <label class="campo campo--categoria">
      <span class="sr-only">Categoría</span>
      <select id="categoria" required>
        <option value="">Selecciona una categoría</option>
      </select>
    </label>
    <button id="limpiar-filtros" class="boton boton--secundario" type="button"><i class="bi bi-eraser me-1" aria-hidden="true"></i>Limpiar filtros</button>
  </section>

  <div id="mensaje" class="mensaje" role="status" aria-live="polite" hidden></div>

  <div class="catalogo__layout">
    <aside class="panel-filtros" id="panel-filtros" aria-label="Filtros dinámicos">
      <div class="panel-filtros__cabecera">
        <div>
          <p class="eyebrow">PERSONALIZA</p>
          <h2>Filtros</h2>
        </div>
        <span id="contador-filtros" class="contador">0 activos</span>
      </div>
      <button id="alternar-filtros" class="boton-filtros-movil" type="button" aria-expanded="true" aria-controls="filtros">Ocultar filtros</button>
      <div id="filtros" class="filtros">
        <p class="texto-suave">Selecciona una categoría para ver sus filtros.</p>
      </div>
    </aside>

    <section class="resultados" aria-labelledby="titulo-resultados">
      <div class="resultados__cabecera">
        <div>
          <p class="eyebrow">RESULTADOS</p>
          <h2 id="titulo-resultados">Catálogo de productos</h2>
        </div>
        <span id="contador-productos" class="contador">0 productos</span>
      </div>
      <div id="filtros-activos" class="filtros-activos" aria-label="Filtros activos"></div>
      <div id="estado-carga" class="estado-carga" hidden><span class="spinner" aria-hidden="true"><i class="bi bi-arrow-repeat"></i></span><span>Cargando resultados...</span></div>
      <div id="sin-resultados" class="estado-vacio" hidden><span class="estado-vacio__icono" aria-hidden="true"><i class="bi bi-inbox"></i></span>
        <h3>Sin resultados</h3>
        <p>Prueba con otra búsqueda o limpia algún filtro.</p>
      </div>
      <div class="tabla-contenedor">
        <table id="tabla-productos">
          <caption class="sr-only">Resultados de productos filtrados</caption>
          <thead>
            <tr>
              <th>N.º</th>
              <th>Producto</th>
              <th>Categoría</th>
              <th>Atributos</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="resultados__pie">
        <span id="resumen-resultados">Mostrando 0 productos</span>
        <span id="estado-actualizacion" class="estado-actualizacion" role="status" aria-live="polite"></span>
        <nav id="paginacion" class="paginacion" aria-label="Paginación de productos"></nav>
      </div>
    </section>
  </div>
</main>

<footer class="pie">
  <div class="contenedor pie__contenido"><span>EAV STORE</span><span>PHP · MariaDB · Fetch API · MVC</span></div>
</footer>
<script>
  window.EAV_BASE_URL = <?= json_encode($baseUrl, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="<?= htmlspecialchars($baseUrl . '/assets/js/api.js?v=20261127-3', ENT_QUOTES, 'UTF-8') ?>"></script>
<script src="<?= htmlspecialchars($baseUrl . '/assets/js/retraso-busqueda.js?v=20261127-4', ENT_QUOTES, 'UTF-8') ?>"></script>
<script src="<?= htmlspecialchars($baseUrl . '/assets/js/productos.js?v=20261127-3', ENT_QUOTES, 'UTF-8') ?>"></script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
