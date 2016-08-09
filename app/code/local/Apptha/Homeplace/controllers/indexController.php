<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/20
 * Time: 16:02
 */

class Apptha_Homeplace_IndexController extends Mage_Core_Controller_Front_Action{

    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();
    }

    public function sectionAction(){
       // echo "nihao";
        $this->loadLayout();
        $this->renderLayout();
    }


}