<?php
/**
 * Tm Offermanager
 *
 * Promote Your offers on selected page to enhanced sale
 *
 * 
 * @category   Tm
 * @package    Offermanager
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
 
$installer = $this;
$installer->startSetup();
$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('offermanager_offer')};
CREATE TABLE {$this->getTable('offermanager_offer')} (
  `offer_id` int(11) unsigned NOT NULL auto_increment,
  `offer_name` varchar(255) NOT NULL,
  `description` text NULL,
  `offer_image` text NULL,
  `offer_icon` text NULL,
  `rule_id` int(11) unsigned NOT NULL,
  `status` int(3) unsigned NOT NULL default '1',
  `user_id` int(11) unsigned NOT NULL,
  `url_key` text NOT NULL,
  `created_date` datetime NULL,
  PRIMARY KEY (`offer_id`),
  CONSTRAINT `FK_offermanager_offer_catalogrule` FOREIGN KEY (`rule_id`) REFERENCES {$this->getTable('catalogrule')} (`rule_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup(); 