<h1>Showing all books</h1>

<?
require_once('lib/db.php');
$result = $db->execute("select * from books");
$books = array();
while ($book = $result->fetch_assoc()) {
  include('templates/book.php');
}
?>
