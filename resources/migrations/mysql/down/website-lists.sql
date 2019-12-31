SET FOREIGN_KEY_CHECKS = false;
DROP TABLE IF EXISTS `list_items`;
DROP TABLE IF EXISTS `lists`;
DROP TABLE IF EXISTS `list_types`;
DELETE FROM `admin_resources` WHERE `identifier` IN ('lists');
DELETE FROM `casbin_rule` WHERE `v1` = 'lists' AND `v2` = 'advanced-write';
SET FOREIGN_KEY_CHECKS = true;
