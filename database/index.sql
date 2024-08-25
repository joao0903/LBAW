--PerformanceIndex

CREATE INDEX idx_user_notification ON notification USING hash (id_user);
CREATE INDEX idx_dateon_post ON post USING btree (date);
CREATE INDEX idx_post_comment ON comment USING hash (id_post);

--FTSIndex
ALTER TABLE post
ADD COLUMN title_tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION posts_search_update() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' OR (TG_OP = 'UPDATE' AND (NEW.title <> OLD.title)) THEN
    NEW.title_tsvectors = setweight(to_tsvector('english', NEW.title), 'A');
  END IF;
  RETURN NEW;
END $$ LANGUAGE plpgsql;

CREATE TRIGGER posts_search_update
BEFORE INSERT OR UPDATE ON post
FOR EACH ROW
EXECUTE FUNCTION posts_search_update();
DROP INDEX IF EXISTS idx_search_posts;
CREATE INDEX idx_search_posts ON post USING GIST (title_tsvectors);
------
ALTER TABLE users
ADD COLUMN username_tsvectors TSVECTOR;


CREATE OR REPLACE FUNCTION users_search_update() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' OR (TG_OP = 'UPDATE' AND (NEW.username <> OLD.username)) THEN
    NEW.username_tsvectors = setweight(to_tsvector('english', NEW.username), 'A');
  END IF;
  RETURN NEW;
END $$ LANGUAGE plpgsql;


CREATE TRIGGER users_search_update
BEFORE INSERT OR UPDATE ON users
FOR EACH ROW
EXECUTE FUNCTION users_search_update();

DROP INDEX IF EXISTS idx_search_users;
CREATE INDEX idx_search_users ON users USING GIST (username_tsvectors);
------
ALTER TABLE topic
ADD COLUMN name_tsvectors TSVECTOR;

CREATE OR REPLACE FUNCTION topics_search_update() RETURNS TRIGGER AS $$
BEGIN
  IF TG_OP = 'INSERT' OR (TG_OP = 'UPDATE' AND (NEW.title <> OLD.title)) THEN
    NEW.name_tsvectors = setweight(to_tsvector('english', NEW.title), 'A');
  END IF;
  RETURN NEW;
END $$ LANGUAGE plpgsql;


CREATE TRIGGER topics_search_update
BEFORE INSERT OR UPDATE ON topic
FOR EACH ROW
EXECUTE FUNCTION topics_search_update();


DROP INDEX IF EXISTS idx_search_topics;
CREATE INDEX idx_search_topics ON topic USING GIN (name_tsvectors);


