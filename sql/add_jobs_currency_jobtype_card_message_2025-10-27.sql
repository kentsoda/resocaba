-- Add currency, job_type, card_message to jobs
ALTER TABLE `jobs`
  ADD COLUMN `currency` VARCHAR(16) NULL AFTER `salary_unit`,
  ADD COLUMN `job_type` VARCHAR(50) NULL AFTER `employment_type`,
  ADD COLUMN `card_message` TEXT NULL AFTER `message_html`;


