-- This is not the final database structure

CREATE TABLE user (
   user_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
   first_name VARCHAR(100) NOT NULL,
   last_name VARCHAR(100) NOT NULL,
   email VARCHAR(100) NOT NULL,
   password VARCHAR(100) NOT NULL, -- Hash of the encrypted password
   birth_date DATETIME NOT NULL,
	gender VARCHAR(100) NOT NULL,
	profile_pic VARCHAR(100) DEFAULT 'default.png', -- File name for public/images/profiles
	about TEXT NULL
);

CREATE TABLE post (
   post_id INT AUTO_INCREMENT NOT NULL,
   user_id INT NOT NULL, -- Owner of this post
   date_of_upload DATETIME NOT NULL,
   image VARCHAR(100) NULL, -- File name for public/images/posts can be here
   content TEXT NOT NULL,
   PRIMARY KEY(user_id, post_id)
);

CREATE TABLE action ( -- Actions like comment/upvotes/downvotes stored here
   action_id INT AUTO_INCREMENT NOT NULL,
   entity_id INT NOT NULL, -- Where was the action
   user_id INT NOT NULL, -- Who did the action
   action_date DATETIME NOT NULL, -- When it happened
   entity_type VARCHAR(100) NOT NULL, -- like post/user and ?
   action_type VARCHAR(100) NOT NULL, -- comment/upvote/downvote and ?
   content TEXT NULL, -- Only relevant when the type is comment
   PRIMARY KEY(user_id, post_id, action_id)
);

CREATE TABLE friend (
  friend_id INT AUTO_INCREMENT NOT NULL,
  user1_id INT NOT NULL, -- Request from this user
  user2_id INT NOT NULL, -- To this
  status VARCHAR(100) NOT NULL, -- Can be friends, request ...
  PRIMARY KEY(friend_id, user1_id, user2_id)
);