-- Fallback for MySQL 5.7 (no window functions)
-- Initialize job_images.sort_order per job_id using user variables

SET @prev_job_id := NULL;
SET @rn := -1;

UPDATE job_images ji
JOIN (
  SELECT id, job_id,
         @rn := IF(@prev_job_id = job_id, @rn + 1, 0) AS rn,
         @prev_job_id := job_id AS _pj
  FROM job_images
  ORDER BY job_id ASC, id ASC
) t ON t.id = ji.id
SET ji.sort_order = t.rn;


