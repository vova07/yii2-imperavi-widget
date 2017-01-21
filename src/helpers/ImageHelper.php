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
     * @param string $imagePath Path to image from widget config
     * @param string $croppingPrefix Prefix for new cropped image
     * @param string $imageName Uploaded image name
     * @param string $croppingWidth Width of new cropped image
     * @param string $croppingHeight Height of new cropped image
     * @param string $croppingX x-coordinate of source point
     * @param string $croppingY y-coordinate of source point
     * @return bool
     * @throws InvalidParamException when can't create image
     */
    public static function cropImage($imagePath, $croppingPrefix, $imageName, $croppingWidth = 100, $croppingHeight = 100, $croppingX = 0, $croppingY = 0)
    {
        $src = $imagePath . $imageName;
        $dstSrc = $imagePath . $croppingPrefix . $imageName;
        $imageType = BaseFileHelper::getMimeType($src);

        switch ($imageType) {
            case 'image/gif':
                $src_img = imagecreatefromgif($src);
                break;

            case 'image/jpeg':
                $src_img = imagecreatefromjpeg($src);
                break;

            case 'image/png':
                $src_img = imagecreatefrompng($src);
                break;
        }

        $dst_img = imagecreatetruecolor($croppingWidth, $croppingHeight);
        $result = imagecopyresampled(
            $dst_img,
            $src_img,
            0,
            0,
            $croppingX,
            $croppingY,
            $croppingWidth,
            $croppingHeight,
            $croppingWidth,
            $croppingHeight
        );

        imagedestroy($src_img);

        if ($result) {
            switch ($imageType) {
                case 'image/gif':
                    $result = imagegif($dst_img, $dstSrc);
                    break;

                case 'image/jpeg':
                    $result = imagejpeg($dst_img, $dstSrc);
                    break;

                case 'image/png':
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
