<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Generar URL correcta para imagen de producto
     */
    public static function getProductImageUrl($rutaImagen)
    {
        if (!$rutaImagen) {
            return null;
        }

        // Verificar si el archivo existe físicamente
        $fullPath = storage_path('app/public/' . $rutaImagen);
        if (!file_exists($fullPath)) {
            return null;
        }

        // Generar URL usando las rutas Laravel que creamos
        // Esto funciona tanto en desarrollo como producción
        return url('/storage/' . $rutaImagen);
    }

    /**
     * Verificar si una imagen existe físicamente
     */
    public static function imageExists($rutaImagen)
    {
        if (!$rutaImagen) {
            return false;
        }

        $fullPath = storage_path('app/public/' . $rutaImagen);
        return file_exists($fullPath);
    }

    /**
     * Obtener el tamaño de una imagen
     */
    public static function getImageSize($rutaImagen)
    {
        if (!self::imageExists($rutaImagen)) {
            return null;
        }

        $fullPath = storage_path('app/public/' . $rutaImagen);
        return filesize($fullPath);
    }
} 