<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/21
 * Time: 11:36
 */
class Apptha_Homeplace_Adminhtml_HomeplaceController extends Mage_Adminhtml_Controller_Action{
    protected function _initAction() {
        $this->loadLayout()
            ->_setActiveMenu('homeplace/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Home Section Manager'), Mage::helper('adminhtml')->__('Home Section Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
            ->renderLayout();
    }
    //编辑section
    public function editAction() {

        $id     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('homeplace/homeplace')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('homeplace_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('homeplace/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Section Manager'), Mage::helper('adminhtml')->__('Section Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Section News'), Mage::helper('adminhtml')->__('Section News'));


            $this->_addContent($this->getLayout()->createBlock('homeplace/adminhtml_homeplace_edit'))
                ->_addLeft($this->getLayout()->createBlock('homeplace/adminhtml_homeplace_edit_tabs'));


            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('homeplace')->__('section does not exist'));
            $this->_redirect('*/*/');
        }
    }
    //新建section
    public function newAction() {
        $this->_forward('edit');
    }


    public function removeFile($file,$path=null)
    {
        $_helper = Mage::helper('homeplace');
        $file = $_helper->updateDirSepereator($file);
        if($path){
            $filePath = Mage::getBaseDir('media') .$path. $file ;
        }else{
            $filePath = Mage::getBaseDir('media') . $file ;
        }
        if(file_exists($filePath)){
           return unlink($filePath);
        }
    }


    public function saveImage($inputName,$requestFile){
        /* Starting upload */
        $uploader = new Varien_File_Uploader($inputName);
        // Any extention would work
        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
        $uploader->setAllowRenameFiles(true);
        // true -> get the file in the product like folders
        //	(file.jpg will go in something like /media/f/i/file.jpg)
        $uploader->setFilesDispersion(false);
        $dirPath=DS . 'homeplace'  . DS . 'section'  . DS;
        $path = Mage::getBaseDir('media') . $dirPath ;
        $io = new Varien_Io_File();
        $io->checkAndCreateFolder($path);

        $ext = explode('.', $requestFile);
        $adActivityName = "home_section_" . time() . "." . $ext[1];
        $uploader->save($path,$adActivityName);
        return $dirPath.$adActivityName;
    }


