<?php
/**
 * Manejador de Carga de Archivos - AlquimiaTechnologic
 * Permite subir imágenes de productos como archivos
 */

require_once __DIR__ . '/../config/config.php';

// Verificar si es administrador
if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
    exit();
}

// Verificar token CSRF
if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
    exit();
}

// Configuración de carga
$uploadDir = '../assets/images/products/';
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$maxFileSize = 5 * 1024 * 1024; // 5MB
$maxFiles = 5;

// Crear directorio si no existe
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Función para generar nombre único de archivo
function generateUniqueFileName($originalName, $uploadDir) {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $baseName = pathinfo($originalName, PATHINFO_FILENAME);
    $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $baseName);
    
    $fileName = $baseName . '_' . uniqid() . '.' . $extension;
    $filePath = $uploadDir . $fileName;
    
    // Si el archivo ya existe, generar otro nombre
    $counter = 1;
    while (file_exists($filePath)) {
        $fileName = $baseName . '_' . uniqid() . '_' . $counter . '.' . $extension;
        $filePath = $uploadDir . $fileName;
        $counter++;
    }
    
    return $fileName;
}

// Función para redimensionar imagen
function resizeImage($sourcePath, $destinationPath, $maxWidth = 800, $maxHeight = 600) {
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) return false;
    
    $width = $imageInfo[0];
    $height = $imageInfo[1];
    $type = $imageInfo[2];
    
    // Calcular nuevas dimensiones
    $ratio = min($maxWidth / $width, $maxHeight / $height);
    $newWidth = round($width * $ratio);
    $newHeight = round($height * $ratio);
    
    // Crear imagen
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            $source = imagecreatefromwebp($sourcePath);
            break;
        default:
            return false;
    }
    
    if (!$source) return false;
    
    // Crear nueva imagen
    $destination = imagecreatetruecolor($newWidth, $newHeight);
    
    // Preservar transparencia para PNG y GIF
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
        imagealphablending($destination, false);
        imagesavealpha($destination, true);
        $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
        imagefill($destination, 0, 0, $transparent);
    }
    
    // Redimensionar
    imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
    // Guardar imagen
    $success = false;
    switch ($type) {
        case IMAGETYPE_JPEG:
            $success = imagejpeg($destination, $destinationPath, 85);
            break;
        case IMAGETYPE_PNG:
            $success = imagepng($destination, $destinationPath, 8);
            break;
        case IMAGETYPE_GIF:
            $success = imagegif($destination, $destinationPath);
            break;
        case IMAGETYPE_WEBP:
            $success = imagewebp($destination, $destinationPath, 85);
            break;
    }
    
    // Limpiar memoria
    imagedestroy($source);
    imagedestroy($destination);
    
    return $success;
}

// Procesar carga de archivos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {
    $uploadedFiles = [];
    $errors = [];
    
    // Procesar múltiples archivos
    $files = $_FILES['images'];
    $fileCount = count($files['name']);
    
    if ($fileCount > $maxFiles) {
        echo json_encode(['success' => false, 'message' => "Máximo $maxFiles archivos permitidos"]);
        exit();
    }
    
    for ($i = 0; $i < $fileCount; $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $tmpName = $files['tmp_name'][$i];
            $originalName = $files['name'][$i];
            $fileSize = $files['size'][$i];
            $fileType = $files['type'][$i];
            
            // Validar tipo de archivo
            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = "El archivo '$originalName' no es una imagen válida";
                continue;
            }
            
            // Validar tamaño
            if ($fileSize > $maxFileSize) {
                $errors[] = "El archivo '$originalName' es demasiado grande (máximo 5MB)";
                continue;
            }
            
            // Generar nombre único
            $fileName = generateUniqueFileName($originalName, $uploadDir);
            $filePath = $uploadDir . $fileName;
            
            // Mover archivo
            if (move_uploaded_file($tmpName, $filePath)) {
                // Crear versión redimensionada
                $resizedPath = $uploadDir . 'thumb_' . $fileName;
                if (resizeImage($filePath, $resizedPath, 300, 300)) {
                    $uploadedFiles[] = [
                        'original' => 'assets/images/products/' . $fileName,
                        'thumbnail' => 'assets/images/products/thumb_' . $fileName,
                        'name' => $originalName
                    ];
                } else {
                    // Si no se puede redimensionar, usar la original
                    $uploadedFiles[] = [
                        'original' => 'assets/images/products/' . $fileName,
                        'thumbnail' => 'assets/images/products/' . $fileName,
                        'name' => $originalName
                    ];
                }
            } else {
                $errors[] = "Error al subir el archivo '$originalName'";
            }
        } else {
            $errors[] = "Error en el archivo '" . $files['name'][$i] . "': " . $files['error'][$i];
        }
    }
    
    // Respuesta
    if (!empty($uploadedFiles)) {
        echo json_encode([
            'success' => true,
            'files' => $uploadedFiles,
            'errors' => $errors
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudieron subir archivos',
            'errors' => $errors
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No se recibieron archivos']);
}
?> 