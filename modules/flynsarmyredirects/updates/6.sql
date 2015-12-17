ALTER TABLE flynsarmyredirects_groups ADD COLUMN is_enabled tinyint(1) DEFAULT NULL after id;
ALTER TABLE flynsarmyredirects_redirects ADD COLUMN is_enabled tinyint(1) DEFAULT NULL after id;
CREATE INDEX is_enabled_idx ON flynsarmyredirects_groups (is_enabled);
CREATE INDEX is_enabled_idx ON flynsarmyredirects_redirects (is_enabled);
UPDATE flynsarmyredirects_groups SET is_enabled=1;
UPDATE flynsarmyredirects_redirects SET is_enabled=1;