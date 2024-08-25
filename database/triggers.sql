-- TRIGGER01
-- I can't follow myself 
CREATE OR REPLACE FUNCTION prevent_self_follow() RETURNS TRIGGER AS 
$BODY$
BEGIN
    IF NEW.id_user1 = NEW.id_user2 THEN
        RAISE EXCEPTION 'A user cannot follow themselves';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

-- Create the trigger on the followUser table
CREATE TRIGGER prevent_self_follow
BEFORE INSERT ON followUser
FOR EACH ROW
EXECUTE PROCEDURE prevent_self_follow();


-- TRIGGER02
-- Trigger for preventing post deletion
CREATE OR REPLACE FUNCTION prevent_post_deletion() RETURNS TRIGGER AS 
$BODY$
BEGIN
  IF OLD.id IS NOT NULL AND OLD.id_user = current_user THEN
    -- Check if the post has votes or comments
    IF EXISTS (SELECT 1 FROM vote WHERE id_post = OLD.id) OR
       EXISTS (SELECT 1 FROM comment WHERE id_post = OLD.id) THEN
      RAISE EXCEPTION 'Cannot delete post with votes or comments.';
    END IF;
  END IF;
  RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

-- Create the trigger for post deletion
CREATE TRIGGER prevent_post_delete
BEFORE DELETE ON post
FOR EACH ROW
EXECUTE PROCEDURE prevent_post_deletion();


-- TRIGGER03
-- Trigger for preventing comment deletion
CREATE OR REPLACE FUNCTION prevent_comment_delete() RETURNS TRIGGER AS 
$BODY$
BEGIN
    IF OLD.likes IS NOT NULL THEN
        RAISE EXCEPTION 'Cannot delete a comment with likes';
    ELSE
        RETURN OLD;
    END IF;
END
$BODY$
LANGUAGE plpgsql;

-- Create the trigger itself
CREATE TRIGGER prevent_comment_delete
BEFORE DELETE ON comment
FOR EACH ROW
EXECUTE PROCEDURE prevent_comment_delete();


-- TRIGGER04
-- Create a trigger function that notify user on comment
CREATE OR REPLACE FUNCTION notify_user_on_comment() RETURNS TRIGGER AS 
$BODY$
BEGIN
  -- Check if the comment is on the user's post
  IF NEW.id_post IN (SELECT id FROM post WHERE id_user = OLD.id_user) THEN
    -- Insert a notification for the user
    INSERT INTO notification (content, type, id_user)
    VALUES (
      'Someone commented on your post with ID ' || NEW.id_post,
      'comment',
      OLD.id_user
    );
  END IF;
  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

-- Create the trigger
CREATE TRIGGER comment_notification
AFTER INSERT ON comment
FOR EACH ROW
EXECUTE PROCEDURE notify_user_on_comment();


-- TRIGGER05
-- Create a trigger function that sends a notification when someone upvotes the post
CREATE OR REPLACE FUNCTION notify_user_on_vote() RETURNS TRIGGER AS 
$BODY$
BEGIN
    -- Check if the vote rating is 1 (upvote)
    IF NEW.rating = 1 THEN
        -- Insert a notification into the notification table
        INSERT INTO notification (content, type, id_user)
        VALUES (
            'Someone has upvoted your post (Post ID: ' || NEW.id_post || ').',
            'rating',
            (SELECT id_user FROM post WHERE id = NEW.id_post)
        );
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

-- Create a trigger on the vote table
CREATE TRIGGER vote_notification
AFTER INSERT ON vote
FOR EACH ROW
EXECUTE PROCEDURE notify_user_on_vote();


-- TRIGGER06
-- Create a function to send notifications when a user that i follow post a new post
CREATE OR REPLACE FUNCTION notify_new_post() RETURNS TRIGGER AS 
$BODY$
BEGIN
    -- Check if the author of the new post is being followed by the current user
    IF EXISTS (SELECT 1 FROM followUser WHERE id_user1 = NEW.id_user AND id_user2 = NEW.id_user) THEN
        -- Insert a notification into the notification table
        INSERT INTO notification (content, type, id_user)
        VALUES (
            'User ' || (SELECT username FROM users WHERE id = NEW.id_user) || ' created a new post: ' || NEW.title,
            'post',
            (SELECT id_user1 FROM followUser WHERE id_user2 = NEW.id_user)
        );
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

-- Create the trigger on the post table
CREATE TRIGGER new_post_notification
AFTER INSERT ON post
FOR EACH ROW
EXECUTE PROCEDURE notify_new_post();

