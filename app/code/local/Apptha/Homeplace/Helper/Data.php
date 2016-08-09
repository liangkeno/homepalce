<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/22
 * Time: 18:01
 */


class Apptha_Homeplace_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function updateDirSepereator($path)
    {
        return str_replace('\\', DS, $path);
    }


}