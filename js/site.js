
function get_results() {
  // do the post ourselves in javascript
  $.post("/templates/search-results.php", $("#search").serialize(), function(data, status, request) {
    $("#results").html(data);
  });
}

$("#search").submit(function(event) {
  event.preventDefault(); // prevent the form from actually submitting
  get_results();
});
