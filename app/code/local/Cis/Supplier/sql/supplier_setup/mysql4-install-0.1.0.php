<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('supplier')};
CREATE TABLE {$this->getTable('supplier')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `supplier_id` varchar(255) NOT NULL default '',
  `supplier_name` varchar(255) NOT NULL default '',
  `supplier_url` varchar(255) NOT NULL default '',
  `supplier_extra1` varchar(255) NOT NULL default '',
  `supplier_extra2` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '1',
  `supplier_url_create_date` timestamp default CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 