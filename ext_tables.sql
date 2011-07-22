#
# Table structure for table 'tx_hypeerror_errorlog'
#
CREATE TABLE tx_hypeerror_errorlog (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(1) DEFAULT '0' NOT NULL,
	fe_group int(11) DEFAULT '0' NOT NULL,
	editlock tinyint(1) DEFAULT '0' NOT NULL,

	domain int(11) DEFAULT '0' NOT NULL,
	path text,
	host varchar(255) DEFAULT '' NOT NULL,
	url text,
	referrer text,
	user_agent varchar(255) DEFAULT '' NOT NULL,
	ip_address varchar(15) DEFAULT '' NOT NULL,
	reason varchar(255) DEFAULT '' NOT NULL,
	count int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid)
);

#
# Table structure for table 'sys_domain'
#
CREATE TABLE sys_domain (
	tx_hypeerror_error_page int(11) DEFAULT '0' NOT NULL
);