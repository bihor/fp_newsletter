#
# Table structure for table 'tx_fpnewsletter_domain_model_log'
#
CREATE TABLE tx_fpnewsletter_domain_model_log (

	gender int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	firstname varchar(255) DEFAULT '' NOT NULL,
	lastname varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	status int(11) DEFAULT '0' NOT NULL,
	securityhash varchar(255) DEFAULT '' NOT NULL,
	retoken text,
	mathcaptcha varchar(5) DEFAULT '' NOT NULL,
	extras varchar(255) DEFAULT '' NOT NULL,
	gdpr tinyint(3) unsigned DEFAULT '0' NOT NULL,
	address varchar(255) DEFAULT '' NOT NULL,
	zip varchar(255) DEFAULT '' NOT NULL,
	city varchar(255) DEFAULT '' NOT NULL,
	region varchar(255) DEFAULT '' NOT NULL,
	country varchar(255) DEFAULT '' NOT NULL,
	phone varchar(255) DEFAULT '' NOT NULL,
	mobile varchar(255) DEFAULT '' NOT NULL,
	fax varchar(255) DEFAULT '' NOT NULL,
	www varchar(255) DEFAULT '' NOT NULL,
	position varchar(255) DEFAULT '' NOT NULL,
	company varchar(255) DEFAULT '' NOT NULL,
	categories varchar(255) DEFAULT '' NOT NULL

);