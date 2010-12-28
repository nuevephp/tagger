# Sequel Pro dump
# Version 2492
# http://code.google.com/p/sequel-pro
#
# Host: 127.0.0.1 (MySQL 5.1.52)
# Database: wolf
# Generation Time: 2010-12-28 19:20:46 +0000
# ************************************************************


# Dump of table wlf_page
# ------------------------------------------------------------

LOCK TABLES `{prefix}page` WRITE;
INSERT INTO `{prefix}page` (`id`,`title`,`slug`,`breadcrumb`,`keywords`,`description`,`parent_id`,`layout_id`,`behavior_id`,`status_id`,`comment_status`,`created_on`,`published_on`,`updated_on`,`created_by_id`,`updated_by_id`,`position`,`is_protected`,`needs_login`)
VALUES
	(NULL,'Tag','tag','Tag','','',11,0,'tagger',101,0,'2010-12-17 17:22:44',NULL,'2010-12-28 17:37:08',1,1,0,0,2);
UNLOCK TABLES;


# Dump of table wlf_page_part
# ------------------------------------------------------------

LOCK TABLES `{prefix}page_part` WRITE;
INSERT INTO `{prefix}page_part` (`id`,`name`,`filter_id`,`content`,`content_html`,`page_id`)
VALUES
	(NULL,'body','','<?php $pages = $this->tagger->pagesByTag(); ?>\r\n<?php if($pages): ?>\r\n	<h3>You are viewing pages in \"<?php echo $this->tagger->tag(); ?>\"</h3>\r\n	<?php foreach ($pages as $page): ?>\r\n	<div class=\"entry\">\r\n	  <h3><?php echo $page->link(); ?></h3>\r\n<?php echo $page->content(); ?>\r\n  <p class=\"info\">Posted by <?php echo $page->author(); ?> on <?php echo $page->date(); ?>  \r\n     <br />tags: <?php echo Tags::tag_links($page->tags(), array(\'delimiter\' => \', \')); ?>\r\n  </p>\r\n	</div>\r\n	<?php endforeach ?>\r\n<?php endif ?>','<?php $pages = $this->tagger->pagesByTag(); ?>\r\n<?php if($pages): ?>\r\n	<h3>You are viewing pages in \"<?php echo $this->tagger->tag(); ?>\"</h3>\r\n	<?php foreach ($pages as $page): ?>\r\n	<div class=\"entry\">\r\n	  <h3><?php echo $page->link(); ?></h3>\r\n<?php echo $page->content(); ?>\r\n  <p class=\"info\">Posted by <?php echo $page->author(); ?> on <?php echo $page->date(); ?>  \r\n     <br />tags: <?php echo Tags::tag_links($page->tags(), array(\'delimiter\' => \', \')); ?>\r\n  </p>\r\n	</div>\r\n	<?php endforeach ?>\r\n<?php endif ?>', LAST_INSERT_ID());
UNLOCK TABLES;


# Dump of table wlf_snippet
# ------------------------------------------------------------

LOCK TABLES `{prefix}snippet` WRITE;
INSERT INTO `{prefix}snippet` (`id`,`name`,`filter_id`,`content`,`content_html`,`created_on`,`updated_on`,`created_by_id`,`updated_by_id`,`position`)
VALUES
	(NULL,'tags','','<h3>Tags</h3>\r\n<?php Tags::render(array(\'parent\' => 4)); ?>','<h3>Tags</h3>\r\n<?php Tags::render(array(\'parent\' => 4)); ?>','2010-12-17 17:22:44','2010-12-28 18:48:25',1,1,NULL),
	(NULL,'tagger_tpl_default','','<ul class=\"tagger\">\r\n	<?php foreach ($tags as $key => $value): ?>\r\n		<?php $key_case = ($tag_case == 1) ? ucfirst($key) : strtolower($key); ?>\r\n		<li><a href=\"<?php echo tag_url($tagger_page) . slugify($key) . URL_SUFFIX ?>\" title=\"<?php echo $key; ?> tagged <?php echo $value; ?> items\"><?php echo htmlspecialchars_decode($key_case) ?></a></li>\r\n	<?php endforeach; ?>\r\n</ul>','<ul class=\"tagger\">\r\n	<?php foreach ($tags as $key => $value): ?>\r\n		<?php $key_case = ($tag_case == 1) ? ucfirst($key) : strtolower($key); ?>\r\n		<li><a href=\"<?php echo tag_url($tagger_page) . slugify($key) . URL_SUFFIX ?>\" title=\"<?php echo $key; ?> tagged <?php echo $value; ?> items\"><?php echo htmlspecialchars_decode($key_case) ?></a></li>\r\n	<?php endforeach; ?>\r\n</ul>','2010-12-28 04:24:07','2010-12-28 13:57:23',1,1,NULL),
	(NULL,'tagger_tpl_count','','<ul class=\"tagger\">\r\n	<?php foreach ($tags as $key => $value): ?>\r\n		<?php $key_case = ($tag_case == 1) ? ucfirst($key) : strtolower($key); ?>\r\n		<li><a href=\"<?php echo tag_url($tagger_page) . slugify($key) . URL_SUFFIX ?>\" title=\"<?php echo $key; ?> tagged <?php echo $value; ?> items\"><?php echo htmlspecialchars_decode($key_case) ?> (<?php echo $value ?>)</a></li>\r\n	<?php endforeach; ?>\r\n</ul>','<ul class=\"tagger\">\r\n	<?php foreach ($tags as $key => $value): ?>\r\n		<?php $key_case = ($tag_case == 1) ? ucfirst($key) : strtolower($key); ?>\r\n		<li><a href=\"<?php echo tag_url($tagger_page) . slugify($key) . URL_SUFFIX ?>\" title=\"<?php echo $key; ?> tagged <?php echo $value; ?> items\"><?php echo htmlspecialchars_decode($key_case) ?> (<?php echo $value ?>)</a></li>\r\n	<?php endforeach; ?>\r\n</ul>','2010-12-28 13:05:15','2010-12-28 13:08:27',1,1,NULL);
UNLOCK TABLES;