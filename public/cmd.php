<?php
/**
 * Script de Configuración para Producción
 * Bodegas Multiphlex - Sistema de Gestión
 * 
 * Este archivo ejecuta todas las configuraciones necesarias para producción
 * Acceder desde: https://tudominio.com/cmd.php
 */

// Verificar que se está ejecutando desde el servidor web
if (php_sapi_name() === 'cli') {
    die("Este script debe ejecutarse desde el navegador web.\n");
}

// Configurar headers para evitar cache
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Producción - Bodegas Multiphlex</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; text-align: center; margin-bottom: 30px; }
        .step { margin: 20px 0; padding: 15px; border-left: 4px solid #3498db; background: #f8f9fa; }
        .success { border-left-color: #27ae60; background: #d5f4e6; }
        .error { border-left-color: #e74c3c; background: #fadbd8; }
        .warning { border-left-color: #f39c12; background: #fef9e7; }
        pre { background: #2c3e50; color: #ecf0f1; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .btn { background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        .progress { width: 100%; background: #ecf0f1; border-radius: 10px; overflow: hidden; margin: 10px 0; }
        .progress-bar { height: 20px; background: #3498db; transition: width 0.3s; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Configuración de Producción - Bodegas Multiphlex</h1>
        
        <?php
        echo '<div class="step">';
        echo '<h3>📋 Iniciando configuración de producción...</h3>';
        echo '<p>Ejecutando comandos de Laravel para optimizar el sistema.</p>';
        echo '</div>';

        // Función para ejecutar comandos y mostrar resultados
        function ejecutarComando($comando, $descripcion) {
            echo '<div class="step">';
            echo '<h4>🔧 ' . $descripcion . '</h4>';
            echo '<pre>$ ' . $comando . '</pre>';
            
            $output = [];
            $return_var = 0;
            
            // Ejecutar comando
            exec($comando . ' 2>&1', $output, $return_var);
            
            if ($return_var === 0) {
                echo '<div class="success">';
                echo '<strong>✅ Éxito:</strong> Comando ejecutado correctamente<br>';
                echo '</div>';
            } else {
                echo '<div class="error">';
                echo '<strong>❌ Error:</strong> El comando falló<br>';
                echo '</div>';
            }
            
            if (!empty($output)) {
                echo '<pre>' . htmlspecialchars(implode("\n", $output)) . '</pre>';
            }
            
            echo '</div>';
            return $return_var === 0;
        }

        // Lista de comandos a ejecutar
        $comandos = [
            'composer install --optimize-autoloader --no-dev' => 'Instalando dependencias de producción',
            'php artisan config:cache' => 'Cacheando configuración',
            'php artisan route:cache' => 'Cacheando rutas',
            'php artisan view:cache' => 'Cacheando vistas',
            'php artisan event:cache' => 'Cacheando eventos',
            'php artisan migrate --force' => 'Ejecutando migraciones de base de datos',
            'php artisan storage:link' => 'Creando enlace simbólico de storage',
            'php artisan optimize' => 'Optimizando aplicación',
            'php artisan config:clear' => 'Limpiando cache de configuración',
            'php artisan route:clear' => 'Limpiando cache de rutas',
            'php artisan view:clear' => 'Limpiando cache de vistas',
            'php artisan cache:clear' => 'Limpiando cache general',
            'php artisan optimize' => 'Re-optimizando aplicación'
        ];

        $exitosos = 0;
        $total = count($comandos);

        foreach ($comandos as $comando => $descripcion) {
            if (ejecutarComando($comando, $descripcion)) {
                $exitosos++;
            }
            echo '<div class="progress"><div class="progress-bar" style="width: ' . (($exitosos / $total) * 100) . '%"></div></div>';
        }

        // Verificar permisos de directorios
        echo '<div class="step">';
        echo '<h3>🔐 Verificando permisos de directorios</h3>';
        
        $directorios = [
            'storage' => 'storage/',
            'bootstrap/cache' => 'bootstrap/cache/',
            'public/storage' => 'public/storage/'
        ];

        foreach ($directorios as $nombre => $ruta) {
            if (is_writable($ruta)) {
                echo '<div class="success">✅ ' . $nombre . ' es escribible</div>';
            } else {
                echo '<div class="error">❌ ' . $nombre . ' NO es escribible</div>';
            }
        }
        echo '</div>';

        // Verificar archivo .env
        echo '<div class="step">';
        echo '<h3>⚙️ Verificando configuración</h3>';
        
        if (file_exists('.env')) {
            echo '<div class="success">✅ Archivo .env encontrado</div>';
            
            $env_content = file_get_contents('.env');
            $required_vars = ['APP_NAME', 'APP_ENV', 'APP_KEY', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE'];
            
            foreach ($required_vars as $var) {
                if (strpos($env_content, $var . '=') !== false) {
                    echo '<div class="success">✅ ' . $var . ' configurado</div>';
                } else {
                    echo '<div class="error">❌ ' . $var . ' NO configurado</div>';
                }
            }
        } else {
            echo '<div class="error">❌ Archivo .env NO encontrado</div>';
        }
        echo '</div>';

        // Resumen final
        echo '<div class="step ' . ($exitosos === $total ? 'success' : 'warning') . '">';
        echo '<h3>📊 Resumen de Configuración</h3>';
        echo '<p><strong>Comandos ejecutados exitosamente:</strong> ' . $exitosos . ' de ' . $total . '</p>';
        
        if ($exitosos === $total) {
            echo '<div class="success">';
            echo '<h4>🎉 ¡Configuración completada exitosamente!</h4>';
            echo '<p>Tu aplicación Bodegas Multiphlex está lista para producción.</p>';
            echo '<p><strong>Recomendaciones adicionales:</strong></p>';
            echo '<ul>';
            echo '<li>Configurar SSL/HTTPS en tu servidor</li>';
            echo '<li>Configurar backup automático de la base de datos</li>';
            echo '<li>Monitorear logs de la aplicación</li>';
            echo '<li>Configurar firewall y seguridad del servidor</li>';
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<div class="warning">';
            echo '<h4>⚠️ Configuración completada con advertencias</h4>';
            echo '<p>Algunos comandos fallaron. Revisa los errores arriba y ejecuta manualmente los comandos que fallaron.</p>';
            echo '</div>';
        }
        
        echo '<p><strong>Fecha de ejecución:</strong> ' . date('Y-m-d H:i:s') . '</p>';
        echo '</div>';

        // Botón para ejecutar nuevamente
        echo '<div style="text-align: center; margin-top: 30px;">';
        echo '<a href="cmd.php" class="btn">🔄 Ejecutar Nuevamente</a>';
        echo ' <a href="/" class="btn" style="background: #27ae60;">🏠 Ir al Dashboard</a>';
        echo '</div>';
        ?>

        <div class="step">
            <h3>📝 Notas Importantes</h3>
            <ul>
                <li><strong>Seguridad:</strong> Elimina este archivo (cmd.php) después de la configuración inicial</li>
                <li><strong>Backup:</strong> Siempre haz backup de tu base de datos antes de ejecutar migraciones</li>
                <li><strong>Variables de entorno:</strong> Asegúrate de que todas las variables en .env estén correctamente configuradas</li>
                <li><strong>Permisos:</strong> Los directorios storage/ y bootstrap/cache/ deben ser escribibles por el servidor web</li>
            </ul>
        </div>
    </div>
</body>
</html>
