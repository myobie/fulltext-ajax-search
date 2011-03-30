
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

// setup to get results after they stop typing
var delay_timeout_id = 0;

$("#q").keyup(function(event) { // for every key they hit
  clearTimeout(delay_timeout_id); // stop any currently running timers
  delay_timeout_id = setTimeout(get_results, 300); // and setup to get results really soon from now
});

// *** filters ***

$("#add-filter-button").click(function(event) {
  event.preventDefault();
  $("#search").append(filter_html);
});

