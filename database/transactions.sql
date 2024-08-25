-- RETRIEVE THE 10 MOST RECENT NEWS ARTICLES
BEGIN TRANSACTION; 

SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

SELECT
    post.id AS article_id,
    post.title AS article_title,
    post.description AS article_description,
    post."date" AS article_date,
    users.username AS author_username
FROM post
INNER JOIN users ON post.id_user = users.id
ORDER BY post."date" DESC
LIMIT 10;

COMMIT;
-- ##########################################################



-- RETRIEVE NEWS FROM A CERTAIN TOPIC (PRECISO ARRANJAR FORMA DE RECEBER QUALQUER TIPO DE TOPIC)
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

SELECT p.title, p.description, u.username AS author
FROM post p
INNER JOIN users u ON p.id_user = u.id
WHERE p.id_topic = $id_topic;

COMMIT;
-- ##########################################################



-- RETRIEVE THE 10 MOST UPVOTED NEWS
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

SELECT post.id, post.title, post.description, post.popularity, users.username AS author
FROM post
INNER JOIN users ON post.id_user = users.id
ORDER BY post.popularity DESC
LIMIT 10;

COMMIT;
-- ##########################################################



-- RETRIEVE A POST BY SEARCHING FOR IT (PRECISO ARRANJAR FORMA DE INSERIR O INPUT DO UTILIZADOR)
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

SELECT post.id, post."date", post.title, post.description, users.username AS author
FROM post
INNER JOIN users ON post.id_user = users.id
WHERE post.title ILIKE '%$keyword%' -- Change to the input inserted by the user
AND post."date" <= 'today'::"Today"
ORDER BY post."date" DESC;

COMMIT;
-- ##########################################################



-- CHECK A USER'S PROFILE AND RETRIVE HIS SUBMITTED POSTS AND FOLLOWED TAGS
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

SELECT u.username, u.firstName, u.lastName, u.email, u.reputation
FROM users u
WHERE u.id = $id_user;

-- List of posts created by the user
SELECT p.title, p.description, p."date", p.popularity, t.title AS topic
FROM post p
INNER JOIN topic t ON p.id_topic = t.id
WHERE p.id_user = $id_user;

-- List of tags followed by the user
SELECT t.title
FROM tag t
INNER JOIN followTag ft ON t.id = ft.id_tag
WHERE ft.id_user = $id_user;

COMMIT;
-- ##########################################################



