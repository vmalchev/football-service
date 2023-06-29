<?php
namespace Sportal\FootballApi\Asset;

class AssetManager
{

    private $storage;

    private $sizes;

    private $formats;

    private $saveMethods;

    public function __construct(AssetStorageInterface $storage, array $sizes, array $formats)
    {
        $this->storage = $storage;
        $this->sizes = $sizes;
        $this->formats = $formats;
        $this->saveMethods = [
            'jpeg' => 'imagejpeg',
            'png' => function ($im) {
                imagepng($im, null, 9);
            }
        ];
    }

    public function saveImage(Assetable $model, $base64, $type)
    {
        $className = get_class($model);
        $ext = isset($this->formats[$className][$type]) ? $this->formats[$className][$type] : "jpeg";
        $fileName = $model->hasAsset($type) ? $model->getAssetFilename($type) : $model->generateAssetName($type) . "." .
             $ext;
        $size = isset($this->sizes[$className][$type]) ? $this->sizes[$className][$type] : [];
        $imageBinary = $this->getImageBinary($base64, $ext, $size);
        
        if ($this->storage->saveImage($className, $fileName, $imageBinary, $type) === false) {
            throw new \RuntimeException("Could not save $type for: " . $className . " with name: " . $fileName);
        }
        $model->setAssetFilename($type, $fileName);
        return $fileName;
    }

    public static function resizeImage($im, $newWidth, $newHeight)
    {
        // Get current dimension
        $width = imagesx($im);
        $height = imagesy($im);
        
        // Resample
        $image_p = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($image_p, false);
        imagesavealpha($image_p, true);
        if (imagecopyresampled($image_p, $im, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height)) {
            return $image_p;
        }
        return null;
    }

    private function getImageBinary($base64, $ext, array $size)
    {
        $image = base64_decode($base64);
        if (empty($image)) {
            throw new \InvalidArgumentException("Base64 image is empty or invalid");
        }
        
        $im = imagecreatefromstring($image);
        imagealphablending($im, true);
        
        if (! empty($size)) {
            $im = static::resizeImage($im, $size['width'], $size['height']);
        }
        
        if ($im === null) {
            throw new \RuntimeException("Unable to resize image");
        }
        
        ob_start();
        call_user_func_array($this->saveMethods[$ext], [
            $im
        ]);
        $imageBinary = ob_get_contents();
        ob_end_clean();
        return $imageBinary;
    }
}