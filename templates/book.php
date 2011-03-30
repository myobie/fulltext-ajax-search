<article class="book">
  <header>
    <hgroup>
      <h1><?= $book["title"] ?></h1>
      <h2>by <?= authors_links_from($book) ?> - <?= $book["year"] ?></h2>
    </hgroup>
  </header>

  <p><?= $book["description"] ?></p>

  <footer>
    <p class="isbn">ISBN: <?= $book["isbn"] ?></p>
  </footer>
</article>
