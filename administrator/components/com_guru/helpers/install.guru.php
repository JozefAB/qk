<?php
/*------------------------------------------------------------------------
# com_guru
# ------------------------------------------------------------------------
# author    iJoomla
# copyright Copyright (C) 2013 ijoomla.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.ijoomla.com
# Technical Support:  Forum - http://www.ijoomla.com.com/forum/index/
-------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.filesystem.folder' );

class com_guruInstallerScript{
	function install() {
		$database = JFactory::getDBO();	
		$this->installAlertUploadPlugins();
		$this->installGuruDiscussPlugin();
		$this->installGuruPayOfflinePlugin();
		//START COMPONENT CREATE TABLES
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_authors` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `userid` int(21) NOT NULL DEFAULT '0',
				  `gid` int(11) NOT NULL DEFAULT '0',
				  `full_bio` longtext NOT NULL,
				  `images` varchar(255) NOT NULL DEFAULT '',
				  `emaillink` int(2) NOT NULL DEFAULT '0',
				  `website` varchar(255) NOT NULL DEFAULT '',
				  `blog` varchar(255) NOT NULL DEFAULT '',
				  `facebook` varchar(255) NOT NULL DEFAULT '',
				  `twitter` varchar(255) NOT NULL DEFAULT '',
				  `show_email` tinyint(1) NOT NULL DEFAULT '1',
				  `show_website` tinyint(1) NOT NULL DEFAULT '1',
				  `show_blog` tinyint(1) NOT NULL DEFAULT '1',
				  `show_facebook` tinyint(1) NOT NULL DEFAULT '1',
				  `show_twitter` tinyint(1) NOT NULL DEFAULT '1',
				  `author_title` varchar(255) NOT NULL,
				  `ordering` int(11) NOT NULL DEFAULT '0',
				  `forum_kunena_generated` tinyint(4) NOT NULL DEFAULT '0',
				  `enabled` tinyint(4) NOT NULL DEFAULT '0',
				  `commission_id` int(11) NOT NULL DEFAULT '1',
				  `paypal_email` varchar(100) NOT NULL,
				  `paypal_other_information` text,
				  `paypal_option`  tinyint(1) NOT NULL DEFAULT '1',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_buy_courses` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `userid` int(11) NOT NULL DEFAULT '0',
			  `order_id` int(11) NOT NULL DEFAULT '0',
			  `course_id` int(11) NOT NULL,
			  `price` float NOT NULL,
			  `buy_date` datetime NOT NULL,
			  `expired_date` datetime NOT NULL,
			  `plan_id` varchar(255) NOT NULL,
			  `email_send` int(3) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_category` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(255) DEFAULT NULL,
			  `alias` varchar(255) NOT NULL,
			  `published` tinyint(1) NOT NULL DEFAULT '1',
			  `description` text,
			  `image` varchar(255) DEFAULT NULL,
			  `ordering` int(11) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_categoryrel` (
			  `parent_id` int(11) NOT NULL DEFAULT '1',
			  `child_id` int(11) NOT NULL DEFAULT '1'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_certificates` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `general_settings` varchar(5) DEFAULT NULL,
			  `design_background` varchar(255) DEFAULT NULL,
			  `design_background_color` varchar(11) DEFAULT 'ACE0F6',
			  `design_text_color` varchar(11) NOT NULL DEFAULT '333333',
			  `avg_cert` int(11) NOT NULL DEFAULT '70',
			  `templates1` text NOT NULL,
			  `templates2` text NOT NULL,
			  `templates3` text NOT NULL,
			  `templates4` text NOT NULL,
			  `subjectt3` text NOT NULL,
			  `subjectt4` text NOT NULL,
			  `font_certificate` text NOT NULL,
			  `library_pdf` tinyint(2) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "select u.`id` from #__users u, #__user_usergroup_map ugm where u.`id`=ugm.`user_id` and ugm.`group_id`='8' LIMIT 0,1";
		$database->setQuery($sql);
		$database->query();
		$default_email = $database->loadColumn();
		$default_email = @$default_email["0"];
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_config` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `currency` varchar(255) NOT NULL DEFAULT 'USD',
			  `datetype` varchar(255) NOT NULL DEFAULT '0',
			  `dificulty` varchar(255) DEFAULT NULL,
			  `influence` tinyint(1) NOT NULL,
			  `display_tasks` tinyint(1) NOT NULL DEFAULT '0',
			  `groupe_tasks` tinyint(1) NOT NULL DEFAULT '0',
			  `display_media` tinyint(1) NOT NULL DEFAULT '0',
			  `show_unpubl` tinyint(1) NOT NULL DEFAULT '0',
			  `btnback` tinyint(1) NOT NULL DEFAULT '1',
			  `btnhome` tinyint(1) NOT NULL DEFAULT '1',
			  `btnnext` tinyint(1) NOT NULL DEFAULT '1',
			  `dofirst` tinyint(1) NOT NULL DEFAULT '0',
			  `imagesin` varchar(255) NOT NULL DEFAULT 'images/stories',
			  `videoin` varchar(255) NOT NULL DEFAULT 'media/videos',
			  `audioin` varchar(255) NOT NULL DEFAULT 'media/audio',
			  `docsin` varchar(255) NOT NULL DEFAULT 'media/documents',
			  `filesin` varchar(255) NOT NULL DEFAULT 'media/files',
			  `certificatein` varchar(255) NOT NULL DEFAULT 'images/stories/guru/certificates',
			  `certificatein1` varchar(255) NOT NULL DEFAULT 'images/stories/guru/certificates',
			  `fromname` varchar(255) DEFAULT NULL,
			  `fromemail` varchar(255) DEFAULT NULL,
			  `regemail` varchar(255) DEFAULT NULL,
			  `orderemail` varchar(255) DEFAULT NULL,
			  `ctgpage` text,
			  `st_ctgpage` text NOT NULL,
			  `ctgspage` text,
			  `st_ctgspage` text NOT NULL,
			  `psgspage` text NOT NULL,
			  `st_psgspage` text NOT NULL,
			  `psgpage` text NOT NULL,
			  `st_psgpage` text NOT NULL,
			  `authorspage` text NOT NULL,
			  `st_authorspage` text NOT NULL,
			  `authorpage` text NOT NULL,
			  `st_authorpage` text NOT NULL,
			  `video_display` tinyint(1) NOT NULL,
			  `audio_display` tinyint(1) NOT NULL,
			  `content_selling` text NOT NULL,
			  `open_target` int(1) NOT NULL DEFAULT '0',
			  `st_donecolor` varchar(10) NOT NULL,
			  `st_notdonecolor` varchar(10) NOT NULL,
			  `st_txtcolor` varchar(10) NOT NULL,
			  `st_width` varchar(10) NOT NULL,
			  `st_height` varchar(10) NOT NULL,
			  `progress_bar` tinyint(4) NOT NULL,
			  `lesson_window_size` varchar(255) NOT NULL,
			  `default_video_size` varchar(255) NOT NULL,
			  `lesson_window_size_back` varchar(255) NOT NULL,
			  `last_check_date` datetime NOT NULL,
			  `hour_format` int(11) NOT NULL,
			  `back_size_type` int(3) NOT NULL,
			  `notification` int(2) NOT NULL,
			  `show_bradcrumbs` tinyint(4) NOT NULL DEFAULT '0',
			  `show_powerd` tinyint(4) NOT NULL DEFAULT '1',
			  `qct_alignment` tinyint(4) NOT NULL DEFAULT '1',
			  `qct_border_color` varchar(10) NOT NULL DEFAULT 'cccccc',
			  `qct_minsec` varchar(10) NOT NULL DEFAULT 'cccccc',
			  `qct_title_color` varchar(10) NOT NULL DEFAULT 'FFFFFF',
			  `qct_bg_color` varchar(10) NOT NULL DEFAULT 'f7f7f7',
			  `qct_font` text NOT NULL,
			  `qct_width` varchar(10) NOT NULL DEFAULT '200',
			  `qct_height` varchar(10) NOT NULL DEFAULT '60',
			  `qct_font_nb` varchar(10) NOT NULL DEFAULT '22',
			  `qct_font_words` varchar(10) NOT NULL DEFAULT '14',
			  `currencypos` tinyint(4) NOT NULL DEFAULT '0',
			  `guru_ignore_ijseo` tinyint(4) NOT NULL DEFAULT '0',
			  `course_lesson_release` tinyint(4) NOT NULL DEFAULT '0',
			  `student_group` int(10) NOT NULL DEFAULT '2',
			  `guru_turnoffjq` tinyint(4) NOT NULL DEFAULT '1',
			  `show_bootstrap` tinyint(4) NOT NULL DEFAULT '0',
			  `guru_turnoffbootstrap` tinyint(4) NOT NULL DEFAULT '1',
			  `gurujomsocialregstudent` tinyint(4) NOT NULL DEFAULT '0',
			  `gurujomsocialregteacher` tinyint(4) NOT NULL DEFAULT '0',
			  `gurujomsocialprofilestudent` tinyint(4) NOT NULL DEFAULT '0',
			  `gurujomsocialprofileteacher` tinyint(4) NOT NULL DEFAULT '0',
			  `gurujomsocialregstudentmprof` tinyint(4) NOT NULL,
  			  `gurujomsocialregteachermprof` tinyint(4) NOT NULL,
			  `installed_plugin_user` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `template_emails` text NOT NULL,
			  `terms_cond_student` int(3) NOT NULL DEFAULT '0',
			  `terms_cond_teacher` int(3) NOT NULL DEFAULT '0',
			  `terms_cond_student_content` text,
			  `terms_cond_teacher_content` text,
			  `course_is_free_show` tinyint(2) NOT NULL DEFAULT '0',
			  `admin_email` varchar(255) NOT NULL DEFAULT '".$default_email."',
			  `invoice_issued_by` text,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_currencies` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `plugname` varchar(30) NOT NULL DEFAULT '',
			  `currency_name` varchar(20) NOT NULL DEFAULT '',
			  `currency_full` varchar(50) NOT NULL DEFAULT '',
			  `sign` varchar(10) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_customer` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `company` varchar(100) NOT NULL DEFAULT '',
				  `firstname` varchar(50) NOT NULL DEFAULT '',
				  `lastname` varchar(50) NOT NULL DEFAULT '',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_days` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `pid` int(11) NOT NULL,
			  `title` varchar(255) DEFAULT NULL,
			  `alias` varchar(255) NOT NULL,
			  `description` text,
			  `image` varchar(255) DEFAULT NULL,
			  `published` tinyint(1) NOT NULL DEFAULT '1',
			  `startpublish` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `endpublish` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `metatitle` varchar(255) DEFAULT NULL,
			  `metakwd` varchar(255) DEFAULT NULL,
			  `metadesc` text,
			  `afterfinish` tinyint(1) NOT NULL DEFAULT '1',
			  `url` varchar(255) DEFAULT NULL,
			  `pagetitle` varchar(255) DEFAULT NULL,
			  `pagecontent` text,
			  `ordering` int(3) NOT NULL DEFAULT '0',
			  `locked` tinyint(1) NOT NULL DEFAULT '0',
			  `media_id` int(9) NOT NULL,
			  `access` int(3) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_emails` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `description` text,
			  `type` varchar(255) DEFAULT NULL,
			  `trigger` varchar(255) DEFAULT NULL,
			  `sendtime` tinyint(2) DEFAULT NULL,
			  `sendday` tinyint(2) DEFAULT NULL,
			  `reminder` varchar(255) DEFAULT NULL,
			  `published` tinyint(2) NOT NULL DEFAULT '0',
			  `subject` varchar(255) DEFAULT NULL,
			  `body` text,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_emails_pending` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `sending_time` int(11) NOT NULL,
			  `mail_id` int(11) NOT NULL,
			  `mail_subj` varchar(255) NOT NULL,
			  `mail_body` text NOT NULL,
			  `user_id` int(11) NOT NULL,
			  `pid` int(11) NOT NULL,
			  `type` enum('T','R') NOT NULL,
			  `send` tinyint(1) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_emails_refr_time` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `last_trigger_time` int(11) NOT NULL,
			  `last_reminder_time` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_jump` (
		  `id` int(15) NOT NULL AUTO_INCREMENT,
		  `button` int(2) NOT NULL,
		  `text` varchar(255) NOT NULL,
		  `jump_step` int(15) NOT NULL,
		  `module_id1` int(10) NOT NULL,
		  `type_selected` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_kunena_courseslinkage` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `idcourse` int(11) DEFAULT NULL,
		  `coursename` varchar(255) DEFAULT NULL,
		  `catidkunena` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_kunena_forum` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `forumboardcourse` tinyint(4) NOT NULL DEFAULT '0',
		  `forumboardlesson` tinyint(4) NOT NULL DEFAULT '0',
		  `forumboardteacher` tinyint(4) NOT NULL DEFAULT '0',
		  `deleted_boards` tinyint(4) NOT NULL DEFAULT '0',
		  `allow_stud` tinyint(4) NOT NULL DEFAULT '0',
		  `allow_edit` tinyint(4) NOT NULL DEFAULT '0',
		  `allow_delete` tinyint(4) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_kunena_lessonslinkage` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `idlesson` int(11) DEFAULT NULL,
		  `lessonname` varchar(255) DEFAULT NULL,
		  `catidkunena` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_media` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(255) DEFAULT NULL,
			  `instructions` text,
			  `type` varchar(10) DEFAULT NULL,
			  `source` varchar(5) DEFAULT NULL,
			  `uploaded` enum('0','1') NOT NULL DEFAULT '0',
			  `code` text,
			  `url` varchar(255) DEFAULT NULL,
			  `local` varchar(255) DEFAULT NULL,
			  `width` int(11) NOT NULL DEFAULT '32',
			  `height` int(11) NOT NULL DEFAULT '32',
			  `published` tinyint(1) NOT NULL DEFAULT '1',
			  `option_video_size` int(3) NOT NULL,
			  `category_id` int(10) NOT NULL,
			  `auto_play` int(3) NOT NULL,
			  `show_instruction` int(3) NOT NULL,
			  `hide_name` int(3) NOT NULL DEFAULT '1',
			  `author` int(11) NOT NULL,
			  `image`	text,
			  `description` text,
			  `uploaded_tab` int(3) NOT NULL DEFAULT '-1',
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_mediarel` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `type` varchar(5) DEFAULT NULL,
		  `type_id` int(11) DEFAULT NULL,
		  `media_id` int(11) DEFAULT NULL,
		  `mainmedia` tinyint(1) NOT NULL DEFAULT '0',
		  `text_no` int(4) NOT NULL DEFAULT '0',
		  `layout` tinyint(3) NOT NULL DEFAULT '0',
		  `access` int(3) NOT NULL,
		  `order` int(100) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_media_categories` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) DEFAULT NULL,
		  `parent_id` int(11) NOT NULL,
		  `child_id` int(11) NOT NULL,
		  `description` text,
		  `metatitle` varchar(255) DEFAULT NULL,
		  `metakey` varchar(255) DEFAULT NULL,
		  `metadesc` varchar(255) DEFAULT NULL,
		  `published` tinyint(1) NOT NULL DEFAULT '1',
		  `user_id` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_media_templay` (
		  `ip` bigint(20) NOT NULL,
		  `scr_id` int(8) NOT NULL,
		  `tmp_time` datetime NOT NULL,
		  `db_lay` int(8) NOT NULL,
		  `db_med` varchar(150) NOT NULL,
		  `db_text` varchar(150) NOT NULL,
		  PRIMARY KEY (`ip`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_mycertificates` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `course_id` int(11) NOT NULL,
		  `author_id` int(11) NOT NULL,
		  `user_id` int(11) NOT NULL,
		  `emailcert` tinyint(4) NOT NULL DEFAULT '0',
		  `datecertificate` datetime NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_order` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `userid` int(11) NOT NULL DEFAULT '0',
		  `order_date` datetime NOT NULL,
		  `courses` text NOT NULL,
		  `status` varchar(10) NOT NULL,
		  `amount` float NOT NULL DEFAULT '0',
		  `amount_paid` float NOT NULL DEFAULT '0',
		  `processor` varchar(100) NOT NULL,
		  `number_of_licenses` int(11) NOT NULL,
		  `currency` varchar(10) NOT NULL,
		  `promocodeid` varchar(255) NOT NULL,
		  `published` int(11) NOT NULL,
		  `form` text NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_plugins` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(40) NOT NULL DEFAULT '',
		  `classname` varchar(40) NOT NULL DEFAULT '',
		  `value` text NOT NULL,
		  `filename` varchar(40) NOT NULL DEFAULT '',
		  `type` varchar(10) NOT NULL DEFAULT 'payment',
		  `published` int(11) NOT NULL DEFAULT '0',
		  `def` varchar(30) NOT NULL DEFAULT '',
		  `sandbox` int(11) NOT NULL DEFAULT '0',
		  `reqhttps` int(11) NOT NULL DEFAULT '0',
		  `display_name` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_plugin_settings` (
		  `pluginid` int(11) NOT NULL DEFAULT '0',
		  `setting` varchar(200) NOT NULL DEFAULT '',
		  `description` text NOT NULL,
		  `value` text NOT NULL,
		  KEY `pluginid` (`pluginid`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_program` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `catid` int(11) DEFAULT NULL,
		  `name` varchar(255) DEFAULT NULL,
		  `alias` varchar(255) NOT NULL,
		  `description` text,
		  `introtext` text,
		  `image` varchar(255) DEFAULT NULL,
		  `image_avatar` varchar(255) DEFAULT NULL,
		  `emails` varchar(255) DEFAULT NULL,
		  `published` tinyint(1) NOT NULL DEFAULT '1',
		  `startpublish` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `endpublish` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `metatitle` varchar(255) DEFAULT NULL,
		  `metakwd` varchar(255) DEFAULT NULL,
		  `metadesc` text,
		  `ordering` int(5) NOT NULL,
		  `pre_req` text,
		  `pre_req_books` text,
		  `reqmts` text,
		  `author` int(3) NOT NULL,
		  `level` int(3) NOT NULL,
		  `priceformat` enum('1','2','3','4','5') NOT NULL DEFAULT '1',
		  `skip_module` int(3) NOT NULL,
		  `chb_free_courses` varchar(4) NOT NULL,
		  `step_access_courses` int(4) DEFAULT NULL,
		  `selected_course` varchar(255) DEFAULT NULL,
		  `course_type` tinyint(4) NOT NULL DEFAULT '0',
		  `lesson_release` tinyint(4) NOT NULL DEFAULT '0',
		  `lessons_show` tinyint(4) NOT NULL DEFAULT '1',
		  `start_release` datetime NOT NULL,
		  `id_final_exam` int(11) NOT NULL,
		  `certificate_term` tinyint(4) NOT NULL DEFAULT '0',
		  `hasquiz` tinyint(4) NOT NULL DEFAULT '0',
		  `updated` tinyint(4) NOT NULL DEFAULT '0',
		  `certificate_course_msg` text NOT NULL,
		  `avg_certc` int(11) NOT NULL DEFAULT '70',
		  `status` 	int(3) NOT NULL DEFAULT '1',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_programstatus` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `userid` int(11) NOT NULL,
		  `pid` int(11) NOT NULL,
		  `days` text NOT NULL,
		  `tasks` text NOT NULL,
		  `startdate` datetime NOT NULL,
		  `enddate` datetime NOT NULL,
		  `status` enum('0','1','2','-1') NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_program_plans` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `product_id` int(11) NOT NULL,
		  `plan_id` int(11) NOT NULL,
		  `price` float NOT NULL,
		  `default` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_program_reminders` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `product_id` int(11) NOT NULL,
		  `emailreminder_id` int(11) NOT NULL,
		  `send` enum('0','1') NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_program_renewals` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `product_id` int(11) NOT NULL,
		  `plan_id` int(11) NOT NULL,
		  `price` float NOT NULL,
		  `default` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_promos` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `title` varchar(200) NOT NULL DEFAULT '',
		  `code` varchar(100) NOT NULL DEFAULT '',
		  `codelimit` int(11) NOT NULL DEFAULT '0',
		  `codeused` int(11) NOT NULL DEFAULT '0',
		  `discount` float NOT NULL DEFAULT '0',
		  `codestart` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `codeend` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `forexisting` int(11) NOT NULL DEFAULT '0',
		  `published` int(11) NOT NULL DEFAULT '0',
		  `typediscount` tinyint(2) NOT NULL DEFAULT '0',
		  `courses_ids` text,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `code` (`code`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_questions` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `qid` int(11) DEFAULT NULL,
		  `text` text,
		  `a1` text,
		  `a2` text,
		  `a3` varchar(255) DEFAULT NULL,
		  `a4` varchar(255) DEFAULT NULL,
		  `a5` varchar(255) DEFAULT NULL,
		  `a6` varchar(255) DEFAULT NULL,
		  `a7` varchar(255) DEFAULT NULL,
		  `a8` varchar(255) DEFAULT NULL,
		  `a9` varchar(255) DEFAULT NULL,
		  `a10` varchar(255) DEFAULT NULL,
		  `answers` varchar(255) DEFAULT NULL,
		  `reorder` int(4) NOT NULL DEFAULT '0',
		  `published` tinyint(4) NOT NULL DEFAULT '1',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_quiz` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) DEFAULT NULL,
		  `description` text,
		  `image` varchar(255) DEFAULT NULL,
		  `published` tinyint(1) NOT NULL DEFAULT '1',
		  `startpublish` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `endpublish` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `ordering` int(10) NOT NULL DEFAULT '0',
		  `max_score` int(20) NOT NULL DEFAULT '70',
		  `pbl_max_score` tinyint(4) NOT NULL DEFAULT '0',
		  `time_quiz_taken` int(20) NOT NULL DEFAULT '1',
		  `show_nb_quiz_taken` tinyint(4) NOT NULL DEFAULT '0',
		  `final_quiz` tinyint(4) NOT NULL DEFAULT '1',
		  `nb_quiz_select_up` int(11) NOT NULL DEFAULT '10',
		  `show_nb_quiz_select_up` tinyint(4) NOT NULL DEFAULT '0',
		  `limit_time` int(11) NOT NULL DEFAULT '10',
		  `show_limit_time` tinyint(4) NOT NULL DEFAULT '0',
		  `show_countdown` tinyint(4) NOT NULL DEFAULT '0',
		  `limit_time_f` int(11) NOT NULL DEFAULT '1',
		  `show_finish_alert` tinyint(4) NOT NULL DEFAULT '1',
		  `is_final` tinyint(4) NOT NULL DEFAULT '0',
		  `student_failed_quiz` tinyint(4) NOT NULL DEFAULT '0',
		  `hide` tinyint(2) NOT NULL DEFAULT '0',
		  `author` int(11) NOT NULL,
		  `questions_per_page` int(11) NOT NULL DEFAULT '10', 
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_quizzes_final` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `quizzes_ids` varchar(255) NOT NULL,
		  `qid` int(11) NOT NULL,
		  `published` tinyint(1) NOT NULL DEFAULT '1',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_quiz_question_taken` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL DEFAULT '0',
		  `show_result_quiz_id` int(11) NOT NULL,
		  `answers_gived` varchar(255) NOT NULL,
		  `question_id` int(11) NOT NULL,
		  `question_order_no` int(10) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_quiz_taken` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL DEFAULT '0',
		  `quiz_id` int(11) NOT NULL,
		  `score_quiz` text,
		  `date_taken_quiz` datetime DEFAULT NULL,
		  `pid` int(11) NOT NULL,
		  `time_quiz_taken_per_user` int(20) NOT NULL DEFAULT '1',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_subplan` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) NOT NULL,
		  `term` tinyint(3) NOT NULL,
		  `period` varchar(255) NOT NULL,
		  `published` enum('0','1') NOT NULL DEFAULT '0',
		  `ordering` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_subremind` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) NOT NULL,
		  `term` tinyint(3) NOT NULL,
		  `subject` varchar(255) NOT NULL,
		  `body` text NOT NULL,
		  `published` enum('0','1') NOT NULL,
		  `ordering` int(11) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_task` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) DEFAULT NULL,
		  `alias` text NOT NULL,
		  `category` int(11) DEFAULT NULL,
		  `difficultylevel` varchar(255) DEFAULT NULL,
		  `points` int(11) DEFAULT NULL,
		  `image` varchar(255) DEFAULT NULL,
		  `published` tinyint(1) NOT NULL DEFAULT '1',
		  `startpublish` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `endpublish` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  `metatitle` varchar(255) DEFAULT NULL,
		  `metakwd` varchar(255) DEFAULT NULL,
		  `metadesc` text,
		  `time` int(11) NOT NULL DEFAULT '0',
		  `ordering` int(3) NOT NULL DEFAULT '0',
		  `step_access` int(3) NOT NULL,
		  `final_lesson` tinyint(2) NOT NULL DEFAULT '0',
		  `forum_kunena_generatedt` tinyint(4) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_taskcategory` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) DEFAULT NULL,
		  `published` tinyint(1) NOT NULL DEFAULT '1',
		  `description` text,
		  `image` varchar(255) DEFAULT NULL,
		  `listorder` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_taskcategoryrel` (
		  `parent_id` int(11) NOT NULL DEFAULT '1',
		  `child_id` int(11) NOT NULL DEFAULT '1'
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_task_kunenacomment` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `id_lesson` int(11) DEFAULT NULL,
		  `thread` int(11) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_commissions` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `default_commission` tinyint(4) NOT NULL DEFAULT '0',
			  `commission_plan` varchar(255) NOT NULL DEFAULT '',
			  `teacher_earnings` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_authors_commissions` (
			   `id` int(11) NOT NULL AUTO_INCREMENT,
			  `author_id` int(11) NOT NULL,
			  `course_id` int(11) NOT NULL,
			  `plan_id` int(11) NOT NULL,
			  `order_id` int(11) DEFAULT NULL,
			  `customer_id` int(11) NOT NULL,
			  `commission_id` int(11) DEFAULT NULL,
			  `price` int(11) NOT NULL,
			  `price_paid` int(11) NOT NULL,
			  `amount_paid_author` int(11) NOT NULL,
			  `promocode_id` int(11) NOT NULL,
			  `status_payment` text NOT NULL,
			  `payment_method` text NOT NULL,
			  `history` int(11) NOT NULL,
			  `data` datetime NOT NULL,
			  `currency` varchar(10) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_authors_commissions_history` (
			   `id` int(11) NOT NULL AUTO_INCREMENT,
			  `author_id` int(11) NOT NULL,
			  `total` float NOT NULL,
			  `order_auth_ids` text,
			  `data_paid` datetime NOT NULL,
			  `count_payments` int(11) NOT NULL,
			  `coin` varchar(10) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}

		
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_viewed_lesson` (
		  `id` int(3) NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) NOT NULL DEFAULT '0',
		  `lesson_id` text,
		  `module_id` text NOT NULL,
		  `completed` tinyint(2) DEFAULT NULL,
		  `date_completed` datetime NOT NULL,
		  `date_last_visit` datetime NOT NULL,
		  `pid` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		$sql = "CREATE TABLE IF NOT EXISTS `#__guru_jomsocialstream` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `userid` int(21) NOT NULL DEFAULT '0',
				  `params` text NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$database->setQuery($sql);
		if(!$database->query()){
			echo $database->getErrorMsg();
		}
		
		
		
		//END CREATE TABLES
		$mosConfig_absolute_path = JPATH_BASE; 
		$mosConfig_live_site     = JURI::base();
	
		$admin_path = $mosConfig_absolute_path."/administrator/components/com_guru/";
		$user_path	= $mosConfig_absolute_path."/components/com_guru/";
		//see if we have any settings in the settings table and add the default settings
		$sql = "SELECT `id` FROM `#__guru_config` limit 1";
		$database->setQuery($sql);
		$textafterreg = $database->loadColumn();
		
		if(intval($textafterreg) == 0){
			$config = new JConfig();
			$fromemail = $config->mailfrom;
			$fromname = $config->sitename;
			
			$ctgspage_array = array("ctgslayout" => "1", "ctgscols" => "2", "ctgs_image_size" => "100", "ctgs_image_size_type" => "0", "ctgs_image_alignment" => "0", "ctgs_wrap_image" => "1", "ctgs_description_length" => "120", "ctgs_description_type" => "0", "ctgs_description_alignment"  => "0", "ctgs_read_more" => "1", "ctgs_read_more_align" => "0", "ctgs_show_empty_catgs" => "1", "ctgs_description_mode" => "0");
			
			$st_ctgspage_array = array("ctgs_page_title" => "title_guru", "ctgs_categ_name" => "name_guru", "ctgs_image" => "image_guru", "ctgs_description" =>  "description_guru", "ctgs_st_read_more" => "readon");
			
			$ctgpage_array = array("ctg_image_size" => "80", "ctg_image_size_type" => "1", "ctg_image_alignment" => "0", "ctg_wrap_image" => "1", "ctg_description_length" => "250", "ctg_description_type" => "0", "ctg_description_alignment" => "0", "ctg_description_mode"=>"0");
			
			$st_ctgpage_array = array("ctg_name" => "title_guru", "ctg_image" => "image_guru", "ctg_description" => "description_guru_bigger", "ctg_sub_title" => "sub_title_guru");
			
			$psgspage_array = array("courseslayout" => "1", "coursescols" => "2", "courses_image_size" => "100", "courses_image_size_type" => "0", "courses_image_alignment" => "0", "courses_wrap_image" => "1", "courses_description_length" => "120", "courses_description_type" => "0", "courses_description_alignment" => "0", "courses_read_more" => "1", "courses_read_more_align" => "0", "courses_description_mode"=>"0");
			
			$st_psgspage_array = array("courses_page_title" => "title_guru", "courses_name" => "name_guru", "courses_image" => "image_guru", "courses_description" => "description_guru", "courses_st_read_more" => "readon");
			
			$psgpage_array = array("course_image_size" => "150", "course_image_size_type" => "0", "course_image_alignment" => "0", "course_wrap_image" => "1", "course_author_name_show" => "0", "course_released_date" => "0", "course_level" => "0", "course_price" => "1", "course_price_type" => "0", "course_table_contents" => "0", "course_description_show" => "0", "course_tab_price" => "0", "course_author" => "0", "course_requirements" => "0", "course_buy_button" => "0", "course_buy_button_location" => "2", "show_course_image" => "0", "show_all_cloase_all" => "0", "show_course_studentamount"=>"0");
			
			$st_psgpage_array = array("course_name" => "title_guru", "course_image" => "image_guru", "course_top_field_name" => "field_name_guru", "course_top_field_value" => "field_value_guru", "course_tabs_module_name" => "name_guru", "course_tabs_step_name" => "step_name_guru", "course_description" => "description_guru", "course_price_field_name" => "field_name_guru", "course_price_field_value" => "field_value_guru", "course_author_name" => "title_guru", "course_author_bio" => "description_guru", "course_author_image" => "image_guru", "course_req_field_name" => "field_name_guru", "course_req_field_value" => "field_value_guru", "course_other_button" => "guru_buynow", "course_other_background"=>"buy_background");
			
			$authorspage_array = array("authorslayout" => "1", "authorscols" => "2", "authors_image_size" => "75", "authors_image_size_type" => "0", "authors_image_alignment" => "0", "authors_wrap_image" => "1", "authors_description_length" => "300", "authors_description_type" => "0", "authors_description_alignment" => "0", "authors_read_more" => "0", "authors_read_more_align" => "1", "authors_description_mode"=>"0");
			$st_authorspage_array = array("authors_page_title" => "title_guru", "authors_name" => "name_guru", "authors_image" => "image_guru", "authors_description" => "description_guru", "authors_st_read_more" => "readon");
			
			$authorpage_array = array("author_image_size" => "150", "author_image_size_type" => "0", "author_image_alignment" => "0", "author_wrap_image" => "0", "author_description_length" => "1000", "author_description_type" => "0", "author_description_alignment" => "0");
			$st_authorpage_array = array("author_name" => "name_guru", "author_image" => "image_guru", "author_description" => "description_guru", "author_st_read_more" => "readon", "teacher_aprove"=>"1","teacher_group"=>"2","teacher_add_media"=>"0","teacher_edit_media"=>"0","teacher_add_courses"=>"0","teacher_edit_courses"=>"0","teacher_add_quizzesfe"=>"0","teacher_edit_quizzesfe"=>"0","teacher_add_students"=>"0","teacher_edit_students"=>"", "teacher_approve_courses"=>"1");
			
			
			$templates_emails_array = array("approve_subject"=>"Approved your course [COURSE_NAME]","approve_body"=>"<p>Dear [AUTHOR_NAME],</p>\r\n<p>We are happy to inform you that we've approved your course [COURSE_NAME]!</p>\r\n<p>[SITE_NAME] admin</p>","unapprove_subject"=>"Unapproved your course [COURSE_NAME]","unapprove_body"=>"<p>Dear [AUTHOR_NAME],</p>\r\n<p>We're are sorry to inform you that your course [COURSE_NAME] was unapproved.</p>\r\n<p>[SITE_NAME] admin</p>","ask_approve_subject"=>"Approve [COURSE_NAME] course","ask_approve_body"=>"<p>Dear admin,</p>\r\n<p>New course was submitted by:[AUTHOR_NAME]</p>\r\n<p>Course name: [COURSE_NAME]</p>\r\n<p>**********************</p>\r\n<p>Approve this course: [COURSE_APPROVE_URL]</p>\r\n<p>Thank you!</p>","approved_teacher_subject"=>"Approved as teacher","approved_teacher_body"=>"<p>Dear <span class=\"error\">[AUTHOR_NAME]</span>,</p>\r\n<p>Thank you for applying to be a teacher at <span class=\"error\">[SITE_NAME]</span></p>\r\n<p>Your application has been approved. You may login to our site and submit your courses.</p>\r\n<p>Best regards,</p>\r\n<p><span class=\"error\">[SITE_NAME]</span></p>","pending_teacher_subject"=>"Registration in pending.","pending_teacher_body"=>"<p>Hello [AUTHOR_NAME],</p>\r\n<p>Thank you for registering at [SITE_NAME].</p>\r\n<p>Your Teacher Application is waiting admin approval, once that's done you'll get access to the Teacher Interface. You will be notified when you're Teacher Application is approved.</p>\r\n<p>In the meantime you are registered as any other user and may login to other parts of the [SITE_NAME] site using the following username and password:</p>\r\n<p>Username: [USERNAME]<br \/> Password: [PASSWORD]</p>", "ask_teacher_subject"=>"You have a new teacher application", "ask_teacher_body"=>"<p>Dear admin,</p>\r\n<p>You have a new teacher application:</p>\r\n<p>Name: [AUTHOR_NAME]</p>\r\n<p>Thank you!</p>", "new_teacher_subject"=>"New teacher has registered", "new_teacher_body"=>"<p>Dear admin,</p>\r\n<p>New teacher has registered:</p>\r\n<p>Name: [AUTHOR_NAME]</p>\r\n<p>Thank you!</p>");
			
			$ctgpage = json_encode($ctgpage_array);
			$st_ctgpage = json_encode($st_ctgpage_array);
			$ctgspage = json_encode($ctgspage_array);
			$st_ctgspage = json_encode($st_ctgspage_array);
			$psgspage = json_encode($psgspage_array);
			$st_psgspage = json_encode($st_psgspage_array);
			$psgpage = json_encode($psgpage_array);
			$st_psgpage = json_encode($st_psgpage_array);
			$authorspage = json_encode($authorspage_array);
			$st_authorspage = json_encode($st_authorspage_array);
			$authorpage = json_encode($authorpage_array);
			$st_authorpage = json_encode($st_authorpage_array);
			$templates_emails  = json_encode($templates_emails_array);
	
			$sql = "INSERT INTO `#__guru_config` (`id`, `currency`, `datetype`, `dificulty`, `influence`, `display_tasks`, `groupe_tasks`, `display_media`, `show_unpubl`, `btnback`, `btnhome`, `btnnext`, `dofirst`, `imagesin`, `videoin`, `audioin`, `docsin`, `filesin`, `fromname`, `fromemail`, `regemail`, `orderemail`, `ctgpage`, `st_ctgpage`, `ctgspage`, `st_ctgspage`, `psgspage`, `st_psgspage`, `psgpage`, `st_psgpage`, `authorspage`, `st_authorspage`, `authorpage`, `st_authorpage`, `video_display`, `audio_display`, `content_selling`, `open_target`, `st_donecolor`, `st_notdonecolor`, `st_txtcolor`, `st_width`, `st_height`, `progress_bar`, `lesson_window_size`, `default_video_size`, `lesson_window_size_back`, `last_check_date`, `hour_format`, `back_size_type`, `notification`, `show_bradcrumbs`,`show_powerd`, `currencypos`, `guru_ignore_ijseo`,`course_lesson_release`,`student_group`,`guru_turnoffjq`,`guru_turnoffbootstrap`, `gurujomsocialregstudent`, `gurujomsocialregteacher`, `gurujomsocialprofilestudent`, `gurujomsocialprofileteacher`, `gurujomsocialregstudentmprof`, `gurujomsocialregteachermprof`, `installed_plugin_user`,`template_emails`,`terms_cond_student`, `terms_cond_teacher`, `terms_cond_student_content`,`terms_cond_teacher_content`,`course_is_free_show`,`admin_email`) 
				
				VALUES (1, 'USD', 'Y-m-d H:i:s', 'Hard', 0, 1, 1, 1, 3, 0, 0, 0, 0, 'images/stories/guru', 'media/videos', 'media/audio', 'media/documents', 'media/files', '".$fromname."', '".$fromemail."', '0', '0', '".$ctgpage."', '".$st_ctgpage."', '".$ctgspage."', '".$st_ctgspage."', '".$psgspage."', '".$st_psgspage."', '".$psgpage."', '".$st_psgpage."', '".$authorspage."', '".$st_authorspage."', '".$authorpage."', '".$st_authorpage."', 0, 0, '<h2>You need to be a subscriber to access this lesson/file.</h2><p>Please select a subscription plan below and click Continue </p>', 1, '#66FF33', '#FF3399', '#FF9900', '200', '20', 0, '580x750', '400x670', '550x935', '', 12, 1 , 0, 0, 1, 0,0,0,2,1,1, 0,0,0,0,'','', '0000-00-00 00:00:00','".addslashes($templates_emails)."', 0, 0,'', '', 0, '".$default_email."' )";
			
			$database->setQuery($sql);
			if(!$database->query()){
				echo $database->getErrorMsg();
			}
			
			if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

			//create media folders
			if(!JFolder::exists(JPATH_SITE."/images/stories/guru")){
				JFolder::create(JPATH_SITE."/images/stories/guru");
			}
			
			if(!JFolder::exists(JPATH_SITE."/images/stories/guru/authors")){
				JFolder::create(JPATH_SITE."/images/stories/guru/authors");
			}
			
			if(!JFolder::exists(JPATH_SITE."/images/stories/guru/categories")){
				JFolder::create(JPATH_SITE."/images/stories/guru/categories");
			}
			
			if(!JFolder::exists(JPATH_SITE."/images/stories/guru/courses")){
				JFolder::create(JPATH_SITE."/images/stories/guru/courses");
			}
			if(!JFolder::exists(JPATH_SITE."/images/stories/guru/certificates")){
				JFolder::create(JPATH_SITE."/images/stories/guru/certificates");
			}
			
			if(!JFolder::exists(JPATH_SITE."/images/stories/guru/media")){
				JFolder::create(JPATH_SITE."/images/stories/guru/media");
			}
			
			if(!JFolder::exists(JPATH_SITE."/media/videos")){
				JFolder::create(JPATH_SITE."/media/videos");
			}
			
			if(!JFolder::exists(JPATH_SITE."/media/audio")){
				JFolder::create(JPATH_SITE."/media/audio");
			}
			
			if(!JFolder::exists(JPATH_SITE."/media/documents")){
				JFolder::create(JPATH_SITE."/media/documents");
			}
			
			if(!JFolder::exists(JPATH_SITE."/media/files")){
				JFolder::create(JPATH_SITE."/media/files");
			}
		
		}
	
		
		$sql = "select count(*) from #__guru_certificates";
		$database->setQuery($sql);
		$result = $database->loadColumn();
		if($result["0"] == "0"){
			$sql = "INSERT INTO `#__guru_certificates` (`id`, `general_settings`, `design_background`, `design_background_color`, `design_text_color`, `avg_cert`, `templates1`, `templates2`, `templates3`,`templates4`, `subjectt3`, `subjectt4`, `font_certificate`) VALUES ('1', '1', 'images/stories/guru/certificates/thumbs/Cert-blue-color-back-no-seal.png', 'ACE0F6', '333333', '70', '<p align=\"center\">Student Name: [STUDENT_FIRST_NAME] [STUDENT_LAST_NAME]<br />Certificate ID: [CERTIFICATE_ID]<br />Course name: [COURSE_NAME]<br />Completion Date: [COMPLETION_DATE]<br />Site name: [SITENAME] <br />Site URL: [SITEURL]<br />Teacher: [AUTHOR_NAME]</p>', '<p>Certificate of Satisfactory Completion<br /><br />[COURSE_NAME] Awarded to [STUDENT_FIRST_NAME] [STUDENT_LAST_NAME] on [COMPLETION_DATE]<br /><br />This is a duplicate of the official Certificate of Completion on file for [STUDENT_FIRST_NAME] [STUDENT_LAST_NAME], who successfully and satisfactorily completed all requirements of the [SITENAME] course [COURSE_NAME] with [AUTHOR_NAME] on [COMPLETION_DATE].<br /><br /></p>', '<p>Dear [STUDENT_FIRST_NAME],</p><p>&nbsp;</p><p>Your certificate for course[COURSE_NAME] that you completed on [COMPLETION_DATE], is now available for you to download and share.</p><p>Please visit our site to get your certificate:</p><p>&nbsp;&nbsp; [SITENAME] <br />&nbsp;</p>','<p>Hello,</p><p>Message from  <span>[STUDENT_FIRST_NAME]</span> <span>[STUDENT_LAST_NAME]</span>:</p><p><span>[CERT_MESSAGE]</span></p><p>Please click on the URL below to see the certificate</p><p><span>[CERT_URL]</span></p><p><span>[SITENAME]</span></p>', 'Certificate for [STUDENT_FIRST_NAME] [STUDENT_LAST_NAME] from [SITENAME]', 'Certificate for [STUDENT_FIRST_NAME] [STUDENT_LAST_NAME] from [SITENAME]', 'Arial');";
			$database->setQuery($sql);
			$database->query();		
		}
		
			
		if(!JFolder::exists(JPATH_SITE."/images/stories/guru/certificates/thumbs")){
				JFolder::create(JPATH_SITE."/images/stories/guru/certificates/thumbs");
		}
		
		$component_dir = JPATH_SITE.'/administrator/components/com_guru';
		copy($component_dir."/images/for_install/certificates/viewed.png", JPATH_SITE."/images/stories/guru/certificates/viewed.png");
	
		copy($component_dir."/images/for_install/certificates/link.png", JPATH_SITE."/images/stories/guru/certificates/link.png");
	
		copy($component_dir."/images/for_install/certificates/email.png", JPATH_SITE."/images/stories/guru/certificates/email.png");
		
		copy($component_dir."/images/for_install/certificates/download.png", JPATH_SITE."/images/stories/guru/certificates/download.png");
		
		copy($component_dir."/images/for_install/certificates/Cert-blue-color-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/Cert-blue-color-back-no-seal.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert-blue-color-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert-blue-color-back-no-seal.png");
		
		copy($component_dir."/images/for_install/certificates/Cert-Blue-seal-color-back.png", JPATH_SITE."/images/stories/guru/certificates/Cert-Blue-seal-color-back.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert-Blue-seal-color-back.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert-Blue-seal-color-back.png");
		
		copy($component_dir."/images/for_install/certificates/Cert-blue-white-back.png", JPATH_SITE."/images/stories/guru/certificates/Cert-blue-white-back.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert-blue-white-back.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert-blue-white-back.png");
		
		copy($component_dir."/images/for_install/certificates/Cert-blue-white-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/Cert-blue-white-back-no-seal.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert-blue-white-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert-blue-white-back-no-seal.png");
		
		copy($component_dir."/images/for_install/certificates/Cert-green-seal-color-back.png", JPATH_SITE."/images/stories/guru/certificates/Cert-green-seal-color-back.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert-green-seal-color-back.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert-green-seal-color-back.png");
		
		copy($component_dir."/images/for_install/certificates/Cert-green-seal-color-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/Cert-green-seal-color-back-no-seal.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert-green-seal-color-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert-green-seal-color-back-no-seal.png");
		
		copy($component_dir."/images/for_install/certificates/Cert-green-seal-white-back.png", JPATH_SITE."/images/stories/guru/certificates/Cert-green-seal-white-back.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert-green-seal-white-back.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert-green-seal-white-back.png");
		
		copy($component_dir."/images/for_install/certificates/Cert-green-white--back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/Cert-green-white--back-no-seal.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert-green-white--back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert-green-white--back-no-seal.png");
		
		copy($component_dir."/images/for_install/certificates/Cert-orange--color-back.png", JPATH_SITE."/images/stories/guru/certificates/Cert-orange--color-back.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert-orange--color-back.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert-orange--color-back.png");
		
		copy($component_dir."/images/for_install/certificates/Cert-orange--color-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/Cert-orange--color-back-no-seal.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert-orange--color-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert-orange--color-back-no-seal.png");
		
		copy($component_dir."/images/for_install/certificates/Cert-orange-white-back.png", JPATH_SITE."/images/stories/guru/certificates/Cert-orange-white-back.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert-orange-white-back.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert-orange-white-back.png");
		
		copy($component_dir."/images/for_install/certificates/Cert-orange-white-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/Cert-orange-white-back-no-seal.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert-orange-white-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert-orange-white-back-no-seal.png");
		
		copy($component_dir."/images/for_install/certificates/Cert--Purple-color-back.png", JPATH_SITE."/images/stories/guru/certificates/Cert--Purple-color-back.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert--Purple-color-back.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert--Purple-color-back.png");
		
		copy($component_dir."/images/for_install/certificates/Cert--Purple-color-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/Cert--Purple-color-back-no-seal.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert--Purple-color-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert--Purple-color-back-no-seal.png");
		
		copy($component_dir."/images/for_install/certificates/Cert--Purple-white-back.png", JPATH_SITE."/images/stories/guru/certificates/Cert--Purple-white-back.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert--Purple-white-back.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert--Purple-white-back.png");
		
		copy($component_dir."/images/for_install/certificates/Cert--Purple-white-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/Cert--Purple-white-back-no-seal.png");
		copy($component_dir."/images/for_install/certificates/thumbs/Cert--Purple-white-back-no-seal.png", JPATH_SITE."/images/stories/guru/certificates/thumbs/Cert--Purple-white-back-no-seal.png");
		
	
		$sql = "select count(*) from #__guru_kunena_forum";
		$database->setQuery($sql);
		$result = $database->loadColumn();
		if($result["0"] == "0"){
			$sql = "INSERT INTO `#__guru_kunena_forum` (`forumboardcourse`, `forumboardlesson`, `forumboardteacher`, `deleted_boards`,`allow_stud`,`allow_edit`,`allow_delete`) VALUES (0, 0, 0, 0,0,0,0);";
			$database->setQuery($sql);
			$database->query();		
		}
		$sql = "select count(*) from #__guru_subplan";
		$database->setQuery($sql);
		$result = $database->loadColumn();
		if($result["0"] == "0"){
			$sql = "INSERT INTO `#__guru_subplan` (`id`, `name`, `term`, `period`, `published`, `ordering`) VALUES
						(1, 'Unlimited', 0, 'hours', '1', 5),
						(2, '1 Month', 1, 'months', '1', 1),
						(3, '3 Months', 3, 'months', '1', 2),
						(4, '6 Months', 6, 'months', '1', 4),
						(5, '1 Year', 1, 'years', '1', 3),
						(7, '1 hour', 1, 'hours', '1', 0);
					";
			$database->setQuery($sql);
			$database->query();		
		}
		$sql = "select count(*) from #__guru_commissions";
		$database->setQuery($sql);
		$result = $database->loadColumn();
		if($result["0"] == "0"){
			$sql = "INSERT INTO `#__guru_commissions` (`id`, `default_commission`, `commission_plan`, `teacher_earnings`) VALUES
					(1, 1, ' default', 70);
					";
			$database->setQuery($sql);
			$database->query();		
		}
			
		$sql = "SELECT count(*) FROM `#__guru_currencies`";
		$database->setQuery($sql);
		$database->query();
		$currency = $database->loadColumn();
		if(intval($currency["0"])<=0){
			$sql = "INSERT INTO `#__guru_currencies` (`id`, `plugname`, `currency_name`, `currency_full`) VALUES 
						(1, 'paypal', 'USD', 'U.S. Dollar'),
						(2, 'paypal', 'AUD', 'Australian Dollar'),
						(3, 'paypal', 'CAD', 'Canadian Dollar'),
						(4, 'paypal', 'CHF', 'Swiss Franc'),
						(5, 'paypal', 'CZK', 'Czech Koruna'),
						(6, 'paypal', 'DKK', 'Danish Krone'),
						(7, 'paypal', 'EUR', 'Euro'),
						(8, 'paypal', 'GBP', 'Pound Sterling'),
						(9, 'paypal', 'HKD', 'Hong Kong Dollar'),
						(10, 'paypal', 'HUF', 'Hungarian Forint'),
						(11, 'paypal', 'JPY', 'Japanese Yen'),
						(12, 'paypal', 'NOK', 'Norwegian Krone'),
						(13, 'paypal', 'NZD', 'New Zealand Dollar'),
						(14, 'paypal', 'PLN', 'Polish Zloty'),
						(15, 'paypal', 'SEK', 'Swedish Krona'),
						(16, 'paypal', 'SGD', 'Singapore Dollar'),
						(17, 'paypal', 'BRL', 'Brazilian Real'),
						(18, 'paypal', 'INR', 'Indian rupee'),
						(20, 'paypal', 'IDR', 'Indonesian Rupiah'),
						(21, 'paypal', 'MYR', 'Malaysian Ringgit'),
						(22, 'paypal', 'XOF', 'African CFA Franc'),
						(23, 'paypal', 'BGN', 'Bulgarian lev'),
						(24, 'paypal', 'VND', 'Vietnamese Dong'),
						(25, 'paypal', 'CNY', 'Chinese Yuan'),
						(26, 'paypal', 'IR', 'Iranian Rial'),
						(19, 'paypal', 'ZAR', 'South African Rand');";
			$database->setQuery($sql);
			$database->query();		
		}
		
		$sql = "select count(*) from `#__guru_subremind`";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadColumn();
		if($result["0"] == "0"){
			$sql = "INSERT INTO #__guru_subremind (`id`, `name`, `term`, `subject`, `body`, `published`, `ordering`) VALUES
					(1, 'Your subscription has expired', 0, 'Your subscription to [COURSE_NAME] has expired ', '<p>Dear [STUDENT_FIRST_NAME], <br />\r\n<br />\r\n Your [SUBSCRIPTION_TERM] subscription to [COURSE_NAME] has expired! <br />\r\n<br />\r\n Please click on the link below to renew it: <br />\r\n<br />\r\n [RENEW_URL]       <br />\r\n<br />\r\n Remember, you can always access your courses here: <br />\r\n<br />\r\n [MY_COURSES]<br />\r\n<br />\r\n Thank you! </p>', '1', 3),
					(2, 'Your subscription will expire in 1 day', 1, 'Your subscription to [COURSE_NAME] will expire in 1 day', '<p>Dear [STUDENT_FIRST_NAME], <br />\r\n<br />\r\n Your [SUBSCRIPTION_TERM] subscription to [COURSE_NAME] will expire tomorrow!<br />\r\n<br />\r\n Please click on the link below to renew it: <br />\r\n<br />\r\n [RENEW_URL]<br />\r\n<br />\r\n Remember, you can always access your courses here: <br />\r\n<br />\r\n [MY_COURSES]<br />\r\n<br />\r\n Thank you! </p>', '1', 2),
					(4, '3 days after expiration', 8, 'Subscription to [COURSE_NAME] has expired!', '<p>Dear [STUDENT_FIRST_NAME], <br />\r\n<br />\r\n Your [SUBSCRIPTION_TERM] subscription to [COURSE_NAME] has expired 3 days ago! <br />\r\n<br />\r\n Please click on the link below to renew it: <br />\r\n<br />\r\n [RENEW_URL]<br />\r\n<br />\r\n Remember, you can always access your courses here: <br />\r\n<br />\r\n [MY_COURSES]<br />\r\n<br />\r\n Thank you! </p>', '1', 1),
					(5, 'On purchase email', 11, 'Thank you for purchasing [COURSE_NAME] course', '<p>Dear [STUDENT_FIRST_NAME],<br />\r\n<br />\r\n Thank you for purchasing [SUBSCRIPTION_TERM] subscription to [COURSE_NAME] <br />\r\n<br />\r\n Your subscription will expire on:\r\n[EXPIRE_DATE]<br />\r\n<br />\r\n You can access your course here:<br />\r\n<br />\r\n [COURSE_URL] <br />\r\n<br />\r\n Best Regards,<br />\r\n<br />\r\n [SITENAME] </p>', '1', 0),
					(6, 'New Lesson', 12, 'New lesson: [LESSON_TITLE]', '<p>Dear [STUDENT_FIRST_NAME],<br />\r\n<br />\r\n A new lesson is available for [COURSE_NAME]: <br />\r\n<br />\r\n [LESSON_TITLE]<br />\r\n<br />\r\n Click below to access this lesson:<br />\r\n<br />\r\n [LESSON_URL] <br />\r\n<br />\r\n Best Regards,<br />\r\n<br />\r\n [SITENAME] </p>', '1', 4);";
			$database->setQuery($sql);
			$database->query();
		}
		if($result == "4"){
			$sql = "INSERT INTO #__guru_subremind (`id`, `name`, `term`, `subject`, `body`, `published`, `ordering`) VALUES
					(6, 'New Lesson', 12, 'New lesson: [LESSON_TITLE]', '<p>Dear [STUDENT_FIRST_NAME],<br />\r\n<br />\r\n A new lesson is available for [COURSE_NAME]: <br />\r\n<br />\r\n [LESSON_TITLE]<br />\r\n<br />\r\n Click below to access this lesson:<br />\r\n<br />\r\n [LESSON_URL] <br />\r\n<br />\r\n Best Regards,<br />\r\n<br />\r\n [SITENAME] </p>', '1', 4);";
			$database->setQuery($sql);
			$database->query();
		
		}
		$sql = "SHOW COLUMNS FROM #__guru_program";
		$database->setQuery($sql);
		$result = $database->loadColumn();
		if(isset($result)){
			if(!in_array("image_avatar", $result)){
				$sql = "ALTER TABLE `#__guru_program` ADD `image_avatar` varchar(255) DEFAULT NULL";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("status", $result)){
				$sql = "ALTER TABLE `#__guru_program` ADD `status` int(3) NOT NULL DEFAULT '1'";
				$database->setQuery($sql);
				$database->query();
			}
		}
		
		$sql = "SHOW COLUMNS FROM #__guru_config";
		$database->setQuery($sql);
		$result = $database->loadColumn();
		
		if(isset($result)){
			if(!in_array("gurujomsocialregstudent", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `gurujomsocialregstudent` tinyint(4) NOT NULL DEFAULT '0'";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("gurujomsocialregteacher", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `gurujomsocialregteacher` tinyint(4) NOT NULL DEFAULT '0'";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("gurujomsocialprofilestudent", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `gurujomsocialprofilestudent` tinyint(4) NOT NULL DEFAULT '0'";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("gurujomsocialprofileteacher", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `gurujomsocialprofileteacher` tinyint(4) NOT NULL DEFAULT '0'";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("gurujomsocialregstudentmprof", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `gurujomsocialregstudentmprof` tinyint(4) NOT NULL";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("gurujomsocialregteachermprof", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `gurujomsocialregteachermprof` tinyint(4) NOT NULL";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("installed_plugin_user", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `installed_plugin_user` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("invoice_issued_by", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `invoice_issued_by` text";
				$database->setQuery($sql);
				$database->query();
			}
			
			if(!in_array("template_emails", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `template_emails` text NOT NULL";
				$database->setQuery($sql);
				$database->query();
				
				$templates_emails_array = array("approve_subject"=>"Approved your course [COURSE_NAME]","approve_body"=>"<p>Dear [AUTHOR_NAME],</p>\r\n<p>We are happy to inform you that we've approved your course [COURSE_NAME]!</p>\r\n<p>[SITE_NAME] admin</p>","unapprove_subject"=>"Unapproved your course [COURSE_NAME]","unapprove_body"=>"<p>Dear [AUTHOR_NAME],</p>\r\n<p>We're are sorry to inform you that your course [COURSE_NAME] was unapproved.</p>\r\n<p>[SITE_NAME] admin</p>","ask_approve_subject"=>"Approve [COURSE_NAME] course","ask_approve_body"=>"<p>Dear admin,</p>\r\n<p>New course was submitted by:[AUTHOR_NAME]</p>\r\n<p>Course name: [COURSE_NAME]</p>\r\n<p>**********************</p>\r\n<p>Approve this course: [COURSE_APPROVE_URL]</p>\r\n<p>Thank you!</p>","approved_teacher_subject"=>"Approved as teacher","approved_teacher_body"=>"<p>Dear <span class=\"error\">[AUTHOR_NAME]</span>,</p>\r\n<p>Thank you for applying to be a teacher at <span class=\"error\">[SITE_NAME]</span></p>\r\n<p>Your application has been approved. You may login to our site and submit your courses.</p>\r\n<p>Best regards,</p>\r\n<p><span class=\"error\">[SITE_NAME]</span></p>","pending_teacher_subject"=>"Registration in pending.","pending_teacher_body"=>"<p>Hello [AUTHOR_NAME],</p>\r\n<p>Thank you for registering at [SITE_NAME].</p>\r\n<p>Your Teacher Application is waiting admin approval, once that's done you'll get access to the Teacher Interface. You will be notified when you're Teacher Application is approved.</p>\r\n<p>In the meantime you are registered as any other user and may login to other parts of the [SITE_NAME] site using the following username and password:</p>\r\n<p>Username: [USERNAME]<br \/> Password: [PASSWORD]</p>", "ask_teacher_subject"=>"You have a new teacher application", "ask_teacher_body"=>"<p>Dear admin,</p>\r\n<p>You have a new teacher application:</p>\r\n<p>Name: [AUTHOR_NAME]</p>\r\n<p>Thank you!</p>", "new_teacher_subject"=>"New teacher has registered", "new_teacher_body"=>"<p>Dear admin,</p>\r\n<p>New teacher has registered:</p>\r\n<p>Name: [AUTHOR_NAME]</p>\r\n<p>Thank you!</p>", "approve_order_subject"=>"Approved Order","approve_order_body"=>"<p>Hello [STUDENT_FIRST_NAME],</p>\r\n<p>Your [COURSE_NAME] order has been approved and you can access the course now.</p>\r\n<p>Login to the [SITE_NAME] to view it.</p>\r\n<p>Kindest regards,</p>\r\n<p>[SITE_NAME] administrators</p>", "pending_order_subject"=>"Pending Order", "pending_order_body"=>"<p>Hello,</p>\r\n<p>There is a pending order: <br /> Course name: [COURSE_NAME]<br /> Order number: [ORDER_NUMBER]<br /> Student name: [STUDENT_FIRST_NAME] [STUDENT_LAST_NAME] </p>\r\n<p>To approve the order, please login to the list of orders: [GURU_ORDER_LIST_URL] and approve it.</p>");
				$templates_emails  = json_encode($templates_emails_array);
				$sql = "UPDATE `#__guru_config` set `template_emails` ='".addslashes($templates_emails)."' WHERE id=1";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("terms_cond_student", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `terms_cond_student` int(3) NOT NULL DEFAULT '0'";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("terms_cond_teacher", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `terms_cond_teacher` int(3) NOT NULL DEFAULT '0'";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("terms_cond_student_content", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `terms_cond_student_content` text NOT NULL";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("terms_cond_teacher_content", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `terms_cond_teacher_content` text NOT NULL";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("course_is_free_show", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `course_is_free_show` tinyint(2) NOT NULL DEFAULT '0'";
				$database->setQuery($sql);
				$database->query();
			}
				
			if(!in_array("admin_email", $result)){
				$sql = "ALTER TABLE `#__guru_config` ADD `admin_email` varchar(255) NOT NULL DEFAULT '".$default_email."'";
				$database->setQuery($sql);
				$database->query();
			}
		}
		
		$sql = "SHOW COLUMNS FROM #__guru_media_categories";
		$database->setQuery($sql);
		$result = $database->loadColumn();
		if(isset($result)){
			if(!in_array("user_id", $result)){
				$sql = "ALTER TABLE `#__guru_media_categories` ADD `user_id` int(11) NOT NULL";
				$database->setQuery($sql);
				$database->query();
			}
		}
		
		$sql = "SHOW COLUMNS FROM #__guru_media";
		$database->setQuery($sql);
		$result = $database->loadColumn();
		if(isset($result)){
			if(!in_array("hide_name", $result)){
				$sql = "ALTER TABLE `#__guru_media` ADD `hide_name` int(3) NOT NULL DEFAULT '1'";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("author", $result)){
				$sql = "ALTER TABLE `#__guru_media` ADD `author` int(11) NOT NULL ";
				$database->setQuery($sql);
				$database->query();
			}		

			if(!in_array("image", $result)){
				$sql = "ALTER TABLE `#__guru_media` ADD `image` text ";
				$database->setQuery($sql);
				$database->query();
			}		
			if(!in_array("description", $result)){
				$sql = "ALTER TABLE `#__guru_media` ADD `description` text ";
				$database->setQuery($sql);
				$database->query();
			}	
			if(!in_array("uploaded_tab", $result)){
				$sql = "ALTER TABLE `#__guru_media` ADD `uploaded_tab` int(3) NOT NULL DEFAULT '-1'";
				$database->setQuery($sql);
				$database->query();
			}	
			
		}
		$sql = "SHOW COLUMNS FROM #__guru_promos";
		$database->setQuery($sql);
		$result = $database->loadColumn();
		if(isset($result)){
			if(!in_array("courses_ids", $result)){
				$sql = "ALTER TABLE `#__guru_promos` ADD `courses_ids` text ";
				$database->setQuery($sql);
				$database->query();
			}
 		}
		
		$sql = "SHOW COLUMNS FROM #__guru_quiz";
		$database->setQuery($sql);
		$result = $database->loadColumn();
		if(isset($result)){
			if(!in_array("author", $result)){
				$sql = "ALTER TABLE `#__guru_quiz` ADD `author` int(11) NOT NULL ";
				$database->setQuery($sql);
				$database->query();
			}
			if(!in_array("questions_per_page", $result)){
				$sql = "ALTER TABLE `#__guru_quiz` ADD `questions_per_page` int(11) NOT NULL DEFAULT '10'";
				$database->setQuery($sql);
				$database->query();
			}
 		}
		$sql = "SHOW COLUMNS FROM #__guru_authors";
		$database->setQuery($sql);
		$result = $database->loadColumn();
		if(isset($result)){
			if(!in_array("enabled", $result)){
				$sql = "ALTER TABLE `#__guru_authors` ADD `enabled` tinyint(4) NOT NULL DEFAULT '1'";
				$database->setQuery($sql);
				$database->query();
			}
 		}
		if(isset($result)){
			if(!in_array("commission_id", $result)){
				$sql = "ALTER TABLE `#__guru_authors` ADD `commission_id` int(11) NOT NULL DEFAULT '1'";
				$database->setQuery($sql);
				$database->query();
			}
 		}
		if(isset($result)){
			if(!in_array("paypal_email", $result)){
				$sql = "ALTER TABLE `#__guru_authors` ADD `paypal_email` varchar(100) NOT NULL";
				$database->setQuery($sql);
				$database->query();
			}
 		}
		if(isset($result)){
			if(!in_array("paypal_other_information", $result)){
				$sql = "ALTER TABLE `#__guru_authors` ADD `paypal_other_information` text";
				$database->setQuery($sql);
				$database->query();
			}
 		}
		
		if(isset($result)){
			if(!in_array("paypal_option", $result)){
				$sql = "ALTER TABLE `#__guru_authors` ADD `paypal_option`  tinyint(1) NOT NULL DEFAULT '1'";
				$database->setQuery($sql);
				$database->query();
			}
 		}
		
		$sql = "SELECT `psgpage` FROM `#__guru_config` WHERE id =1";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadColumn();
		$update_no = json_decode($result["0"]);
		
		$psgpage_array = array("course_image_size" => "".$update_no->course_image_size."", "course_image_size_type" => "".$update_no->course_image_size_type."", "course_image_alignment" => "".$update_no->course_image_alignment."", "course_wrap_image" => "".$update_no->course_wrap_image."", "course_author_name_show" => "".$update_no->course_author_name_show."", "course_released_date" => "".$update_no->course_released_date."", "course_level" => "".$update_no->course_level."", "course_price" => "".$update_no->course_price."", "course_price_type" => "".$update_no->course_price_type."", "course_table_contents" => "".$update_no->course_table_contents."", "course_description_show" => "".$update_no->course_description_show."", "course_tab_price" => "".$update_no->course_tab_price."", "course_author" => "".$update_no->course_author."", "course_requirements" => "".$update_no->course_requirements."", "course_buy_button" => "".$update_no->course_buy_button."", "course_buy_button_location" => "".$update_no->course_buy_button_location."", "show_course_image" => "".$update_no->show_course_image."", "show_all_cloase_all" => "".$update_no->show_all_cloase_all."", "show_course_studentamount"=>"".$update_no->show_course_studentamount."");
		
		
		$sql = "SELECT `st_authorpage` FROM `#__guru_config` WHERE id =1";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadColumn();
		$update_noa = json_decode($result["0"]);
		
			
		$st_authorpage_array2 = array("author_name" => "".$update_noa->author_name."", "author_image" => "".$update_noa->author_image."", "author_description" => "".$update_noa->author_description."", "author_st_read_more" => "".$update_noa->author_st_read_more."", "teacher_aprove"=>"".@$update_noa->teacher_aprove."","teacher_group"=>"".@$update_noa->teacher_group."","teacher_add_media"=>"".@$update_noa->teacher_add_media."","teacher_edit_media"=>"".@$update_noa->teacher_edit_media."","teacher_add_courses"=>"".@$update_noa->teacher_add_courses."","teacher_edit_courses"=>"".@$update_noa->teacher_edit_courses."","teacher_add_quizzesfe"=>"".@$update_noa->teacher_add_quizzesfe."","teacher_edit_quizzesfe"=>"".@$update_noa->teacher_edit_quizzesfe."","teacher_add_students"=>"".@$update_noa->teacher_add_students."","teacher_edit_students"=>"".@$update_noa->teacher_edit_students."", "teacher_approve_courses"=>"".@$update_noa->teacher_approve_courses."");
		$st_authorpage2= json_encode($st_authorpage_array2);
		
		$sql = "SELECT `ctgspage` FROM `#__guru_config` WHERE id =1";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadColumn();
		$update_noctgs = json_decode($result["0"]);
		
		$ctgspage_array2 = array("ctgslayout" => "".$update_noctgs->ctgslayout."", "ctgscols" => "".$update_noctgs->ctgscols."", "ctgs_image_size" => "".$update_noctgs->ctgs_image_size."", "ctgs_image_size_type" => "".$update_noctgs->ctgs_image_size_type."", "ctgs_image_alignment" => "".$update_noctgs->ctgs_image_alignment."", "ctgs_wrap_image" => "".$update_noctgs->ctgs_wrap_image."", "ctgs_description_length" => "".$update_noctgs->ctgs_description_length."", "ctgs_description_type" => "".$update_noctgs->ctgs_description_type."", "ctgs_description_alignment"  => "".$update_noctgs->ctgs_description_alignment."", "ctgs_read_more" => "".$update_noctgs->ctgs_read_more."", "ctgs_read_more_align" => "".$update_noctgs->ctgs_read_more_align."", "ctgs_show_empty_catgs" => "".$update_noctgs->ctgs_show_empty_catgs."", "ctgs_description_mode" => "".$update_noctgs->ctgs_description_mode."");
			
		$sql = "SELECT `ctgpage` FROM `#__guru_config` WHERE id =1";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadColumn();
		$update_noctg = json_decode($result["0"]);
			
		$ctgpage_array2 = array("ctg_image_size" => "".$update_noctg->ctg_image_size."", "ctg_image_size_type" => "".$update_noctg->ctg_image_size_type."", "ctg_image_alignment" =>"".$update_noctg->ctg_image_alignment."", "ctg_wrap_image" => "".$update_noctg->ctg_wrap_image."", "ctg_description_length" => "".$update_noctg->ctg_description_length."", "ctg_description_type" => "".$update_noctg->ctg_description_type."", "ctg_description_alignment" => "".$update_noctg->ctg_description_alignment."", "ctg_description_mode"=>"".$update_noctg->ctg_description_mode."");
			
		$sql = "SELECT `psgspage` FROM `#__guru_config` WHERE id =1";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadColumn();
		$update_nopsg = json_decode($result["0"]);
			
		$psgspage_array2 = array("courseslayout" => "".$update_nopsg->courseslayout."", "coursescols" => "".$update_nopsg->coursescols."", "courses_image_size" => "".$update_nopsg->courses_image_size."", "courses_image_size_type" => "".$update_nopsg->courses_image_size_type."", "courses_image_alignment" => "".$update_nopsg->courses_image_alignment."", "courses_wrap_image" => "".$update_nopsg->courses_wrap_image."", "courses_description_length" => "".$update_nopsg->courses_description_length."", "courses_description_type" => "".$update_nopsg->courses_description_type."", "courses_description_alignment" => "".$update_nopsg->courses_description_alignment."", "courses_read_more" => "".$update_nopsg->courses_read_more."", "courses_read_more_align" => "".$update_nopsg->courses_read_more_align."", "courses_description_mode"=>"".$update_nopsg->courses_description_mode."");
		
		$sql = "SELECT `authorspage` FROM `#__guru_config` WHERE id =1";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadColumn();
		$update_nocapsg = json_decode($result["0"]);
			
		$authorspage_array2 = array("authorslayout" => "".$update_nocapsg->authorslayout."", "authorscols" => "".$update_nocapsg->authorscols."", "authors_image_size" => "".$update_nocapsg->authors_image_size."", "authors_image_size_type" => "".$update_nocapsg->authors_image_size_type."", "authors_image_alignment" => "".$update_nocapsg->authors_image_alignment."", "authors_wrap_image" => "".$update_nocapsg->authors_wrap_image."", "authors_description_length" => "".$update_nocapsg->authors_description_length."", "authors_description_type" => "".$update_nocapsg->authors_description_type."", "authors_description_alignment" => "".$update_nocapsg->authors_description_alignment."", "authors_read_more" => "".$update_nocapsg->authors_read_more."", "authors_read_more_align" =>"".$update_nocapsg->authors_read_more_align."", "authors_description_mode"=>"".$update_nocapsg->authors_description_mode."");
		
		if(!isset($update_noctgs->ctgs_description_mode)){
			$insert_config_array = json_encode($ctgspage_array2);
			$sql = "UPDATE `#__guru_config` set `ctgspage` ='".$insert_config_array."' WHERE id=1";
			$database->setQuery($sql);
			$database->query();
		}
		if(!isset($update_noctg->ctg_description_mode)){
			$insert_config_array = json_encode($ctgpage_array2);
			$sql = "UPDATE `#__guru_config` set `ctgpage` ='".$insert_config_array."' WHERE id=1";
			$database->setQuery($sql);
			$database->query();
		}
		if(!isset($update_nopsg->courses_description_mode)){

			$insert_config_array = json_encode($psgspage_array2);
			$sql = "UPDATE `#__guru_config` set `psgspage` ='".$insert_config_array."' WHERE id=1";
			$database->setQuery($sql);
			$database->query();
		}
		if(!isset($update_nocapsg->authors_description_mode)){
			$insert_config_array = json_encode($authorspage_array2);
			$sql = "UPDATE `#__guru_config` set `authorspage` ='".$insert_config_array."' WHERE id=1";
			$database->setQuery($sql);
			$database->query();
		}
		if(!isset($update_no->show_course_studentamount)){
			$insert_config_array = json_encode($psgpage_array);
			$sql = "UPDATE `#__guru_config` set `psgpage` ='".$insert_config_array."' WHERE id=1";
			$database->setQuery($sql);
			$database->query();
		}
		if(!isset($update_noa->teacher_aprove)){
			$insert_config_array = json_encode($st_authorpage_array2);
			$sql = "UPDATE `#__guru_config` set `st_authorpage` ='".$insert_config_array."' WHERE id=1";
			$database->setQuery($sql);
			$database->query();
		}
		
		$sql = "SELECT `template_emails` FROM `#__guru_config` WHERE id =1";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadColumn();
		$update_template = json_decode($result["0"]);
		
		if(!isset($update_template->ask_teacher_subject) || $update_template->ask_teacher_subject == ''){
			$update_template->ask_teacher_subject = "You have a new teacher application";
		}
		
		if(!isset($update_template->ask_teacher_body) || $update_template->ask_teacher_body == ''){
			$update_template->ask_teacher_body = "<p>Dear admin,</p>\r\n<p>You have a new teacher application:</p>\r\n<p>Name: [AUTHOR_NAME]</p>\r\n<p>Thank you!</p>";
		}
		
		if(!isset($update_template->new_teacher_subject) || $update_template->new_teacher_subject == ''){
			$update_template->new_teacher_subject = "New teacher has registered";
		}
		
		if(!isset($update_template->new_teacher_body) || $update_template->new_teacher_body == ''){
			$update_template->new_teacher_body = "<p>Dear admin,</p>\r\n<p>New teacher has registered:</p>\r\n<p>Name: [AUTHOR_NAME]</p>\r\n<p>Thank you!</p>";
		}
		if(!isset($update_template->approve_order_subject) || $update_template->approve_order_subject == ''){
			$update_template->approve_order_subject = "Approved Order";
		}
		
		if(!isset($update_template->approve_order_body) || $update_template->approve_order_body == ''){
			$update_template->approve_order_body = "<p>Hello [STUDENT_FIRST_NAME],</p>\r\n<p>Your [COURSE_NAME] order has been approved and you can access the course now.</p>\r\n<p>Login to the [SITE_NAME] to view it.</p>\r\n<p>Kindest regards,</p>\r\n<p>[SITE_NAME] administrators</p>";
		}
		if(!isset($update_template->pending_order_subject) || $update_template->pending_order_subject == ''){
			$update_template->pending_order_subject = "Pending Order";
		}
		
		if(!isset($update_template->pending_order_body) || $update_template->pending_order_body == ''){
			$update_template->pending_order_body = "<p>Hello,</p>\r\n<p>There is a pending order: <br /> Course name: [COURSE_NAME]<br /> Order number: [ORDER_NUMBER]<br /> Student name: [STUDENT_FIRST_NAME] [STUDENT_LAST_NAME] </p>\r\n<p>To approve the order, please login to the list of orders: [GURU_ORDER_LIST_URL] and approve it.</p>";
		}
		
		$templates_emails_array2 = array("approve_subject"=>"".$update_template->approve_subject."","approve_body"=>"".$update_template->approve_body."","unapprove_subject"=>"".$update_template->unapprove_subject."","unapprove_body"=>"".$update_template->unapprove_body."","ask_approve_subject"=>"".$update_template->ask_approve_subject."","ask_approve_body"=>"".$update_template->ask_approve_body."","approved_teacher_subject"=>"".$update_template->approved_teacher_subject."","approved_teacher_body"=>"".$update_template->approved_teacher_body."","pending_teacher_subject"=>"".$update_template->pending_teacher_subject."","pending_teacher_body"=>"".$update_template->pending_teacher_body."" ,"ask_teacher_subject"=>"".$update_template->ask_teacher_subject."", "ask_teacher_body"=>"".$update_template->ask_teacher_body."", "new_teacher_subject"=>"".$update_template->new_teacher_subject."", "new_teacher_body"=>"".$update_template->new_teacher_body."", "approve_order_subject"=>"".$update_template->approve_order_subject."", "approve_order_body"=>"".$update_template->approve_order_body."", "pending_order_subject"=>"".$update_template->pending_order_subject."", "pending_order_body"=>"".$update_template->pending_order_body."");
		
		$templates_emails2  = json_encode($templates_emails_array2);
		$sql = "UPDATE `#__guru_config` set `template_emails` ='".addslashes($templates_emails2)."' WHERE id=1";
		$database->setQuery($sql);
		$database->query();
			
		$sql = "UPDATE `#__guru_media` set `uploaded_tab` ='1' WHERE `source`='local'";
		$database->setQuery($sql);
		$database->query();
		
		
		
		
		$this->installDefaultContent();
		$this->gurupdateMenuItems();
		
		$filePath = JPATH_ROOT.DS."components".DS."com_guru".DS."css".DS."original"; // Specify the path you want to look in. 				
		$dir = opendir($filePath); // Open the path
		while($file = readdir($dir)){ 
			if(trim($file) != "." && trim($file) != ".."){
				if(strpos($file, ".css") !== FALSE){ // Look at only files with a .css extension
					$string = $file;
					if(!is_file(JPATH_ROOT.DS."components".DS."com_guru".DS."css".DS.$string)){
						$source = JPATH_ROOT.DS."components".DS."com_guru".DS."css".DS."original".DS.$string;
						$destination = JPATH_ROOT.DS."components".DS."com_guru".DS."css".DS.$string;
						if(JFile::copy($source, $destination)){
							// do nothing, already saved in database
						}
					}
				 }
			}	 
		}
		
		echo '<script language="javascript" type="text/javascript">
					setTimeout(function(){window.location.href = "'.JURI::root()."administrator/index.php?option=com_guru".'";}, 2000);
			  </script>';
		
		return true;
	}
	
	function gurupdateMenuItems()
	{
		// Get new component id.
		$component		= JComponentHelper::getComponent('com_guru');
		$component_id	= 0;
		if (is_object($component) && isset($component->id)){
			$component_id 	= $component->id;
		}
		if ($component_id > 0)
		{
			// Update the existing menu items.
			$db 	= JFactory::getDBO();
			$query 	= "UPDATE #__menu SET component_id=".intval($component_id)." WHERE link LIKE '%option=com_guru%'";
			$db->setQuery( $query );
			$db->query();
			if($db->getErrorNum())
			{
				return false;
			}
		}
		return true;
	}

	function installDefaultContent(){
		$db = JFactory::getDBO();
		$user_id = "0";
		
		$sql = "select id from #__users where username='ijoomla' and email='demo@ijoomla.com'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		
		if(!isset($result["0"]) || $result["0"] == NULL){
			$sql = "select `id` from `#__users` where `username`='ijoomla'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadColumn();
			
			if(isset($result) && isset($result["0"])){
				$user_id = intval($result["0"]);
			}
			else{
				$data = array("name"=>"iJoomla", "username"=>"ijoomla", "email"=>"demo@ijoomla.com", "password"=>"000000");
				$user = new JUser();
				if(!$user->bind($data)){
					//die($user->getError());
				}
				$user->gid = 18;
				if(!$user->save()){
					//die($user->getError());
				}
				$user_id = $user->id;
				$sql = "select id from #__usergroups where title='Registered'";
				$db->setQuery($sql);
				$db->query();
				$user_group_id = $db->loadColumn();
				$user_group_id = $user_group_id["0"];
				
				$sql = "INSERT INTO `#__user_usergroup_map` (`user_id`, `group_id`) VALUES (".$user_id.", ".$user_group_id.")";
				$db->setQuery($sql);
				$db->query();
			}
		}
		else{
			$user_id = intval($result["0"]);
		}
		
		$sql = "select count(*) from #__guru_authors where userid=".intval($user_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		
		if($result["0"] == "0"){
			$sql = "INSERT INTO `#__guru_authors` (`userid`, `gid`, `full_bio`, `images`, `emaillink`, `website`, `blog`, `facebook`, `twitter`, `show_email`, `show_website`, `show_blog`, `show_facebook`, `show_twitter`, `author_title`, `ordering`, `forum_kunena_generated`,`enabled`) VALUES
	(".$user_id.", 2, '<p><a href=\"http://www.ijoomla.com\" title=\"\" target=\"_blank\">iJoomla.com</a> is the winner of &quot;<a href=\"http://www.cmsexpo.net/news-updates/643-cmsa-spotlight-award-winners\" title=\"\">The Best Joomla App Development Firm</a>&quot; award at the 2010 <strong>CMS Expo</strong>. </p>\r\n<p>At iJoomla, we combine open source with professional standards. Our\r\ndevelopers are all experienced, full-time professionals dedicated to\r\nproducing Joomla components that take Joomla sites to the next level.\r\nWhile other developers are limited to weekend coding and occasional\r\ndebugging, our staff are constantly working to improve your site. </p>\r\n<p>The quality of our work is seen in its usability, its design and its\r\nfunctionality.\r\nAll our commercial components come with first rate technical support. If\r\n you''ve got a question about one of our components, a member of our team\r\n will be back with an answer in no time at all. Ultimately, our goal is\r\nto create a new standard for the <a href=\"http://www.ijoomla.com\" title=\"\" target=\"_blank\">Joomla</a> community: top quality\r\ncomponents with professional service for a very low price. </p>', '/images/stories/guru/authors/thumbs/ijoomla2.gif', 0, 'http://www.ijoomla.com', 'http://www.ijoomla.com/blog', 'http://www.facebook.com/ijoomla', 'ijoomla', 1, 1, 1, 1, 1, '', 0, 0, 1)";
			$db->setQuery($sql);
			$db->query();
			if(!JFolder::exists(JPATH_SITE."/images/stories/guru/authors/thumbs")){
				JFolder::create(JPATH_SITE."/images/stories/guru/authors/thumbs");
			}
			
			$component_dir = JPATH_SITE.'/administrator/components/com_guru';
			copy($component_dir."/images/for_install/authors/ijoomla2.gif", JPATH_SITE."/images/stories/guru/authors/ijoomla2.gif");
			copy($component_dir."/images/for_install/authors/thumbs/ijoomla2.gif", JPATH_SITE."/images/stories/guru/authors/thumbs/ijoomla2.gif");
			
		}
		else{
			$sql = "SELECT id FROM `#__users` WHERE block=1";
			$db->setQuery($sql);
			$db->query();
			$result_blocked = $db->loadColumn();
			$result_blocked = implode("," ,$result_blocked);
			if($result_blocked != ""){
				$sql = "UPDATE `#__guru_authors` set enabled = 0 WHERE userid IN(".$result_blocked.")";
				$db->setQuery($sql);
				$db->query();
			}
		}
		$sql = "select count(*) from #__guru_media_categories";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		if($result["0"] == "0"){
			$sql = "INSERT INTO `#__guru_media_categories` (`id`, `name`, `parent_id`, `child_id`, `description`, `metatitle`, `metakey`, `metadesc`, `published`) VALUES
	(3, 'Guru media', 0, 0, 'media for the Guru course', '', '', '', 1)";
			$db->setQuery($sql);
			$db->query();
		}
		
		$sql = "select count(*) from #__guru_media";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		
		$sql = "select id from #__users where username='ijoomla' and email='demo@ijoomla.com'";
		$db->setQuery($sql);
		$db->query();
		$result_id = $db->loadColumn();
		
		if($result["0"] == "0"){
			$sql = "INSERT INTO `#__guru_media` (`id`, `name`, `instructions`, `type`, `source`, `uploaded`, `code`, `url`, `local`, `width`, `height`, `published`, `option_video_size`, `category_id`, `auto_play`, `show_instruction`,`hide_name`,`author`,`image`,`description`,`uploaded_tab`) VALUES
	(1, 'Overview of Guru', '', 'video', 'url', '0', '', 'http://vimeo.com/27181459', '', 0, 0, 1, 0, 3, 1, 0,1,'".$result_id["0"]."','','','-1'),
	(2, 'Adding a promo code', '', 'video', 'url', '0', '', 'http://vimeo.com/27181476', '', 0, 0, 1, 0, 3, 1, 0,1,'".$result_id["0"]."','','','-1'),
	(3, 'Edit the language file', '', 'video', 'url', '0', '', 'http://vimeo.com/27181372', '', 0, 0, 1, 0, 3, 1, 0,1,'".$result_id["0"]."','','','-1'),
	(4, 'Adding table of content', '', 'video', 'url', '0', '', 'http://vimeo.com/27181365', '', 0, 0, 1, 0, 3, 1, 0,1,'".$result_id["0"]."','','','-1'),
	(5, 'Adding media', '', 'video', 'url', '0', '', 'http://vimeo.com/27181343', '', 0, 0, 1, 0, 3, 1, 0,1,'".$result_id["0"]."','','','-1'),
	(6, 'Adding a course on the backend', '', 'video', 'url', '0', '', 'http://vimeo.com/27181315', '', 0, 0, 1, 0, 3, 1, 0,1,'".$result_id["0"]."','','','-1'),
	(7, 'Adding an order on the backend', '', 'video', 'url', '0', '', 'http://vimeo.com/27181273', '', 0, 0, 1, 0, 3, 1, 0,1,'".$result_id["0"]."','','','-1'),
	(8, 'Adding a student', '', 'video', 'url', '0', '', 'http://vimeo.com/27181347', '', 0, 0, 1, 0, 3, 1, 0,1,'".$result_id["0"]."','','','-1')";
			$db->setQuery($sql);
			$db->query();
		}
		
		$sql = "select count(*) from #__guru_category";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		if($result["0"] == "0"){
			$sql = "INSERT INTO `#__guru_category` (`id`, `name`, `alias`, `published`, `description`, `image`, `ordering`) VALUES
	(1, 'iJoomla', 'ijoomla', 1, '<p>iJoomla.com is the winner of &quot;<a href=\"http://www.cmsexpo.net/news-updates/643-cmsa-spotlight-award-winners\" title=\"\" onclick=\"window.open(this.getAttribute(''href''),'''');return false;\">The Best Joomla App Development Firm</a>&quot; award at the 2010 <strong>CMS Expo</strong>. </p>', 'images/stories/guru/categories/thumbs/ijoomla2.gif', 1)";
			$db->setQuery($sql);
			if($db->query()){
				$sql = "INSERT INTO `#__guru_categoryrel` (`parent_id`, `child_id`) VALUES (0, 1)";
				$db->setQuery($sql);
				$db->query();
			}
			if(!JFolder::exists(JPATH_SITE."/images/stories/guru/categories/thumbs")){
				JFolder::create(JPATH_SITE."/images/stories/guru/categories/thumbs");
			}
			$component_dir = JPATH_SITE.'/administrator/components/com_guru';
			copy($component_dir."/images/for_install/categories/ijoomla2.gif", JPATH_SITE."/images/stories/guru/categories/ijoomla2.gif");
			copy($component_dir."/images/for_install/categories/thumbs/ijoomla2.gif", JPATH_SITE."/images/stories/guru/categories/thumbs/ijoomla2.gif");
		}
		
		$sql = "select count(*) from #__guru_program";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		if($result["0"] == "0"){
			$jnow = JFactory::getDate();
			$date_today = $jnow->toSQL();
			$sql = "INSERT INTO `#__guru_program` (`id`, `catid`, `name`, `alias`, `description`, `introtext`, `image`, `emails`, `published`, `startpublish`, `endpublish`, `metatitle`, `metakwd`, `metadesc`, `ordering`, `pre_req`, `pre_req_books`, `reqmts`, `author`, `level`, `priceformat`, `skip_module`) VALUES
	(1, 1, 'How to use Guru', 'how-to-use-guru', '<p>Guru is a <a href=\"http://guru.ijoomla.com/\" title=\"\" target=\"_blank\">joomla LMS</a>, designed to help you create online training courses. On this course you will learn how to use it. </p>', '', 'images/stories/guru/courses/thumbs/guru3.jpg', NULL, 1, '".$date_today."', '0000-00-00 00:00:00', '', '', '', 0, 'Basic Joomla knowledge', '', '', ".intval($user_id).", 0, '1', 0);";
			$db->setQuery($sql);
			if($db->query()){
				$sql = "INSERT INTO `#__guru_program_plans` (`product_id`, `plan_id`, `price`, `default`) VALUES
							(1, 3, 0.3, 0),
							(1, 2, 0.2, 0),
							(1, 7, 0.1, 1)";
				$db->setQuery($sql);
				$db->query();
				
				$sql = "INSERT INTO `#__guru_days` (`id`, `pid`, `title`, `alias`, `description`, `image`, `published`, `startpublish`, `endpublish`, `metatitle`, `metakwd`, `metadesc`, `afterfinish`, `url`, `pagetitle`, `pagecontent`, `ordering`, `locked`, `media_id`, `access`) VALUES
	(1, 1, 'Getting started', 'getting-started', '', NULL, 1, '".$date_today."', '0000-00-00 00:00:00', NULL, NULL, NULL, 0, NULL, NULL, '', 1, 0, 0, 2),
	(2, 1, 'Creating your course', 'creating-your-course', NULL, NULL, 1, '".$date_today."', '0000-00-00 00:00:00', NULL, NULL, NULL, 0, NULL, NULL, NULL, 2, 0, 0, 2),
	(3, 1, 'Adding stuff', 'adding-stuff', NULL, NULL, 1, '".$date_today."', '0000-00-00 00:00:00', NULL, NULL, NULL, 0, NULL, NULL, NULL, 3, 0, 0, 0),
	(4, 1, 'Settings', 'settings', '', NULL, 1, '".$date_today."', '0000-00-00 00:00:00', NULL, NULL, NULL, 0, NULL, NULL, '', 4, 0, 0, 0)";
				$db->setQuery($sql);
				$db->query();
				
				$sql = "INSERT INTO `#__guru_task` (`id`, `name`, `alias`, `category`, `difficultylevel`, `points`, `image`, `published`, `startpublish`, `endpublish`, `metatitle`, `metakwd`, `metadesc`, `time`, `ordering`, `step_access`, `forum_kunena_generatedt`) VALUES
	(1, 'Overview of Guru', 'overview-of-guru', NULL, 'easy', NULL, NULL, 1, '".$date_today."', '0000-00-00 00:00:00', '', '', '', 0, 1, 2, 0),
	(2, 'Adding a course on the backend', 'adding-a-course-on-the-backend', NULL, 'easy', NULL, NULL, 1, '".$date_today."', '0000-00-00 00:00:00', '', '', '', 0, 3, 2, 0),
	(3, 'Adding table of content', 'adding-table-of-content', NULL, 'easy', NULL, NULL, 1, '".$date_today."', '0000-00-00 00:00:00', '', '', '', 0, 6, 0, 0),
	(4, 'Adding media', 'adding-media', NULL, 'easy', NULL, NULL, 1, '".$date_today."', '0000-00-00 00:00:00', '', '', '', 0, 10, 0, 0),
	(5, 'Adding a student', 'adding-a-student', NULL, 'easy', NULL, NULL, 1, '".$date_today."', '0000-00-00 00:00:00', '', '', '', 0, 7, 0, 0),
	(6, 'Adding a promo code', 'adding-a-promo-code', NULL, 'easy', NULL, NULL, 1, '".$date_today."', '0000-00-00 00:00:00', '', '', '', 0, 8, 0, 0),
	(7, 'Adding an order on the backend', 'adding-an-order-on-the-backend', NULL, 'easy', NULL, NULL, 1, '".$date_today."', '0000-00-00 00:00:00', '', '', '', 0, 9, 0, 0),
	(8, 'Editing the language file', 'editing-the-language-file', NULL, 'easy', NULL, NULL, 1, '".$date_today."', '0000-00-00 00:00:00', '', '', '', 0, 11, 0, 0)";
				$db->setQuery($sql);
				$db->query();
				
				$sql = "INSERT INTO `#__guru_mediarel` (`type`, `type_id`, `media_id`, `mainmedia`, `text_no`, `layout`, `access`, `order`) VALUES
							('scr_l', 1, 6, 0, 0, 0, 0, 0),
							('dtask', 1, 1, 0, 0, 0, 0, 0),
							('dtask', 0, 1, 0, 0, 0, 0, 0),
							('scr_l', 2, 6, 0, 0, 0, 0, 0),
							('dtask', 2, 4, 0, 0, 0, 0, 0),
							('scr_l', 3, 6, 0, 0, 0, 0, 0),
							('dtask', 2, 3, 0, 0, 0, 0, 0),
							('scr_l', 4, 6, 0, 0, 0, 0, 0),
							('dtask', 2, 2, 0, 0, 0, 0, 0),
							('scr_l', 5, 6, 0, 0, 0, 0, 0),
							('dtask', 3, 7, 0, 0, 0, 0, 0),
							('scr_l', 6, 6, 0, 0, 0, 0, 0),
							('dtask', 3, 6, 0, 0, 0, 0, 0),
							('scr_l', 7, 6, 0, 0, 0, 0, 0),
							('dtask', 3, 5, 0, 0, 0, 0, 0),
							('scr_m', 3, 4, 1, 0, 1, 0, 0),
							('scr_l', 8, 6, 0, 0, 0, 0, 0),
							('dtask', 4, 8, 0, 0, 0, 0, 0),
							('scr_m', 1, 1, 1, 0, 1, 0, 0),
							('scr_m', 1, 1, 1, 0, 2, 0, 0),
							('scr_m', 1, 1, 1, 0, 3, 0, 0),
							('scr_m', 1, 1, 1, 0, 4, 0, 0),
							('scr_m', 1, 1, 1, 0, 6, 0, 0),
							('scr_m', 1, 1, 1, 0, 7, 0, 0),
							('scr_m', 1, 1, 1, 0, 8, 0, 0),
							('scr_m', 1, 1, 1, 0, 9, 0, 0),
							('scr_m', 1, 1, 1, 0, 10, 0, 0),
							('scr_m', 1, 1, 1, 0, 11, 0, 0),
							('dtask', 0, 1, 0, 0, 0, 0, 0),
							('scr_m', 2, 6, 1, 0, 1, 0, 0),
							('scr_m', 2, 6, 1, 0, 2, 0, 0),
							('scr_m', 2, 6, 1, 0, 3, 0, 0),
							('scr_m', 2, 6, 1, 0, 4, 0, 0),
							('scr_m', 2, 6, 1, 0, 6, 0, 0),
							('scr_m', 2, 6, 1, 0, 7, 0, 0),
							('scr_m', 2, 6, 1, 0, 8, 0, 0),
							('scr_m', 2, 6, 1, 0, 9, 0, 0),
							('scr_m', 2, 6, 1, 0, 10, 0, 0),
							('scr_m', 2, 6, 1, 0, 11, 0, 0),
							('dtask', 0, 2, 0, 0, 0, 0, 0),
							('scr_m', 3, 4, 1, 0, 2, 0, 0),
							('scr_m', 3, 4, 1, 0, 3, 0, 0),
							('scr_m', 3, 4, 1, 0, 4, 0, 0),
							('scr_m', 3, 4, 1, 0, 6, 0, 0),
							('scr_m', 3, 4, 1, 0, 7, 0, 0),
							('scr_m', 3, 4, 1, 0, 8, 0, 0),
							('scr_m', 3, 4, 1, 0, 9, 0, 0),
							('scr_m', 3, 4, 1, 0, 10, 0, 0),
							('scr_m', 3, 4, 1, 0, 11, 0, 0),
							('dtask', 0, 3, 0, 0, 0, 0, 0),
							('scr_m', 4, 5, 1, 0, 1, 0, 0),
							('scr_m', 4, 5, 1, 0, 2, 0, 0),
							('scr_m', 4, 5, 1, 0, 3, 0, 0),
							('scr_m', 4, 5, 1, 0, 4, 0, 0),
							('scr_m', 4, 5, 1, 0, 6, 0, 0),
							('scr_m', 4, 5, 1, 0, 7, 0, 0),
							('scr_m', 4, 5, 1, 0, 8, 0, 0),
							('scr_m', 4, 5, 1, 0, 9, 0, 0),
							('scr_m', 4, 5, 1, 0, 10, 0, 0),
							('scr_m', 4, 5, 1, 0, 11, 0, 0),
							('dtask', 0, 4, 0, 0, 0, 0, 0),
							('scr_m', 5, 8, 1, 0, 1, 0, 0),
							('scr_m', 5, 8, 1, 0, 2, 0, 0),
							('scr_m', 5, 8, 1, 0, 3, 0, 0),
							('scr_m', 5, 8, 1, 0, 4, 0, 0),

							('scr_m', 5, 8, 1, 0, 6, 0, 0),
							('scr_m', 5, 8, 1, 0, 7, 0, 0),
							('scr_m', 5, 8, 1, 0, 8, 0, 0),
							('scr_m', 5, 8, 1, 0, 9, 0, 0),
							('scr_m', 5, 8, 1, 0, 10, 0, 0),
							('scr_m', 5, 8, 1, 0, 11, 0, 0),
							('dtask', 0, 5, 0, 0, 0, 0, 0),
							('scr_m', 6, 2, 1, 0, 1, 0, 0),
							('scr_m', 6, 2, 1, 0, 2, 0, 0),
							('scr_m', 6, 2, 1, 0, 3, 0, 0),
							('scr_m', 6, 2, 1, 0, 4, 0, 0),
							('scr_m', 6, 2, 1, 0, 6, 0, 0),
							('scr_m', 6, 2, 1, 0, 7, 0, 0),
							('scr_m', 6, 2, 1, 0, 8, 0, 0),
							('scr_m', 6, 2, 1, 0, 9, 0, 0),
							('scr_m', 6, 2, 1, 0, 10, 0, 0),
							('scr_m', 6, 2, 1, 0, 11, 0, 0),
							('dtask', 0, 6, 0, 0, 0, 0, 0),
							('scr_m', 7, 7, 1, 0, 1, 0, 0),
							('scr_m', 7, 7, 1, 0, 2, 0, 0),
							('scr_m', 7, 7, 1, 0, 3, 0, 0),
							('scr_m', 7, 7, 1, 0, 4, 0, 0),
							('scr_m', 7, 7, 1, 0, 6, 0, 0),
							('scr_m', 7, 7, 1, 0, 7, 0, 0),
							('scr_m', 7, 7, 1, 0, 8, 0, 0),
							('scr_m', 7, 7, 1, 0, 9, 0, 0),
							('scr_m', 7, 7, 1, 0, 10, 0, 0),
							('scr_m', 7, 7, 1, 0, 11, 0, 0),
							('dtask', 0, 7, 0, 0, 0, 0, 0)";
				$db->setQuery($sql);
				$db->query();
			}
			
			if(!JFolder::exists(JPATH_SITE."/images/stories/guru/courses/thumbs")){
				JFolder::create(JPATH_SITE."/images/stories/guru/courses/thumbs");
			}
			$component_dir = JPATH_SITE.'/administrator/components/com_guru';
			copy($component_dir."/images/for_install/courses/guru3.jpg", JPATH_SITE."/images/stories/guru/courses/guru3.jpg");
			copy($component_dir."/images/for_install/courses/thumbs/guru3.jpg", JPATH_SITE."/images/stories/guru/courses/thumbs/guru3.jpg");
		}
		
	}

	function installAlertUploadPlugins(){
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__extensions where element='ijoomlanews'";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		$count = $count["0"];
		
		$component_dir = JPATH_SITE.'/administrator/components/com_guru/plugins';
		
		if($count == 0){
		   $query = "INSERT INTO #__extensions (name,type,element,folder,client_id,enabled,access,protected,manifest_cache,params,custom_data,system_data,checked_out, 	checked_out_time,ordering,state)"
			."\n VALUES ('iJoomla News', 'plugin', 'ijoomlanews', 'system', 0, 1, 1, 0, '', '{\"nr_articles\":\"3\",\"text_length\":\"100\",\"image_width\":\"50\"}', '', '', 0, '0000-00-00 00:00:00' , -10300, 0)";
			$db->setQuery($query);
			$db->query();
		}
		
		//----------------------------------------start news plugin
		$news_dir = JPATH_SITE.'/plugins/system/ijoomlanews';
		$component_dirn = JPATH_SITE.'/administrator/components/com_guru/plugins';
		if(!is_dir($news_dir)){
			mkdir($news_dir, 0755);
		}
		$news_php = 'ijoomlanews.php';
		$news_xml = 'ijoomlanews.xml';
		$news_folder = 'ijoomlanews';
		@chmod($news_dir, 0755);
		if(!copy($component_dirn."/ijoomlanews/".$news_xml, $news_dir."/".$news_xml)){
			echo 'Error copying ijoomlanews.xml'."<br/>";
		}
		if(!copy($component_dirn."/ijoomlanews/".$news_php, $news_dir."/".$news_php)){
			echo 'Error copying ijoomlanews.php'."<br/>";
		}
		if(!is_dir($news_dir."/".$news_folder)){
			mkdir($news_dir."/".$news_folder, 0755);
		}
		if(!copy($component_dirn."/ijoomlanews/".$news_folder."/feed.php", $news_dir."/".$news_folder."/feed.php")){
			echo 'Error copying feed.php'."<br/>";
		}
		if(!copy($component_dirn."/ijoomlanews/".$news_folder."/tabs.php", $news_dir."/".$news_folder."/tabs.php")){
			echo 'Error copying tabs.php'."<br/>";
		}
		if(!copy($component_dirn."/ijoomlanews/".$news_folder."/index.html", $news_dir."/".$news_folder."/index.html")){
			echo 'Error copying index.html'."<br/>";
		}
		//----------------------------------------stop news plugin
		
		//----------------------------------------start jomsocial plugin
		$sql = "select count(*) from #__extensions where element='guru_user_update'";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		$count = $count["0"];
		
		if($count == 0){
		   $query = 'INSERT INTO #__extensions (name,type,element,folder,client_id,enabled,access,protected,manifest_cache,params,custom_data,system_data,checked_out, 	checked_out_time,ordering,state) VALUES ( "Guru User Update", "plugin", "guru_user_update", "user", 0, 1, 1, 0, \'{"name":"Guru User Update","type":"plugin","creationDate":"February 12, 2014","author":"iJoomla","copyright":"(C) 2014 iJoomla.com","authorEmail":"webmaster2@ijoomla.com","authorUrl":"www.ijoomla.com","version":"1.0.0","description":"","group":""}\', "{}", "", "", 0, "0000-00-00 00:00:00", 0, 0)';
			$db->setQuery($query);
			$db->query();
		}
		
		
		$jsplug_dir = JPATH_SITE.'/plugins/user/guru_user_update';
		$component_dirn = JPATH_SITE.'/administrator/components/com_guru/plugins';
		if(!is_dir($jsplug_dir)){
			mkdir($jsplug_dir, 0755);
		}
		$jsplug_php = 'guru_user_update.php';
		$jsplug_xml = 'guru_user_update.xml';
		$jsplug_folder = 'guru_user_update';
		@chmod($jsplug_folder, 0755);
		if(!copy($component_dirn."/guru_user_update/".$jsplug_xml, $jsplug_dir."/".$jsplug_xml)){
			echo 'Error copying guru_user_update.xml'."<br/>";
		}
		if(!copy($component_dirn."/guru_user_update/".$jsplug_php, $jsplug_dir."/".$jsplug_php)){
			echo 'Error copying guru_user_update.php'."<br/>";
		}
		//----------------------------------------stop jomsocial plugin
		
		//----------------------------------------start teacher events plugin 
		
		$sql = "select count(*) from #__extensions where element='guruteacheractions'";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		$count = $count["0"];
		
		if($count == 0){
		   $query = 'INSERT INTO #__extensions (name,type,element,folder,client_id,enabled,access,protected,manifest_cache,params,custom_data,system_data,checked_out, 	checked_out_time,ordering,state) VALUES ( "Guru Teacher Actions", "plugin", "guruteacheractions", "system", 0, 1, 1, 0, \'{"name":"Guru Teacher Actions","type":"plugin","creationDate":"July 16, 2014","author":"iJoomla","copyright":"(C) 2014 iJoomla.com","authorEmail":"webmaster2@ijoomla.com","authorUrl":"www.ijoomla.com","version":"1.0.0","description":"","group":""}\', "{}", "", "", 0, "0000-00-00 00:00:00", 0, 0)';
			$db->setQuery($query);
			$db->query();
		}
		
		
		$jsplug_dir = JPATH_SITE.'/plugins/system/guruteacheractions';
		$component_dirn = JPATH_SITE.'/administrator/components/com_guru/plugins';
		if(!is_dir($jsplug_dir)){
			mkdir($jsplug_dir, 0755);
		}
		$jsplug_php = 'guruteacheractions.php';
		$jsplug_xml = 'guruteacheractions.xml';
		$jsplug_folder = 'guruteacheractions';
		@chmod($jsplug_folder, 0755);
		if(!copy($component_dirn."/guruteacheractions/".$jsplug_xml, $jsplug_dir."/".$jsplug_xml)){
			echo 'Error copying guruteacheractions.xml'."<br/>";
		}
		if(!copy($component_dirn."/guruteacheractions/".$jsplug_php, $jsplug_dir."/".$jsplug_php)){
			echo 'Error copying guruteacheractions.php'."<br/>";
		}
		//----------------------------------------stop teacher events plugin 
		
		
		//----------------------------------------start jw_allvideos
		$sql = "select count(*) from #__extensions where element='jw_allvideos'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		if($result["0"] == "0"){
			$sql = "INSERT INTO #__extensions (name,type,element,folder,client_id,enabled,access,protected,manifest_cache,params,custom_data,system_data,checked_out, 	checked_out_time, ordering, state)"
				."\n VALUES ('AllVideos (by JoomlaWorks)', 'plugin', 'jw_allvideos', 'content', 0, 1, 1, 0, '', 'vfolder=images/stories/videos\nvwidth=400\nvheight=300\ntransparency=transparent\nbackground=#010101\nbackgroundQT=black\ncontrolBarLocation=bottom\nlightboxLink=1\nlightboxWidth=800\nlightboxHeight=600\nafolder=images/stories/audio\nawidth=300\naheight=20\ndownloadLink=1\nembedForm=1\n', '', '', 0, '0000-00-00 00:00:00' ,0, 0)";
			$db->setQuery($sql);
			$db->query();
		}
		
		$update_dir = JPATH_SITE.'/plugins/content';
		$source = $component_dir."/jw_allvideos";
		$destination = $update_dir."/jw_allvideos";
		if(!JFolder::copy($source, $destination, '', true)){
			echo "Can't copy ".$source;
		}
		//----------------------------------------stop jw_allvideos
		
		$sql = "select count(*) from #__extensions where element='gurucron'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		
		if($result["0"] == 0){
		$sql = "INSERT INTO #__extensions (name,type,element,folder,client_id,enabled,access,protected,manifest_cache,params,custom_data,system_data,checked_out, checked_out_time, ordering, state)"
			."\n VALUES ('System - Guru Cron', 'plugin', 'gurucron', 'system', 0, 1, 0, 0, '', '', '', '', 0, '0000-00-00 00:00:00' ,0, 0)";
		$db->setQuery($sql);
		$db->query();
		}
		
		//----------------------------------------start cron_GURU plugin
		$update_dir = JPATH_SITE.'/plugins/system/gurucron';
		if(!is_dir($update_dir)){
		mkdir($update_dir);
		}
		$update_php = 'gurucron.php';
		$update_xml = 'gurucron.xml';
		@chmod($update_dir);
		if(!copy($component_dir."/cron_GURU/".$update_xml, $update_dir."/".$update_xml)){
		echo 'Error copying gurucron.xml'."<br/>";
		}	
		if(!copy($component_dir."/cron_GURU/".$update_php, $update_dir."/".$update_php)){
		echo 'Error copying gurucron.php'."<br/>";
		}
		//----------------------------------------stop cron_GURU plugin
		
		$sql = "select element from #__extensions where element='paypaypal' and folder='gurupayment'";
		$db->setQuery($sql);
		$db->query();
		$name = $db->loadColumn();
		
		if(empty($name["0"])){
		$sql = "INSERT INTO #__extensions (name,type,element,folder,client_id,enabled,access,protected,params,custom_data,system_data,checked_out, 	checked_out_time,ordering,state)"
		."\n VALUES ('Payment Processor [PayPal]', 'plugin', 'paypaypal', 'gurupayment', 0, 1, 1, 0, 'paypaypal_label=PayPal Payment\npaypaypal_image=paypal1.gif\npaypaypal_lc=EN\npaypaypal_currency=USD\npaypaypal_tax=0.00\npaypaypal_ship=1\n', '', '', 0, '0000-00-00 00:00:00', 0, 0)";
		$db->setQuery($sql);
		$db->query();
		}
		
		//----------------------------------------start paypaypal_GURU plugin
		$update_dir = JPATH_SITE.'/plugins/gurupayment/paypaypal';
		$source = $component_dir."/paypaypal_GURU";
		$destination = $update_dir;
		if(!JFolder::copy($source, $destination, '', true)){
			echo "Can't copy ".$source;
		}
		//----------------------------------------stop paypaypal_GURU plugin
	
	}
	
	function installGuruDiscussPlugin(){
		jimport('joomla.filesystem.folder');
		$modules = array();
		$sourceModules	= JPATH_ROOT . '/administrator/components/com_guru/plugins/guru_offline_plugin';
	
		$listModules = JFolder::files($sourceModules);
		
		foreach($listModules as $row){
			$modules[] = $sourceModules."/".$row;
		}
		
		jimport('joomla.installer.installer');
		jimport('joomla.installer.helper');
	
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
	
		foreach($modules as $module){
			$package   = JInstallerHelper::unpack($module);
			$installer = JInstaller::getInstance();
	
			if(!$installer->install($package['dir'])){
				// There was an error installing the package
			}
			// Cleanup the install files
			if (!is_file($package['packagefile'])){
				$package['packagefile'] = $app->getCfg('tmp_path').'/'.$package['packagefile'];
			}
			JInstallerHelper::cleanupInstall('', $package['extractdir']);
			
			$sql = "update #__extensions set `enabled`=1 where `type`='plugin' and `element`='ijoomlagurudiscussbox'";
			$db->setQuery($sql);
			$db->query();
		}
	}
	
	function installGuruPayOfflinePlugin(){
		jimport('joomla.filesystem.folder');
		$modules = array();
		$sourceModules	= JPATH_ROOT . '/administrator/components/com_guru/plugins/ijoomlagurudiscussbox';
	
		$listModules = JFolder::files($sourceModules);
		
		foreach($listModules as $row){
			$modules[] = $sourceModules."/".$row;
		}
		
		jimport('joomla.installer.installer');
		jimport('joomla.installer.helper');
	
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
	
		foreach($modules as $module){
			$package   = JInstallerHelper::unpack($module);
			$installer = JInstaller::getInstance();
	
			if(!$installer->install($package['dir'])){
				// There was an error installing the package
			}
			// Cleanup the install files
			if (!is_file($package['packagefile'])){
				$package['packagefile'] = $app->getCfg('tmp_path').'/'.$package['packagefile'];
			}
			JInstallerHelper::cleanupInstall('', $package['extractdir']);
		}
	}
	



	function createGuruIndex(){
		$db = JFactory::getDBO();
		$config = new JConfig();
		$database = $config->db;
		$host = $_SERVER["HTTP_HOST"];
		if($host != "localhost"){
		$sql = "select count(*) from information_schema.statistics where INDEX_NAME='index_type_id' and INDEX_SCHEMA='".$database."'";
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadColumn();
			
			if($count["0"] == "0"){
				$sql = "create index index_type_id on #__guru_mediarel(type_id)";
				$db->setQuery($sql);
				$db->query();
			}
			
			$sql = "select count(*) from information_schema.statistics where INDEX_NAME='index_type' and INDEX_SCHEMA='".$database."'";
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadColumn();
			if($count == "0"){
				$sql = "create index index_type on #__guru_mediarel(type)";
				$db->setQuery($sql);
				$db->query();
			}
			
			$sql = "select count(*) from information_schema.statistics where INDEX_NAME='index_media_id' and INDEX_SCHEMA='".$database."'";
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadColumn();
			if($count == "0"){
				$sql = "create index index_media_id on #__guru_mediarel(media_id)";
				$db->setQuery($sql);
				$db->query();
			}
			
			$sql = "select count(*) from information_schema.statistics where INDEX_NAME='index_mainmedia' and INDEX_SCHEMA='".$database."'";
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadColumn();
			if($count == "0"){
				$sql = "create index index_mainmedia on #__guru_mediarel(mainmedia)";
				$db->setQuery($sql);
				$db->query();
			}
			
			$sql = "select count(*) from information_schema.statistics where INDEX_NAME='index_text_no' and INDEX_SCHEMA='".$database."'";
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadColumn();
			if($count == "0"){
				$sql = "create index index_text_no on #__guru_mediarel(text_no)";
				$db->setQuery($sql);
				$db->query();
			}
			
			$sql = "select count(*) from information_schema.statistics where INDEX_NAME='index_layout' and INDEX_SCHEMA='".$database."'";
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadColumn();
			if($count == "0"){
				$sql = "create index index_layout on #__guru_mediarel(layout)";
				$db->setQuery($sql);
				$db->query();
			}
			$sql = "select count(*) from information_schema.statistics where INDEX_NAME='index_access' and INDEX_SCHEMA='".$database."'";
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadColumn();
			if($count == "0"){
				$sql = "create index index_access on #__guru_mediarel(access)";
				$db->setQuery($sql);
				$db->query();
			}
			$sql = "select count(*) from information_schema.statistics where INDEX_NAME='index_ordering' and INDEX_SCHEMA='".$database."'";
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadColumn();
			if($count == "0"){
				$sql = "create index index_ordering on #__guru_mediarel(`order`)";
				$db->setQuery($sql);
				$db->query();
			}
		
		
		
		}
	}
	function update($parent){
		$this->install();
	}
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent){
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//echo '<p>' . JText::_('COM_ALTACOACH_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent){
		
	}
}