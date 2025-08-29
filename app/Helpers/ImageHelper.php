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

        // Primero intentar en storage/app/public/productos/
        $fullPath1 = storage_path('app/public/productos/' . $rutaImagen);
        if (file_exists($fullPath1)) {
            return url('/storage/productos/' . $rutaImagen);
        }

        // Luego intentar en public/storage/productos/
        $fullPath2 = public_path('storage/productos/' . $rutaImagen);
        if (file_exists($fullPath2)) {
            return url('/storage/productos/' . $rutaImagen);
        }

        // Intentar en storage/app/public/ directamente
        $fullPath3 = storage_path('app/public/' . $rutaImagen);
        if (file_exists($fullPath3)) {
            return url('/storage/' . $rutaImagen);
        }

        // Fallback: devolver URL aunque el archivo no exista físicamente
        // Esto permite que las imágenes se muestren si están en la ubicación correcta
        return url('/storage/productos/' . $rutaImagen);
    }

    /**
     * Verificar si una imagen existe físicamente
     */
    public static function imageExists($rutaImagen)
    {
        if (!$rutaImagen) {
            return false;
        }

        // Verificar en múltiples ubicaciones
        $paths = [
            storage_path('app/public/productos/' . $rutaImagen),
            public_path('storage/productos/' . $rutaImagen),
            storage_path('app/public/' . $rutaImagen),
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return true;
            }
        }

        return false;
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