<?php
/**
 * Created by PhpStorm.
 * User: niklas
 * Date: 12.01.19
 * Time: 15:53
 */

namespace App\XRayLP\LearningCenterBundle\Util;


class ByteConverter
{
    /**
     * Automatically converts a size to an readably unit
     *
     * @param int $byte
     * @return array
     */
    public static function byteAutoConvert($byte)
    {
        $size = array();

        switch ($byte)
        {
            case $byte > 1000000000:
                $size['size'] = self::byteToGigabyte($byte);
                $size['unit'] = 'gb';
                break;
            case $byte > 1000000:
                $size['size'] = self::byteToMegabyte($byte);
                $size['unit'] = 'mb';
                break;
            case $byte > 1000:
                $size['size'] = self::byteToKilobyte($byte);
                $size['unit'] = 'kb';
                break;
            default:
                $size['size'] = $byte;
                $size['unit'] = 'b';
                break;
        }

        return $size;
    }

    /**
     * Converts a byte size into a kilobyte size
     *
     * @param int $byte
     * @return float|int
     */
    public static function byteToKilobyte($byte)
    {
        return round($byte/1000);
    }

    /**
     * Converts a byte size into a megabyte size
     *
     * @param int $byte
     * @return float|int
     */
    public static function byteToMegabyte($byte)
    {
        return round($byte/1000000);
    }

    /**
     * Converts a byte size into a gigabyte size
     *
     * @param int $byte
     * @return float|int
     */
    public static function byteToGigabyte($byte)
    {
        return round($byte/1000000000);
    }
}