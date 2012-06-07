drop database if exists ecsite_db;

create database ecsite_db;
grant all on `ecsite_db`.* to `ecsite_user`@localhost identified by 'ecsite_pass';
flush privileges;
