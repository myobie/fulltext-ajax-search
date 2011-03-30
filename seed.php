<?php

require_once('lib/db.php');

$book_data = array();

$book_data[]= array(
  "title"         => "Advanced PHP for Web professionals",
  "author_name"   => "Christopher Cosentino",
  "year"          => "2003",
  "description"   => "Includes index.",
  "isbn"          => "0130085391"
);

$book_data[]= array(
  "title"         => "Ajax with PHP 5",
  "author_name"   => "Andrew G. Curioso",
  "year"          => "2007",
  "description"   => "Electronic reproduction.Boston, Mass.: Safari Books Online2007s2007<br>maun sMode of access: World Wide Web.",
  "isbn"          => "0596514034"
);

$book_data[]= array(
  "title"         => "Beginning Ajax with PHP",
  "author_name"   => "Lee Babin",
  "year"          => "2007",
  "description"   => "Beginning Ajax with PHP from novice to professional",
  "isbn"          => "6610852715"
);

$book_data[]= array(
  "title"         => "Beginning PHP and MySQL 5",
  "author_name"   => "W. Jason Gilmore",
  "year"          => "2006",
  "description"   => "Beginning PHP and MySQL 5 from novice to professional, second edition",
  "isbn"          => "1430201177"
);

$book_data[]= array(
  "title"         => "How to do everything with PHP & MySQL",
  "author_name"   => "Vikram Vaswani",
  "year"          => "2005",
  "description"   => "Includes index.",
  "isbn"          => "0072257954"
);

function insert_book($book) {
  global $db;
  $book_result = $db->execute("
    insert into books set
    title       = '{$book["title"]}',
    year        = '{$book["year"]}',
    description = '{$book["description"]}',
    isbn        = '{$book["isbn"]}'
  ");

  $book_id = $db->insert_id;

  $author_id = insert_author_if_not_exists(array("name" => $book["author_name"]));

  $db->execute("
    insert into authors_books_joiner set
    book_id   = '$book_id',
    author_id = '$author_id'
  ");

  return $book_id;
}

function insert_author_if_not_exists($author) {
  global $db;
  $result = $db->execute("select * from authors where name = '{$author["name"]}'");

  if ($result->num_rows == 0) {
    $insert_result = $db->execute("insert into authors set name = '{$author["name"]}', bio = '{$author["bio"]}'");
    $id = $db->insert_id;
  } else {
    $author_from_db = $result->fetch_assoc();
    $id = $author_from_db["id"];
  }

  return $id;
}

foreach($book_data as $book) {
  insert_book($book);
}

