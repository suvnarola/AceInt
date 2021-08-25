-- MySQL dump 9.07
--
-- Host: localhost    Database: events
---------------------------------------------------------
-- Server version       4.0.12-max-nt

--
-- Table structure for table 'event'
--

CREATE TABLE event (
  event_id int(4) unsigned NOT NULL auto_increment,
  event_name varchar(30) NOT NULL default '',
  event_start_date int(11) unsigned NOT NULL default '0',
  event_end_date int(11) unsigned NOT NULL default '0',
  event_venue varchar(60) NOT NULL default '',
  event_street_no varchar(10) NOT NULL default '',
  event_street varchar(20) NOT NULL default '',
  event_city varchar(20) NOT NULL default '',
  event_state varchar(12) NOT NULL default '',
  event_country varchar(16) NOT NULL default '',
  event_admin tinyint(1) unsigned NOT NULL default '0',
  event_description text NOT NULL,
  event_contact text NOT NULL,
  event_time varchar(30) NOT NULL default '',
  event_info tinyint(1) NOT NULL default '0',
  event_icon tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (event_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'event'
--

INSERT INTO event VALUES (1,'E Banc Trade Extravaganza',1065794400,1065967200,'Quad Park, Kawana','','','brisbane','qld','australia',0,'The E Banc Trade Extravaganza is on again.','','9:00 - 5:00',1,0);
INSERT INTO event VALUES (12,'Event Five',1059141600,1059141600,'Place One','1','Street','Nowhere','QLD','Netherlands',0,'This is a Description','E Banc Netherlands','9:00 - 5:00',0,0);
INSERT INTO event VALUES (8,'Event One',1076335200,1077112800,'Place One','','','brisbane','qld','australia',0,'0','','9:00 - 5:00',0,0);
INSERT INTO event VALUES (9,'Event Two',1058968800,1058968800,'Place One','','','brisbane','qld','australia',0,'0','','9:00 - 5:00',0,0);
INSERT INTO event VALUES (10,'Event Three',1059314400,1059314400,'Place One','','','brisbane','qld','australia',0,'0','','9:00 - 5:00',0,0);
INSERT INTO event VALUES (11,'Event Four',1059055200,1059141600,'Place One','','','brisbane','qld','australia',0,'0','','9:00 - 5:00',0,0);
INSERT INTO event VALUES (13,'Event Six',1059141600,1059487200,'Place One','','','brisbane','qld','australia',0,'0','','9:00 - 5:00',0,0);
INSERT INTO event VALUES (14,'Event Seven',1058709600,1059573600,'Place One','1','Street','nowhere','qld','australia',0,'This is a Description','Head Office: Ph 5441 5461','9:00 - 5:00',0,0);
INSERT INTO event VALUES (15,'Information Evening',1060696800,1060696800,'Wollongong','','','brisbane','qld','australia',0,'This is a Demo','','9:00 - 5:00',1,0);
INSERT INTO event VALUES (16,'Event Nine',1058882400,1058882400,'Place One','','','brisbane','qld','australia',0,'0','','9:00 - 5:00',0,0);
INSERT INTO event VALUES (17,'Event Ten',1059314400,1059314400,'Place One','','','brisbane','qld','australia',0,'0','','9:00 - 5:00',0,0);
INSERT INTO event VALUES (18,'Event Twelve',1061388000,1061388000,'Place Ten','','','brisbane','qld','australia',0,'This is a test event','','9:00 am - 10:00 pm',1,0);
INSERT INTO event VALUES (19,'Event Thirteen',1061388000,1061388000,'Place Ten','','','brisbane','qld','australia',0,'This is a test event.\r\n;aksfdasj\\\\\\\';\\\\\\\';lopupojfgkihre[\\\\\\\'\\\\\\\'\\\\\\\'\\\\\\\'\\\\\\\'kf;\\\\\\\"\\\\\\\"dfh9862394198^*&%$@)$(','','9:00 am - 10:00 pm',1,0);