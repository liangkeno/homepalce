<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/20
 * Time: 16:18
 */

class Apptha_Homeplace_Block_Homeplace extends Mage_Core_Block_Template{


    /**
     * Get sub category collection of the particular category
     *
     * @return array
     */
    public function getSubCategories($categoryId) {
        $subCatId = array ();
        /**
         * Get Categories
         */
        $children = Mage::getModel ( 'catalog/category' )->getCategories ( $categoryId );
        foreach ( $children as $_children ) {
            $subCatId [] = $_children->getId ();
        }
        return $subCatId;
    }

    /**
     * Load category information
     *
     * @return array
     */
    public function loadCategoryInfo($categoryId) {
        /**
         * load category id
         */
        return Mage::getModel ( 'catalog/category' )->load ( $categoryId );
    }

    /**
     * Setting js and css file for section
     */

    protected $headCss = 'homeplace/homeplace.css';
    protected $headJs = 'homeplace/homeloading.js';

    /**
     * Set template for section
     */
    protected function _beforeToHtml() {
        parent::_beforeToHtml ();

        if (! $this->getTemplate ()) {
            $this->setTemplate ( 'homeplace/homeframe.phtml' );
        }

        return $this;
    }

    /**
     * Prepare layout
     */
    protected function _prepareLayout() {
        /**
         * Seting js and css file
         */
        if (($headBlock = $this->getLayout ()->getBlock ( 'head' )) !== false) {

            $headBlock->addCss ( $this->headCss );
            $headBlock->addItem('skin_js',$this->headJs);
        }
       // return parent::_prepareLayout ();
    }

    public function getMyCollection($id=null){
        if($id){
            $collection=Mage::getModel('homeplace/homeplace')->load($id);
            return $collection;
        }else{
            $collection=Mage::getModel('homeplace/homeplace')->getCollection();
            return $collection;
        }
    }

    public function getAdSlider($adId){
        $sliderCollection=Mage::getModel('homeplace/productad')->load($adId);
        return $sliderCollection;
    }

    public function getCustomizeProduct($productId){
        $cutomizeProductCollection=Mage::getModel('catalog/product')->load($productId);
        return $cutomizeProductCollection;
    }

    /**
     * Function to get quick view category links
     *
     * Return the quick view category collection as array
     *
     * @return array
     */
    public function getQuickview() {
        /**
         * getting category collection
         */
        $categoryCollection = Mage::getModel ( 'catalog/category' )->getCollection ();
        /**
         * getting quick view
         */
        $categoryCollection->addAttributeToFilter ( 'quick_view', '1' );
        /**
         * getting include in menu
         */
        $categoryCollection->addAttributeToFilter ( 'include_in_menu', array (
            'eq' => 1
        ) );
        /**
         * category collection is active
         */
        $categoryCollection->addAttributeToFilter ( 'is_active', array (
            'eq' => '1'
        ) );
        $categoryCollection->addAttributeToSelect ( '*' );
        /**
         * setting pagination
         */
        $categoryCollection->setPageSize ( 10 )->setCurPage ( 1 );
        return $categoryCollection;
    }

    /**
     * Function to get product collection
     *
     * This Function will return the product collection
     *
     * @return array
     */
    public function getProductCollection() {
        $_productCollection = Mage::getResourceModel ( 'catalogsearch/advanced_collection' )->addAttributeToSelect ( Mage::getSingleton ( 'catalog/config' )->getProductAttributes () )->addMinimalPrice ()->addStoreFilter ();
        Mage::getSingleton ( 'catalog/product_status' )->addVisibleFilterToCollection ( $_productCollection );
        /**
         * Filter by visibility
         */
        Mage::getSingleton ( 'catalog/product_visibility' )->addVisibleInSearchFilterToCollection ( $_productCollection );
        /**
         * todays date
         */
        $todayDate = date ( 'm/d/y' );
        /**
         * Tomorrow
         */
        $tomorrow = mktime ( 0, 0, 0, date ( 'm' ), date ( 'd' ), date ( 'y' ) );
        $tomorrowDate = date ( 'm/d/y', $tomorrow );
        /**
         * Getting Product Collection
         */
        $_productCollection->addAttributeToFilter ( 'special_from_date', array (
            'date' => true,
            'to' => $todayDate
        ) )->addAttributeToFilter ( 'special_to_date', array (
            'or' => array (
                0 => array (
                    'date' => true,
                    'from' => $tomorrowDate
                ),
                1 => array (
                    'is' => new Zend_Db_Expr ( 'null' )
                )
            )
        ), 'left' );
        return $_productCollection;
    }

