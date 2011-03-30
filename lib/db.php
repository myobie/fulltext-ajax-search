<?php

class DatabaseConnection extends mysqli {

  function __construct() {
    parent::__construct("localhost", "root", "", "adv_f_a_development");
  }

  function execute($sql = array()) {
    if (is_array($sql)) {
      $result = array();
      foreach ($sql as $statement) {
        $result[]= $this->query($statement);
      }
    } else {
      $result = $this->query($sql);
    }
    return $result;
  }

}

$db = new DatabaseConnection;

function authors_from($book) {
  global $db;
  $author_ids = array();
  $authors = array();

  $book_id = $db->real_escape_string($book["id"]);
  $result = $db->execute("select * from authors_books_joiner where book_id = '$book_id'");
  while ($row = $result->fetch_assoc()) {
    $author_ids[]= $row["author_id"];
  }
  if (!empty($author_ids)) {
    $author_ids_string = implode(",", $author_ids);
    $result_authors = $db->execute("select * from authors where id in ($author_ids_string)");
    while ($author_row = $result_authors->fetch_assoc()) {
      $authors[]= $author_row;
    }
  }
  return $authors;
}

function authors_links_from($book) {
  $authors = authors_from($book);
  ob_start();
  foreach($authors as $author) {
  ?>
    <a href="/author.php?id=<?= $author["id"] ?>"><?= $author["name"] ?></a>
  <?
  }
  $html = ob_get_clean();
  return $html;
}


