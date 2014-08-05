<?php
$installer = $this;
/* @var $installer Mage_Customer_Model_Entity_Setup */

$installer->startSetup();
$installer->run("

ALTER TABLE `{$installer->getTable('sales/quote_payment')}` ADD `iri_zip` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` ADD `iri_dep` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE `{$installer->getTable('sales/order_payment')}` ADD `iri_zip` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `{$installer->getTable('sales/order_payment')}` ADD `iri_dep` VARCHAR( 255 ) NOT NULL ;

");
$installer->endSetup();