    /**
     * Best seller product Collection
     *
     * @return array
     */
    public function bestSellerCollection($parentId) {
        /* 根据类目Id获取子类目Id*/
        $category = Mage::getModel('catalog/category')->load($parentId);
        $childrenIds = $category->getResource()->getChildren($category, true);

        $storeId = Mage::app ()->getStore ()->getId ();
        if(Mage::getStoreConfigFlag('catalog/frontend/flat_catalog_product')) {

            $products = Mage::getResourceModel ( 'reports/product_collection' )->addOrderedQty ()->setStoreId ( $storeId )
                ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
                ->addAttributeToFilter('category_id',array('in'=>$childrenIds))
                ->addStoreFilter ( $storeId )->setOrder ( 'ordered_qty', 'desc' );
            $prefix = Mage::getConfig ()->getTablePrefix ();
            $products->getSelect ()->joinInner ( array (
                'e2' => $prefix . 'catalog_product_flat_' . $storeId
            ), 'e2.entity_id = e.entity_id' );

            Mage::getSingleton ( 'catalog/product_status' )->addVisibleFilterToCollection ( $products );
            Mage::getSingleton ( 'catalog/product_visibility' )->addVisibleInCatalogFilterToCollection ( $products );
        } else {
            $visibility = array (
                Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
                Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
            );

            $products =  Mage::getResourceModel ( 'reports/product_collection' )->addOrderedQty ()
                ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
                ->addAttributeToFilter('category_id',array('in'=>$childrenIds))
                ->addAttributeToSelect ( '*' )->addAttributeToSelect ( array (
                'name',
                'price',
                'small_image'
            ) )->setStoreId ( $storeId )->addStoreFilter ( $storeId )->addAttributeToFilter ( 'visibility', $visibility )->addAttributeToFilter ( 'status', array (
                'eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED
            ) )->setOrder ( 'ordered_qty', 'desc' );
        }
        $configValueStockStatus = Mage::getStoreConfig('cataloginventory/options/show_out_of_stock', $storeId);
        if($configValueStockStatus == 0){
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
        }


        return $products;

    }

    /**
     * Function to get the new products
     *
     * Return New product collection as array
     *
     * @return array
     */
    public function getNewproduct($parentId) {
       /* 根据类目Id获取子类目Id*/
        $category = Mage::getModel('catalog/category')->load($parentId);
        $childrenIds = $category->getResource()->getChildren($category, true);
        /**
         * getting store id
         */
        $storeId = Mage::app ()->getStore ()->getId ();
        /**
         * todays date
         */
        $todayDate = Mage::app ()->getLocale ()->date ()->toString ( Varien_Date::DATETIME_INTERNAL_FORMAT );
        $newProductCollection =  Mage::getModel ( 'catalog/product' )->getCollection ()
            ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
            ->addStoreFilter ( $storeId )->addAttributeToSelect ( '*' )
            ->addAttributeToFilter('category_id',array('in'=>$childrenIds))
            ->addAttributeToFilter ( 'news_from_date', array (
            'date' => true,
            'to' => $todayDate
        ) )->addAttributeToFilter ( 'news_to_date', array (
            'or' => array (
                0 => array (
                    'date' => true,
                    'from' => $todayDate
                ),
                1 => array (
                    'is' => new Zend_Db_Expr ( 'null' )
                )
            )
        ), 'left' )->addAttributeToSort ( 'entity_id', 'desc' )->addAttributeToFilter ( 'status', array (
            'eq' => 1
        ) )->setPageSize(4);

        $configValueStockStatus = Mage::getStoreConfig('cataloginventory/options/show_out_of_stock', $storeId);

        if($configValueStockStatus == 0){
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($newProductCollection);
        }
        $newProductCollection->getSelect()->group('e.entity_id');
        return $newProductCollection;
    }

    /**
     * Function to get the category products
     *
     * Return category product collection as array
     *
     * @return array
     */
    public function getProductList($parentId) {
        /* 根据类目Id获取子类目Id*/
        $category = Mage::getModel('catalog/category')->load($parentId);
        $childrenIds = $category->getResource()->getChildren($category, true);
        /**
         * getting store id
         */
        $storeId = Mage::app ()->getStore ()->getId ();
        /**
         * todays date
         */
        $newProductCollection =  Mage::getModel ( 'catalog/product' )->getCollection ()
            ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
            ->addStoreFilter ( $storeId )->addAttributeToSelect ( '*' )
            ->addAttributeToFilter('category_id',array('in'=>$childrenIds))
            ->addAttributeToSort ( 'entity_id', 'desc' )->addAttributeToFilter ( 'status', array (
                'eq' => 1
            ) )->setPageSize(5);

        $configValueStockStatus = Mage::getStoreConfig('cataloginventory/options/show_out_of_stock', $storeId);

        if($configValueStockStatus == 0){
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($newProductCollection);
        }
        $newProductCollection->getSelect()->group('e.entity_id');
        return $newProductCollection;
    }



}