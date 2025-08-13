# 🧪 Guía de Testing - Pagadito

## ✅ **PROBLEMA SOLUCIONADO**

### **Error Original:**
```
Class "Pagadito" not found
```

### **Solución Aplicada:**
- ✅ Agregado `require_once app_path('Libraries/Pagadito.php');` en PagaditoController
- ✅ Ejecutado `composer dump-autoload -o`
- ✅ Limpiadas las cachés con `php artisan config:clear` y `php artisan route:clear`

---

## 🔄 **RUTAS VERIFICADAS**

```bash
php artisan route:list --name=pagadito
```

**Resultado:**
```
✅ pagos/pagadito/iniciar/{order} .... pagadito.iniciar › PagaditoController@iniciar
✅ pagos/pagadito/retorno ............ pagadito.retorno › PagaditoController@retorno
```

---

## 🧪 **CÓMO PROBAR PAGADITO**

### **Paso 1: Ir al sitio**
```
http://localhost/BodegaMultiplex/public/
```

### **Paso 2: Agregar productos al carrito**
- Navegar por la tienda
- Agregar algunos productos
- Ir al carrito

### **Paso 3: Hacer checkout**
- Iniciar sesión (crear cuenta si es necesario)
- Ir a "Finalizar Compra"
- Llenar formulario de entrega
- **Seleccionar "Pago en Línea"**
- Hacer clic en "Procesar con Pagadito"

### **Paso 4: ¿Qué debería pasar?**
1. ✅ Sistema crea orden con estado 'pendiente'
2. ✅ Redirige a `/pagos/pagadito/iniciar/{order}`
3. ✅ PagaditoController conecta con API
4. ✅ Redirige al checkout seguro de Pagadito
5. ✅ Cliente completa pago en Pagadito
6. ✅ Pagadito redirige de vuelta con resultado
7. ✅ Sistema actualiza estado de orden
8. ✅ Cliente ve confirmación

---

## 🛠️ **SI HAY ERRORES**

### **Error: "No se pudo conectar con Pagadito"**
- Verificar variables de .env:
  ```env
  PAGADITO_UID=tu_uid_aqui
  PAGADITO_WSK=tu_wsk_aqui
  PAGADITO_SANDBOX=true
  ```

### **Error: "Orden no válida"**
- Verificar que el usuario esté autenticado
- Verificar que la orden pertenezca al usuario

### **Error 404 en rutas**
- Ejecutar: `php artisan route:clear`
- Verificar que las rutas estén en web.php

---

## 📊 **LOGS PARA DEBUG**

Todos los eventos se registran en `storage/logs/laravel.log`:

```bash
tail -f storage/logs/laravel.log
```

**Buscar por:**
- `Pagadito connect()`
- `Pagadito exec_trans()`
- `Pagadito retorno`
- `Pagadito estado`

---

## 🌐 **CONFIGURAR URL DE RETORNO**

En el portal de Pagadito, configurar:

**Para desarrollo:**
```
http://localhost/BodegaMultiplex/public/pagos/pagadito/retorno
```

**Para producción:**
```
https://tu-dominio.com/pagos/pagadito/retorno
```

---

## 🎯 **ESTADOS ESPERADOS**

### **Durante el flujo:**
1. **Orden creada:** estado='pendiente', estado_pago='pendiente'
2. **Pago completado:** estado='confirmado', estado_pago='pagado'
3. **Pago fallido:** estado='cancelado', estado_pago='cancelado'

### **En Pagadito Sandbox:**
- Usar tarjetas de prueba
- No se realizan cobros reales
- Perfecto para testing

---

## ✨ **¡TODO LISTO PARA PROBAR!**

El error "Class Pagadito not found" está solucionado. 
Ahora puedes probar el flujo completo de pago con Pagadito. 🚀
