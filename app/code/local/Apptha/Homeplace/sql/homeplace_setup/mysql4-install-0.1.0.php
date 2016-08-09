<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('homeplace')};
CREATE TABLE {$this->getTable('homeplace')} (
  `section_id` int(11) unsigned NOT NULL auto_increment,
  `section_name` varchar(255) NOT NULL default '',
  `ad_activity_path` varchar(255) NOT NULL default '',
  `ad_activity_link` varchar(255) NOT NULL default '',
  `ad_activity_brief` varchar(255) NOT NULL default '',
  `cate_select` varchar(255) NOT NULL default '',
  `four_products` varchar(255) NOT NULL default '',
  `ad_list` varchar(255) NOT NULL default '',
  `ad_style` varchar(255) NOT NULL default '',
   `cate_status` smallint(6) NOT NULL default '0',
   `product_list_status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
   `status` smallint(6) NOT NULL default '0',
  PRIMARY KEY (`section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");


$installer->run("

DROP TABLE IF EXISTS {$this->getTable('home_product_ad')};
CREATE TABLE {$this->getTable('home_product_ad')} (
  `product_ad_id` int(11) unsigned NOT NULL auto_increment,
  `ad_name` varchar(255) NOT NULL default '',
  `ad_brief` varchar(255) NOT NULL default '',
  `ad_path` varchar(255) NOT NULL default '',
  `ad_link` varchar(255) NOT NULL default '',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`product_ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");


$installer->endSetup(); 