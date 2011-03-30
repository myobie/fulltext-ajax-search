<?
  require_once("lib/db.php");
  $q = $db->real_escape_string($_POST["q"]);
?>
<h1>Search results</h1>
<?
  $sql = "";

  // -> Make a query here use $q
  $result = $sql = "select * from books where match (title, description, year, isbn) against ('$q' in boolean mode)";

  $result = $db->execute($sql);

  if ($result->num_rows == 0) {
    echo "<p>No results.</p>";
  } else {
    // output a book.php for each book row
    while($book = $result->fetch_assoc()) {
      include "templates/book.php";
    }
  }
?>

