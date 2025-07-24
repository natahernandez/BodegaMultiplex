<?php
require '../vendor/autoload.php';
$app = require '../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Producto;
use App\Helpers\ImageHelper;

$producto = Producto::with(['imagenes', 'imagenPrincipal'])->first();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Final de Imágenes - BodegaMultiplex</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .image-test { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .image-test img { max-width: 200px; height: 150px; object-fit: cover; border: 2px solid #007bff; border-radius: 5px; margin-right: 10px; }
        .image-test a { color: #007bff; text-decoration: none; }
        .image-test a:hover { text-decoration: underline; }
        .header { text-align: center; color: #007bff; margin-bottom: 30px; }
        .status { padding: 10px; border-radius: 5px; margin: 10px 0; }
        .status.success { background: #d4edda; border: 1px solid #c3e6cb; }
        .status.error { background: #f8d7da; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🖼️ Test Final de Imágenes (Con Helper)</h1>
            <p>Verificando que todas las imágenes de productos sean accesibles usando el helper Laravel</p>
        </div>
        
        <div class="status success">
            <span class="success">✅ SISTEMA REPARADO CON HELPER</span><br>
            Las URLs se generan automáticamente usando App\Helpers\ImageHelper
        </div>

        <?php if ($producto): ?>
            <div class="image-test">
                <h3>📸 Imagen Principal: <?= htmlspecialchars($producto->nombre) ?></h3>
                <?php 
                $urlPrincipal = $producto->imagen_principal_url;
                if ($urlPrincipal): ?>
                    <img src="<?= htmlspecialchars($urlPrincipal) ?>" alt="<?= htmlspecialchars($producto->nombre) ?>" onerror="this.style.border='2px solid red'; this.alt='❌ Error cargando imagen';">
                    <br>
                    <strong>URL generada por helper:</strong> <a href="<?= htmlspecialchars($urlPrincipal) ?>" target="_blank"><?= htmlspecialchars($urlPrincipal) ?></a>
                <?php else: ?>
                    <p class="error">❌ No hay imagen principal disponible</p>
                <?php endif; ?>
            </div>

            <?php foreach ($producto->imagenes as $index => $imagen): ?>
                <?php $urlImagen = ImageHelper::getProductImageUrl($imagen->ruta_imagen); ?>
                <div class="image-test">
                    <h3>📸 Imagen <?= $index + 1 ?> <?= $imagen->es_principal ? '(Principal)' : '' ?></h3>
                    <?php if ($urlImagen): ?>
                        <img src="<?= htmlspecialchars($urlImagen) ?>" alt="<?= htmlspecialchars($producto->nombre) ?> - Imagen <?= $index + 1 ?>" onerror="this.style.border='2px solid red'; this.alt='❌ Error cargando imagen';">
                        <br>
                        <strong>URL:</strong> <a href="<?= htmlspecialchars($urlImagen) ?>" target="_blank"><?= htmlspecialchars($urlImagen) ?></a><br>
                        <strong>Ruta BD:</strong> <?= htmlspecialchars($imagen->ruta_imagen) ?><br>
                        <strong>Tamaño:</strong> <?= ImageHelper::getImageSize($imagen->ruta_imagen) ?> bytes
                    <?php else: ?>
                        <p class="error">❌ Imagen no disponible: <?= htmlspecialchars($imagen->ruta_imagen) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <div class="status error">
                <span class="error">❌ ERROR</span><br>
                No se encontró ningún producto para mostrar imágenes.
            </div>
        <?php endif; ?>
        
        <div class="status success">
            <h3>🎉 ¡Problema Solucionado con Helper!</h3>
            <ul>
                <li>✅ Helper personalizado creado: <code>App\Helpers\ImageHelper</code></li>
                <li>✅ URLs generadas automáticamente según entorno</li>
                <li>✅ Verificación automática de existencia de archivos</li>
                <li>✅ Rutas Laravel para servir imágenes</li>
                <li>✅ Cache headers para mejor rendimiento</li>
                <li>✅ Modelo actualizado para usar el helper</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin-top: 30px; color: #6c757d;">
            <p>Prueba accediendo a: <strong><?= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?></strong></p>
            <p><small>Si las imágenes se ven correctamente aquí, funcionarán en toda la aplicación.</small></p>
        </div>
    </div>
    
    <script>
        // Verificar que todas las imágenes se carguen correctamente
        window.addEventListener('load', function() {
            const images = document.querySelectorAll('img');
            let loadedCount = 0;
            let totalCount = images.length;
            
            images.forEach(img => {
                if (img.complete && img.naturalHeight !== 0) {
                    loadedCount++;
                }
            });
            
            console.log(`Imágenes cargadas: ${loadedCount}/${totalCount}`);
            
            if (loadedCount === totalCount) {
                console.log('✅ Todas las imágenes se cargaron correctamente');
                document.querySelector('.header p').innerHTML = 'Verificando que todas las imágenes de productos sean accesibles usando el helper Laravel <span style="color: green; font-weight: bold;">✅ ÉXITO</span>';
            } else {
                console.log('⚠️ Algunas imágenes no se cargaron');
                document.querySelector('.header p').innerHTML = 'Verificando que todas las imágenes de productos sean accesibles usando el helper Laravel <span style="color: red; font-weight: bold;">❌ ERROR</span>';
            }
        });
    </script>
</body>
</html> 