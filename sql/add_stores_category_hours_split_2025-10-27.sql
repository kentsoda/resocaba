-- Add store category and split business hours fields
ALTER TABLE `stores`
  ADD COLUMN `category` VARCHAR(32) NULL AFTER `name`,
  ADD COLUMN `business_hours_start` TINYINT NULL AFTER `business_hours`,
  ADD COLUMN `business_hours_end` VARCHAR(5) NULL AFTER `business_hours_start`;


