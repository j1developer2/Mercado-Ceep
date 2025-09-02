<?php

	// Error Reporting

	ini_set('display_errors', 'On');
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
	//error_reporting(E_ALL);

	include 'admin/connect.php';

	$sessionUser = '';
	$sessionAvatar = '';
	
	if (isset($_SESSION['user'])) {
		$sessionUser = $_SESSION['user'];
		$sessionAvatar = $_SESSION['avatar'];
	}

	// Routes

	$tpl 	= 'includes/templates/'; // Template Directory
	$lang 	= 'includes/languages/'; // Language Directory
	$func	= 'includes/functions/'; // Functions Directory
	$css 	= 'layout/css/'; // Css Directory
	$js 	= 'layout/js/'; // Js Directory

	// Include The Important Files

	include $func . 'functions.php';
	include $lang . 'english.php';
	include $tpl . 'header.php';
	

	/**
	 * Redimensiona uma imagem para um novo tamanho e a salva.
	 *
	 * @param string $source_path Caminho para a imagem original.
	 * @param string $destination_path Caminho para salvar a nova imagem.
	 * @param int $new_width A nova largura.
	 * @param int $new_height A nova altura.
	 * @return bool Retorna true em caso de sucesso, false em caso de falha.
	 */
function redimensionarImagem($source_path, $destination_path, $new_width, $new_height) {
    $image_info = getimagesize($source_path);
    if (!$image_info) { return false; }

    $width = $image_info[0];
    $height = $image_info[1];
    $type = $image_info[2];

    switch ($type) {
        case IMAGETYPE_JPEG:
            $original_image = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $original_image = imagecreatefrompng($source_path);
            break;
        case IMAGETYPE_GIF:
            $original_image = imagecreatefromgif($source_path);
            break;
        case IMAGETYPE_WEBP: // NOVO: Suporte para criar imagem a partir de WEBP
            $original_image = imagecreatefromwebp($source_path);
            break;
        default:
            return false;
    }
    
    if (!$original_image) { return false; }

    $new_image = imagecreatetruecolor($new_width, $new_height);
    
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_WEBP) {
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
        imagefilledrectangle($new_image, 0, 0, $new_width, $new_height, $transparent);
    }

    imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    $success = false;
    switch ($type) {
        case IMAGETYPE_JPEG:
            $success = imagejpeg($new_image, $destination_path, 90);
            break;
        case IMAGETYPE_PNG:
            $success = imagepng($new_image, $destination_path, 6);
            break;
        case IMAGETYPE_GIF:
            $success = imagegif($new_image, $destination_path);
            break;
        case IMAGETYPE_WEBP: // NOVO: Suporte para salvar a nova imagem como WEBP
            $success = imagewebp($new_image, $destination_path, 85); // Qualidade 85
            break;
    }

    imagedestroy($original_image);
    imagedestroy($new_image);

    return $success;
	}

    function getSingleValue($con, $sql, $parameters){
    $q = $con->prepare($sql);
    $q->execute($parameters);
    return $q->fetchColumn();
}
?>