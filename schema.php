<?php

$sql = array();

$sql[]= "drop table authors";
$sql[]= "drop table books";
$sql[]= "drop table authors_books_joiner";
$sql[]= "drop table search_content";

$sql[]= "
create table if not exists authors (
  id int(11) not null auto_increment,
  name varchar(200) not null,
  bio text,
  primary key (id)
)";

$sql[]= "
create table if not exists books (
  id int(11) not null auto_increment,
  title varchar(200) not null,
  description text,
  year varchar(4),
  isbn varchar(100),
  primary key (id),
  fulltext (title, description, year, isbn)
)";

$sql[]= "
create table if not exists authors_books_joiner (
  book_id int(11) not null,
  author_id int(11) not null,
  primary key (book_id, author_id)
)";

require_once("lib/db.php");

define("DEBUG", true);

$db->execute($sql);
