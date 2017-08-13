CREATE DATABASE IF NOT EXISTS `resumeDb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `resumeDb`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `about_me` varchar(60000) NOT NULL,
  `degree` varchar(255) NOT NULL,
  `created` timestamp NOT NULL default current_timestamp() ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `user_experience` (
  `experience_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `description` varchar(60000) NOT NULL,
  `created` timestamp NOT NULL default current_timestamp() ,
  PRIMARY KEY (`experience_id` )
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `user_pro_skills` (
  `skill_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`user_id`, `skill_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `pro_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `user_per_skills` (
  `skill_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`user_id`, `skill_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `per_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `user_education` (
  `education_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `description` varchar(60000) NOT NULL,
  `created` timestamp NOT NULL default current_timestamp() ,
  PRIMARY KEY (`education_id` )
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `user_hobbies` (
  `hobby_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`, `hobby_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `hobbies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `user_languages` (
  `language_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`user_id`, `language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `user_social_networks` (
  `network_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`, `network_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `social_networks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


insert into social_networks (name, icon_name) values("facebook", "fa fa-facebook");
insert into social_networks (name, icon_name) values("linkedin", "fa fa-linkedin");
insert into social_networks (name, icon_name) values("instagram", "fa fa-instagram");
insert into social_networks (name, icon_name) values("medium", "fa fa-medium");
insert into social_networks (name, icon_name) values("website", "fa fa-globe");
insert into social_networks (name, icon_name) values("google_plus", "fa fa-google-plus");
insert into social_networks (name, icon_name) values("twitter", "fa fa-twitter");

insert into per_skills (name) values("Creativity");
insert into per_skills (name) values("Hard Work");
insert into per_skills (name) values("Team Work");
insert into per_skills (name) values("Leader Ship");
insert into per_skills (name) values("Home Working");
insert into per_skills (name) values("Charisma");
insert into per_skills (name) values("Algorithms");


insert into pro_skills (name) values("Photoshop");
insert into pro_skills (name) values("Illustrator");
insert into pro_skills (name) values("JavaScript");
insert into pro_skills (name) values("HTML/CSS");
insert into pro_skills (name) values("Math");
insert into pro_skills (name) values("Psychology");
insert into pro_skills (name) values("Physics");
insert into pro_skills (name) values("Java");
insert into pro_skills (name) values("Android");

insert into hobbies (name, icon_name) values("sport", "fa fa-futbol-o");
insert into hobbies (name, icon_name) values("reading", "fa fa-book");
insert into hobbies (name, icon_name) values("programming", "fa fa-terminal");
insert into hobbies (name, icon_name) values("sleeping", "fa fa-bed");
insert into hobbies (name, icon_name) values("socializing", "fa fa-share-alt");
insert into hobbies (name, icon_name) values("video_games", "fa fa-gamepad");

insert into languages (name) values("English");
insert into languages (name) values("French");
insert into languages (name) values("Hebrew");
insert into languages (name) values("Russian");
insert into languages (name) values("Arabic");
insert into languages (name) values("Italian");
