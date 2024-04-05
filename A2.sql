create database a2;
use a2;

CREATE table users(
	user_id int not null auto_increment,
	fname varchar (255),
	lname varchar (255),
	username varchar (255),
	password varchar (255),
    primary key (user_id)
);

create table images(
	id int(11) NOT NULL auto_increment,
    name varchar(100) not null,
    image varchar(250) not null,
    primary key (id)
    );
    
select * from users;
select * from images;


