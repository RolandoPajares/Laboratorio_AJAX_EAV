(() => {
  const base = window.EAV_BASE_URL || "";
  
  window.eavApi = {
    url(ruta, parametros = {}) {
      const url = new URL(base + ruta, window.location.origin);
      if (parametros instanceof URLSearchParams) {
        parametros.forEach((valor, clave) =>
          url.searchParams.append(clave, valor),
        );
        return url.toString();
      }

      Object.entries(parametros).forEach(([clave, valor]) => {
       
        if (Array.isArray(valor))
          valor.forEach((item) => url.searchParams.append(clave, item));
       
        else if (valor !== undefined && valor !== null && valor !== "")
          url.searchParams.set(clave, valor);
      
      });
      return url.toString();
    },


    async obtener(ruta, parametros = {}, opciones = {}) {
      
      const respuesta = await fetch(this.url(ruta, parametros), {
        headers: { Accept: "application/json" },
        ...opciones,
      });
      
      let cuerpo = null;
      
      try {
        cuerpo = await respuesta.json();
      
      } catch {
        throw new Error("El servidor no devolvió JSON válido.");
      }
      
      if (!respuesta.ok || !cuerpo.success)
      
        throw new Error(cuerpo.message || "La solicitud no pudo completarse.");
      
      return cuerpo.data;
    },
  };
})();