    public function saveAction() {

        $id=$this->getRequest()->getParam('id');
        $requestDate=$this->getRequest()->getPost();

        try{

            if($id == ''){
                $adActivityName="";
                if(isset($_FILES['ad_activity_path']['name']) && $_FILES['ad_activity_path']['name'] != '') {
                    $adActivityName=$this->saveImage('ad_activity_path', $_FILES['ad_activity_path']['name']);
                }
                $homeModel=Mage::getModel('homeplace/homeplace');
                $homeModel->setSectionName($requestDate['section_name']);
                $homeModel->setAdActivityBrief($requestDate['ad_activity_brief']);
                $homeModel->setAdActivityLink($requestDate['ad_activity_link']);
                $homeModel->setAdActivityPath($adActivityName);

                $homeModel->setCateSelect($requestDate['cate_select']);
                $homeModel->setCateStatus($requestDate['cate_status']);

                $homeModel->setProductListStatus($requestDate['product_list_status'][0]);
                $homeModel->setFourProducts($requestDate['four_products']);

                $homeModel->setAdList($requestDate['ad_list']);
                $homeModel->setAdStyle($requestDate['ad_style']);
                $homeModel->setStatus($requestDate['status']);
                $homeModel->setCreatedTime(now());
                $homeModel->setUpdateTime(now());
                $homeModel->save();

                Mage::getSingleton ( 'adminhtml/session' )->addSuccess ( Mage::helper ( 'homeplace' )->__ ( 'Section saved successfully' ) );
                $this->_redirect ( '*/*/' );
            }else{
                $adActivityName="";
                if (!empty( $_FILES['ad_activity_path']['name'] ))
                {
                    $adActivityName=$this->saveImage('ad_activity_path', $_FILES['ad_activity_path']['name']);
                    if (isset($requestDate['ad_activity_path']['value']) && $requestDate['ad_activity_path']['value'] != ''){
                        $this->removeFile($requestDate['ad_activity_path']['value']);
                    }

                }else {
                    if (isset($requestDate['ad_activity_path']['delete']) && $requestDate['ad_activity_path']['delete'] == 1){
                        if ($requestDate['ad_activity_path']['value'] != ''){
                            $this->removeFile($requestDate['ad_activity_path']['value']);
                        }
                    }else {
                        $adActivityName=$requestDate['ad_activity_path']['value'];
                    }
                }

                $homeModel=Mage::getModel('homeplace/homeplace');
                $homeModel->setSectionName($requestDate['section_name']);
                $homeModel->setAdActivityBrief($requestDate['ad_activity_brief']);
                $homeModel->setAdActivityLink($requestDate['ad_activity_link']);
                $homeModel->setAdActivityPath($adActivityName);

                $homeModel->setCateSelect($requestDate['cate_select']);
                $homeModel->setCateStatus($requestDate['cate_status']);

                $homeModel->setProductListStatus($requestDate['product_list_status'][0]);
                if($requestDate['product_list_status'][0]==3){
                    $homeModel->setFourProducts($requestDate['four_products']);
                }
                $homeModel->setAdList($requestDate['ad_list']);
                $homeModel->setAdStyle($requestDate['ad_style']);
                $homeModel->setUpdateTime(now());
                $homeModel->setStatus($requestDate['status']);
                $homeModel->setId($this->getRequest()->getParam('id'))->save();
                Mage::getSingleton ( 'adminhtml/session' )->addSuccess ( Mage::helper ( 'homeplace' )->__ ( 'Section update successfully' ) );
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $homeModel->getId()));
                    return;
                }
                $this->_redirect ( '*/*/' );
            }

        }catch (Mage_Core_Exception $e){
            $e->getMessages();
        }

    }


    //增加广告产品
    public function addadAction(){

        $this->_initAction()
            ->_setActiveMenu('homeplace/items');
        $this->renderLayout();

    }
    //删除广告产品
    public function deleteadAction(){
        $id=$this->getRequest()->getParam('id');
        if(isset($id)){
            $adModel=Mage::getModel('homeplace/productad')->load($id);
            $path=DS . 'homeplace'  . DS . 'images'  . DS;
            $isdeleteFile=$this->removeFile($adModel->getAdPath(),$path);
            $adModel->delete();
            if($isdeleteFile){
                echo "back".$id."||".$isdeleteFile;
            }
        }

    }
    //显示post产品
    public function showpostAction(){
        $dataes = $this->getRequest()->getPost();
        if(isset($dataes['updatepath'])){
            array_unshift($dataes['updatepath'],'');
        }
        //var_dump($dataes);
        $adIdList = array();
        if ($dataes) {

                try {

                        //存储数据
                        $length = count($dataes['ad_name']);
                        for ($i = 0; $i < $length; $i++) {
                            if (empty($dataes['ad_name'][$i]) || empty($dataes['ad_brief'][$i])) {
                                continue;
                            }
                            $createFileName = '';
                            //文件上传处理
                            if (isset($_FILES['ad_path']['name'][$i]) && $_FILES['ad_path']['name'][$i] != '') {
                            /* Starting upload */
                            $uploader = new Varien_File_Uploader(array(
                                'name' => $_FILES['ad_path']['name'][$i],
                                'type' => $_FILES['ad_path']['type'][$i],
                                'tmp_name' => $_FILES['ad_path']['tmp_name'][$i],
                                'error' => $_FILES['ad_path']['error'][$i],
                                'size' => $_FILES['ad_path']['size'][$i]
                            ));

                            // Any extention would work
                            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));

                            //如果为更新则保持名称不变进行覆盖，否则新添加则新建文件名保存
                            if($dataes['id'][$i] !=''){
                                $createFileName= $dataes['updatepath'][$i];
                                $uploader->setAllowRenameFiles(false);
                            }else{
                                $ext = explode('.', $_FILES['ad_path']['name'][$i]);
                                $nameArr = explode(' ', $dataes['ad_name'][$i]);
                                $nameStr = implode('_', $nameArr);
                                $createFileName = uniqid('homepage_'). time() . "." . $ext[1];
                                $uploader->setAllowRenameFiles(true);
                            }
                            // Set the file upload mode
                            // false -> get the file directly in the specified folder
                            // true -> get the file in the product like folders
                            //	(file.jpg will go in something like /media/f/i/file.jpg)
                            $uploader->setFilesDispersion(false);
                            $path = Mage::getBaseDir('media') . DS . 'homeplace'.DS.'images'.DS;
                            $io = new Varien_Io_File();
                            $io->checkAndCreateFolder($path);
                            $uploader->save($path, $createFileName);

                            }

                            $model = Mage::getModel('homeplace/productad');
                            $model->setAdName($dataes['ad_name'][$i]);
                            $model->setAdBrief($dataes['ad_brief'][$i]);
                            $model->setAdLink($dataes['ad_link'][$i]);
                            $model->setUpdateTime(now());


                            if($dataes['id'][$i] !=''){
                                $model->setId($dataes['id'][$i])->save();
                            }else{
                                $model->setAdPath($createFileName);
                                $model->save();
                            }

                            $adIdList[] = $model->getId();
                        }

                    $backStr = urlencode(implode(',', $adIdList));

                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('homeplace')->__('product Adverting is added :' . $backStr));
                    $this->_redirect('*/*/addad', array('id' => $backStr));

                } catch (Mage_Core_Exception $e) {
                    $e->getMessage();
                }


        }


    }

    //删除grid表的记录

    public function massDeleteAction() {
        $gridRecordIds = $this->getRequest()->getParam('homeplace');
        if(!is_array($gridRecordIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($gridRecordIds as $gridRecordId) {
                    $web = Mage::getModel('homeplace/homeplace')->load($gridRecordId);
                    $web->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($gridRecordIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    //更新grid表记录的状态
    public function massStatusAction()
    {
         $gridRecordIds = $this->getRequest()->getParam('homeplace');
        if(!is_array($gridRecordIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($gridRecordIds as $gridRecordId) {
                    $homeplace = Mage::getSingleton('homeplace/homeplace')
                        ->load($gridRecordId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($gridRecordIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }


}