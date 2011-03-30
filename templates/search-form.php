<form id="search" method="post" action="search.php">
  <p id="q-group">
    <input type="search" placeholder="Search Everything" name="q" id="q">
    <button type="submit">Search</button>
    <a href="#add-filter" id="add-filter-button">Add filter</a>
  </p>

  <? ob_start(); // capture all this html so we can stuff it into a javascript variable ?>
  <p class="filter text">
    <select name="field[]" class="field">
      <option value="authors.name">Author Name</option>
      <option value="authors.bio">Author Bio</option>
      <option value="books.title">Book Title</option>
      <option value="books.description">Book Description</option>
      <option value="books.year">Book Year</option>
      <option value="books.isbn">Book ISBN</option>
    </select>

    <select class="text" name="condition_text[]">
      <option value="%t%">contains</option>
      <option value="t%">begins w/</option>
      <option value="%t">ends w/</option>
      <option value="t">is</option>
    </select>

    <select class="year" name="condition_year[]">
      <option value="<">before</option>
      <option value=">">after</option>
      <option value="=">during</option>
    </select>

    <input type="text" name="content[]" class="content">

    <!-- this input is just to count how many filters there are -->
    <input type="hidden" value="true" name="filters[]">

    <a href="#remove-filter" class="remove-button">Remove this filter</a>
  </p>
  <?
    $html = ob_get_clean();
    $html_for_js = rawurlencode($html);
  ?>
  <script>
    var filter_number = 1;
    var filter_html = unescape("<?= $html_for_js ?>");
  </script>
</form>

