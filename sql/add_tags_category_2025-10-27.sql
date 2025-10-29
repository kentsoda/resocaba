-- Add category column to tags for admin grouping (safe IF NOT EXISTS style)
ALTER TABLE `tags`
  ADD COLUMN `category` VARCHAR(64) NULL AFTER `type`;