-- RETRIEVE THE COMMENTS FROM A POST (ARRANJAR FORMA GERAL
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE READ ONLY;

SELECT c.id, c.content, c."date", c.likes, u.username AS user_username
FROM comment AS c
JOIN users AS u ON c.id_user = u.id
WHERE c.id_post = $id_post;

COMMIT;
-- ##########################################################



-- CREATE A NEW USER'S PROFILE 
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

-- Insert a new user profile
INSERT INTO users (username, password, firstName, lastName, email)
VALUES ($username, $password, $firstName, $lastName, $email);

COMMIT;
-- ##########################################################



-- COMMENT ON A POST(ARRANJAR FORMA GERAL)
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

-- Insert a new comment
INSERT INTO comment (content, "date", likes, id_post, id_user)
VALUES ($content, now(), 0, $id_post, $id_user); -- Replace $post_id with the post ID and $user_id with the user's ID.

COMMIT;
-- ##########################################################



-- DELETE MY COMMENT FROM A POST (FORMA GERAL)
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

-- Check if the comment has any likes
SELECT likes
FROM comment
WHERE id = $id_comment;

IF likes = 0 THEN
  DELETE FROM comment
  WHERE id = $id_comment;
ELSE
  ROLLBACK;
END IF;

COMMIT;
-- ##########################################################



-- VOTE ON A POST (FORMA GERAL)
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

DECLARE existing_vote INTEGER;

SELECT rating INTO existing_vote
FROM vote
WHERE id_post = $id_post
AND id_user = $id_user;

-- If the user hasn't voted, insert their vote
IF existing_vote IS NULL THEN
  INSERT INTO vote (rating, id_post, id_user)
  VALUES ($rating, $id_post, $id_user);
-- If the user has already voted, update their vote
ELSE
  -- If the user is changing their vote, update the vote.
  IF $rating <> existing_vote THEN
    UPDATE vote
    SET rating = $rating
    WHERE id_post = $id_post
    AND id_user = $id_user;
  -- If the user is changing their vote to no vote, delete the vote.
  ELSE
    DELETE FROM vote
    WHERE id_post = $id_post
    AND id_user = $id_user;
  END IF;
END IF;

COMMIT;

-- ##########################################################



-- SUBMIT A POST. WITH A CERTAIN ARTICLE, DATE AND AUTHOR NAME (FORMA GERAL)
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

INSERT INTO post ("date", popularity, title, description, id_user, id_topic)
VALUES (now(), 0, $title, $description, $user_id, $topic_id);

-- Get the ID of the newly inserted post
SELECT lastval() INTO post_id;

-- Insert tags associated with the post (can add multiple tags)
INSERT INTO postWithTag (id_post, id_tag)
VALUES (post_id, $tag_id1), (post_id, $tag_id2), (post_id, $tag_id3);

-- Insert images related to the post (can add multiple images)
INSERT INTO image (image, typeOfImage, id_post, id_user)
VALUES ($image, $typeOfImage, post_id, $user_id),
       ($image, $typeOfImage, post_id, $user_id);
       
COMMIT;
-- ##########################################################



-- DELETE A SUBMITTED POST (FORMA GERAL)
BEGIN;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE; --SERIALIZABLE ensures that no other transactions can insert comments or votes for the same post between the time you perform the initial checks and the actual deletion. This prevents any race conditions and maintains data consistency.

-- Check if the post has no comments and no votes
WITH post_info AS (
  SELECT p.id AS post_id
  FROM post p
  WHERE p.id_user = $id_user
    AND p.id = $id_post
)
DELETE FROM post
WHERE id IN (
  SELECT post_id
  FROM post_info
)
AND (
  SELECT COUNT(*) FROM comment c WHERE c.id_post IN (SELECT post_id FROM post_info) = 0
)
AND (
  SELECT COUNT(*) FROM vote v WHERE v.id_post IN (SELECT post_id FROM post_info) = 0
);

-- Check if any rows were deleted
IF FOUND THEN
  COMMIT;
ELSE
  ROLLBACK;
END IF;
-- ##########################################################



-- EDIT A POST (FORMA GERAL)
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

-- Check if the user is the author of the post
IF EXISTS (SELECT 1 FROM post WHERE id = $id_post AND id_user = $id_user) THEN
    UPDATE post
    SET description = $content
    WHERE id = $id_post;
    
    COMMIT; -- If the user is the author, and the update is successful, save the changes.
ELSE
    ROLLBACK; -- Rollback if the user is not the author.
END IF;
-- ##########################################################



-- UPDATE PROFILE (FORMA GERAL)
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

UPDATE users
SET
  firstName = $firstName,
  lastName = $lastName,
  email = $email,
  password = $password
WHERE
  username = $username; -- Replace 'YourUsername' the actual username.

-- Check if any rows were affected by the update
IF FOUND THEN
  COMMIT;
ELSE
  ROLLBACK;
  RAISE EXCEPTION 'User was not found';
END IF;
-- ##########################################################



-- DELETE A POST
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

DELETE FROM post WHERE id = $id_post;

DELETE FROM comment WHERE id_post = $id_post;
DELETE FROM vote WHERE id_post = $id_post;
DELETE FROM image WHERE id_post = $id_post;

COMMIT;
-- ##########################################################



-- DELETE ANY COMMENT 
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

DELETE FROM comment
WHERE id = $id_comment;

COMMIT;
-- ##########################################################



-- BAN USER
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

SELECT id FROM users WHERE id = $user_id_to_ban FOR UPDATE;

INSERT INTO ban (reason, id_user)
VALUES ($reason, $user_id_to_ban);

COMMIT;
-- ##########################################################



-- UPDATE ROLES
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL READ COMMITTED;

UPDATE admin
SET id_admin = $new_admin_user_id
WHERE id_admin = $current_admin_user_id;

UPDATE journalist
SET id_journalist = $new_journalist_user_id
WHERE id_journalist = $current_journalist_user_id;

COMMIT;
-- ##########################################################



-- FOLLOW/UNFOLLOW A USER (FORMA GERAL)
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

-- Check if the user is already following the target user
SELECT EXISTS(
  SELECT 1
  FROM followUser
  WHERE id_user1 = $current_user_id
  AND id_user2 = $target_user_id
) INTO is_following;

-- Follow or unfollow based on the current status
IF is_following THEN
  -- Unfollow
  DELETE FROM followUser
  WHERE id_user1 = $current_user_id
  AND id_user2 = $target_user_id;
ELSE
  -- Follow
  INSERT INTO followUser (id_user1, id_user2)
  VALUES ($current_user_id, $target_user_id);
END IF;

COMMIT;
-- #############################################################

