<?
  require_once("lib/db.php");
  $q = $db->real_escape_string($_POST["q"]);
?>
<h1>Search results</h1>
<?
  $sql = array();

  if (!empty($q)) {
    // add the match condition to the first sql statement
    $sql[] = "match (title, description, year, isbn) against ('$q' in boolean mode)";
  }

  if (!empty($_POST["filters"]) && is_array($_POST["filters"])) {
    $amount = count($_POST["filters"]);
  } else {
    $amount = 0;
  }

  for ($i = 0; $i < $amount; $i++) {
    $field     = $_POST["field"][$i];
    $parts     = explode(".", $field);
    $table     = $parts[0];
    $column    = $parts[1];

    $content   = $_POST["content"][$i];

    $condition = $column == "year" ? $_POST["condition_year"][$i] : $_POST["condition_text"][$i];

    if ($table == "authors") {
      // query authors and find the id's of the books that match
      $author_column = $db->real_escape_string($column);
      $author_cond = 'LIKE';
      $author_value = preg_replace("/t/", $content, $db->real_escape_string($condition));
      $author_ids_sql = "select id from authors where $author_column $author_cond '$author_value'";
      $book_ids_sql = "select book_id from authors_books_joiner where author_id in ($author_ids_sql)";

      // build a filter for books that match the id's found (if any)
      $real_column = "id";
      $real_cond = "IN";
      $real_value = "($book_ids_sql)";
    } else {
      // build a filter for books that match the conditions
      $real_column = $db->real_escape_string($column);

      if ($column == "year") {
        // make sure we can't use anything other than these three conditions for the year
        if ($condition == '=' || $condition == '<' || $condition == '>') {
          $real_cond = $condition;
        } else {
          $real_cond = '='; // if it's not one of those three let's just use =
        }
        $real_value = "'" . $db->real_escape_string($content) . "'";
      } else {
        // sanitize the condition and then substitute the value in
        $real_cond = 'LIKE';
        $real_value = "'" . preg_replace("/t/", $content, $db->real_escape_string($condition)) . "'";
      }

    }

    $sql[]= "$real_column $real_cond $real_value"; // put everything in
  }

  if (!empty($sql)) {
    $real_sql = "select * from books where " . implode(" AND ", $sql); // combine
  } else {
    $real_sql = "select * from books";
  }

  $result = $db->execute($real_sql);

  if ($result->num_rows == 0) {
    echo "<p>No results.</p>";
  } else {
    // output a book.php for each book row
    while($book = $result->fetch_assoc()) {
      include "templates/book.php";
    }
  }
?>

