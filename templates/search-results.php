<?
  require_once("../lib/db.php");
  $q = $db->real_escape_string($_POST["q"]);
?>
<h1>Search results</h1>
<?
  $sql = "select * from books";

  // -> Make a query here use $q
  if (!empty($_POST['q'])) {
    $sql .= " where match (title, description, year, isbn) against ('$q' in boolean mode)";
  }

  if (!empty($_POST["filters"])) { // if any filters were posted, then count them
    $amount = count($_POST["filters"]);

    if (!preg_match("/where/", $sql)) {

      // if the sql statement doesn't already have a where, then add one
      $sql .= " where";

    } else {

      // if it has a where, then we will need an and after the match
      $sql .= " and";

    } // end of the if
  } else {

    $amount = 0;

  } // end of the if

  $filters = array();

  for ($i = 0; $i < $amount; $i++) {

    // find this particular filter
    $field     = $_POST["field"][$i];

    // pull out all of it's information
    $parts     = explode(".", $field);
    $table     = $parts[0];
    $column    = $parts[1];

    $content   = $_POST["content"][$i];

    if ($column == "year") {
      $condition = $_POST["condition_year"][$i]; // for years, look at the year condition
    } else {
      $condition = $_POST["condition_text"][$i]; // for everything else, look at the text condition
    }
    if ($table == "authors") {
      // query authors and find the id's of the books that match
      $author_column = $db->real_escape_string($column);
      $author_cond = 'LIKE'; // since it's text, we will always do a LIKE query
      $author_value = preg_replace("/t/", $content, $db->real_escape_string($condition)); // replace the t with the actual content

      // build a sub-select
      $author_ids_sql = "select id from authors where $author_column $author_cond '$author_value'";
      $book_ids_sql = "select book_id from authors_books_joiner where author_id in ($author_ids_sql)";

      // build a filter for books that match the id's found (if any)
      $real_column = "id";
      $real_cond = "IN";
      $real_value = "($book_ids_sql)";
    } else {
      // build a filter for books that match the conditions
      $real_column = $db->real_escape_string($column);

      // if it's year, use the property conditions
      if ($column == "year") {

        // make sure we can't use anything other than these three conditions for the year
        if ($condition == '=' || $condition == '<' || $condition == '>') {
          $real_cond = $condition;
        } else {
          $real_cond = '='; // if it's not one of those three let's just use =
        }
        $real_value = "'" . $db->real_escape_string($content) . "'";

      } else { // else just do the normal LIKE

        // sanitize the condition and then substitute the value in
        $real_cond = 'LIKE';
        $real_value = "'" . preg_replace("/t/", $content, $db->real_escape_string($condition)) . "'";

      } // end of the if

    }

    $filters[]= "$real_column $real_cond $real_value"; // put everything in

  }

  if (!empty($filters)) {
    $sql .= " " . implode(" AND ", $filters);
  }

  $result = $db->execute($sql);

  if ($result->num_rows == 0) {
    echo "<p>No results.</p>";
  } else {
    // output a book.php for each book row
    while($book = $result->fetch_assoc()) {
      include "book.php";
    }
  }
?>

