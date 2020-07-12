#
# PHP video-bb - MySQL schema
#

# --------------------------------------------------------
# Table structure for table 'videobb_config'
# --------------------------------------------------------
CREATE TABLE videobb_config (
    `id` int(11) NOT NULL auto_increment,
    `name` varchar(255) NOT NULL default '',
    `value` text NOT NULL default '',

    PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

# --------------------------------------------------------
# Table structure for table 'videobb_root'
# --------------------------------------------------------
CREATE TABLE videobb_root (
    `id` int(11) NOT NULL auto_increment,
    `enabled` TINYINT unsigned NOT NULL default 0,
    `caption` varchar(255) NOT NULL default '',
    `path` varchar(255) NOT NULL default '',
    `url` varchar(255) NOT NULL default '',
    `extensions` varchar(255) NOT NULL default '',
    `comment` varchar(255) NOT NULL default '',

    PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

# --------------------------------------------------------
# Table structure for table 'videobb_video'
# --------------------------------------------------------
CREATE TABLE videobb_video (
    `id` int(11) NOT NULL auto_increment,
    `uid` int(11) unsigned,
    `ctime` date NOT NULL,
    `atime` date NOT NULL,
    `name` varchar(255) NOT NULL default '',
    `genre` varchar(255) default '',
    `hits` int(11) unsigned,
    `description` text default '',
    `image` mediumblob default '',
    `image_by` int(11) unsigned NOT NULL default 0,

    PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;


# --------------------------------------------------------
# Table structure for table 'videobb_comments'
# --------------------------------------------------------
CREATE TABLE videobb_comments(
    `id` int(11) NOT NULL auto_increment,
    `video_id` int(11) unsigned NOT NULL,
    `ip` varchar(128) NOT NULL default '',
    `uid` int(11) unsigned NOT NULL,
    `subject` varchar(255) NOT NULL default '',
    `comment` text NOT NULL,
    `datetime` datetime NOT NULL,

    PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

# --------------------------------------------------------
# Table structure for table 'videobb_vote'
# --------------------------------------------------------
CREATE TABLE videobb_vote(
    `id` int(11) NOT NULL auto_increment,
    `video_id` int(11) NOT NULL,
    `uid` int(11) unsigned NOT NULL,
    `vote` int(11) NOT NULL,
    `datetime` datetime NOT NULL,

    PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;