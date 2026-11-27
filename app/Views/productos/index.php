<?php require __DIR__ . '/../layouts/header.php'; ?>
<header class="encabezado">
  <div class="contenedor encabezado__contenido">
    <div class="marca"><span class="marca__icono">E</span>
      <div><strong>EAV STORE</strong><small>Catálogo dinámico</small></div>
    </div>
    <span class="insignia">AJAX · MODELO EAV</span>
  </div>
</header>
<main class="contenedor catalogo" id="catalogo" data-base-url="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>">
  <section class="hero">
    <div>
      <p class="eyebrow">DESARROLLO DE APLICACIONES WEB II</p>
      <h1>Explora productos<br><span>con atributos dinámicos.</span></h1>
      <p class="hero__texto">Filtra en tiempo real con datos que nacen directamente del modelo EAV.</p>
    </div>
    <div class="hero__orb" aria-hidden="true">EAV</div>
  </section>
  <section class="toolbar" aria-label="Controles del catálogo">
    <label class="campo campo--busqueda"><span class="sr-only">Buscar producto</span><span class="icono">⌕</span><input id="busqueda" type="search" maxlength="100" placeholder="Buscar por nombre..." autocomplete="off"></label>
    <label class="campo campo--categoria"><span class="sr-only">Categoría</span><select id="categoria" required>
        <option value="">Selecciona una categoría</option>
      </select></label>
    <button id="limpiar-filtros" class="boton boton--secundario" type="button">Limpiar filtros</button>
  </section>
  <div id="mensaje" class="mensaje" role="status" aria-live="polite" hidden></div>
  <div class="catalogo__layout">
    <aside class="panel-filtros" aria-label="Filtros dinámicos">
      <div class="panel-filtros__cabecera">
        <h2>Filtros</h2><span id="contador-filtros" class="contador">0 activos</span>
      </div>
      <div id="filtros" class="filtros">
        <p class="texto-suave">Selecciona una categoría para ver sus filtros.</p>
      </div>
    </aside>
    <section class="resultados" aria-labelledby="titulo-resultados">
      <div class="resultados__cabecera">
        <div>
          <p class="eyebrow">RESULTADOS</p>
          <h2 id="titulo-resultados">Catálogo de productos</h2>
        </div><span id="contador-productos" class="contador">0 productos</span>
      </div>
      <div id="estado-carga" class="estado-carga" hidden><span class="spinner" aria-hidden="true"></span>Cargando datos...</div>
      <div id="sin-resultados" class="estado-vacio" hidden><span class="estado-vacio__icono">∅</span>
        <h3>Sin resultados</h3>
        <p>Prueba con otra búsqueda o limpia algún filtro.</p>
      </div>
      <div class="tabla-contenedor">
        <table id="tabla-productos">
          <thead>
            <tr>
              <th>ID</th>
              <th>Producto</th>
              <th>Categoría</th>
              <th>Atributos</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <nav id="paginacion" class="paginacion" aria-label="Paginación de productos"></nav>
    </section>
  </div>
</main>
<footer class="pie">
  <div class="contenedor"><span>EAV STORE</span><span>PHP · MySQL · Fetch API · MVC</span></div>
</footer>
<script>
  window.EAV_BASE_URL = <?= json_encode($baseUrl, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="<?= htmlspecialchars($baseUrl . '/assets/js/api.js?v=20261127-2', ENT_QUOTES, 'UTF-8') ?>"></script>
<script src="<?= htmlspecialchars($baseUrl . '/assets/js/debounce.js?v=20261127-2', ENT_QUOTES, 'UTF-8') ?>"></script>
<script src="<?= htmlspecialchars($baseUrl . '/assets/js/productos.js?v=20261127-2', ENT_QUOTES, 'UTF-8') ?>"></script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
