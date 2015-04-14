<?php

namespace vova07\imperavi\helpers;

use yii\base\InvalidParamException;
use yii\helpers\BaseFileHelper;

/**
 * Image helper
 *
 * @author Artem Denysov <denysov.artem@gmail.com>
 *
 * @link https://github.com/denar90
 */
class ImageHelper extends BaseFileHelper
{

    /**
     * @inheritdoc
     *
     * @param string $imagePath
     * @param string $croppingPrefix
     * @param string $imageName
     * @param array $croppingData = [
     *   //example
     *   'x' => 1,
     *   'y' => 1,
     *   'width' => 100,
     *   'height' => 100
     * ]
     * @return bool | throw
     */
    public static function cropImage($imagePath, $croppingPrefix, $imageName, $croppingData)
    {
        $src = $imagePath . $imageName;
        $dstSrc = $imagePath . $croppingPrefix . $imageName;
        $width = $croppingData['width'];
        $height = $croppingData['height'];
        $imageInfo = getimagesize($src);

        if ($imageInfo === false) {
            throw new InvalidParamException('Can\'t get image info');
        }

        $imageType = $imageInfo[2];

        switch ($imageType) {
            case IMAGETYPE_GIF:
                $src_img = imagecreatefromgif($src);
                break;

            case IMAGETYPE_JPEG:
                $src_img = imagecreatefromjpeg($src);
                break;

            case IMAGETYPE_PNG:
                $src_img = imagecreatefrompng($src);
                break;
        }

        $dst_img = imagecreatetruecolor($width, $height);
        $result = imagecopyresampled(
            $dst_img,
            $src_img,
            0,
            0,
            $croppingData['x'],
            $croppingData['y'],
            $width,
            $height,
            $width,
            $height
        );

        imagedestroy($src_img);

        if ($result) {
            switch ($imageType) {
                case IMAGETYPE_GIF:
                    $result = imagegif($dst_img, $dstSrc);
                    break;

                case IMAGETYPE_JPEG:
                    $result = imagejpeg($dst_img, $dstSrc);
                    break;

                case IMAGETYPE_PNG:
                    $result = imagepng($dst_img, $dstSrc);
                    break;
            }

            imagedestroy($dst_img);

            if (!$result) {
                throw new InvalidParamException('Can\'t create image file');
            } else {
                return true;
            }

        } else {
            imagedestroy($dst_img);
            return false;
        }
    }
}
