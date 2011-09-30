CREATE TABLE __PREFIX__mydownloads_files (
  fid int(11) NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  filename varchar(250) NOT NULL default '',
  submitdate int(10) NOT NULL default '0',
  PRIMARY KEY  (fid),
  KEY lid (lid),
  KEY submitdate (submitdate)
) TYPE=MyISAM;
