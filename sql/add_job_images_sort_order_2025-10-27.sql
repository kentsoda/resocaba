-- Add sort_order to job_images and initialize existing rows by id order
ALTER TABLE `job_images`
  ADD COLUMN `sort_order` INT NOT NULL DEFAULT 0 AFTER `image_url`;

-- Initialize sort order based on id
UPDATE `job_images` ji
JOIN (
  SELECT id, ROW_NUMBER() OVER (PARTITION BY job_id ORDER BY id ASC) - 1 AS rn
  FROM job_images
) t ON t.id = ji.id
SET ji.sort_order = t.rn;


