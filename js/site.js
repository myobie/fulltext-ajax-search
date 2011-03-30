
function get_results() {
  // do the post ourselves in javascript
  $.post("/templates/search-results.php", $("#search").serialize(), function(data, status, request) {
    $("#results").html(data);
  });
}

// setup to get results after they stop typing
var delay_timeout_id = 0;
function get_results_soon() {
  clearTimeout(delay_timeout_id); // stop any currently running timers
  delay_timeout_id = setTimeout(get_results, 300); // and setup to get results really soon from now
}

$("#search").submit(function(event) {
  event.preventDefault(); // prevent the form from actually submitting
  get_results();
});

$("#q").keyup(function(event) { // for every key they hit
  get_results_soon();
});

// *** filters ***

$("#add-filter-button").click(function(event) {
  event.preventDefault();
  $("#search").append(filter_html);
});

$(".remove-button").live("click", function(event) {
  event.preventDefault();
  $(this).parent().remove(); // find my parent (a p tag) and remove it from the DOM
});

// If the thing to search is a year, then show the other conditional select
// that makes sense.
$("select.field").live("change", function(event) {
  var newClassName; // create a variable for use to shove stuff into

  if (this.value == "books.year") {
    newClassName = "year";
  } else {
    newClassName = "text";
  }

  // The above if statement is the same as this next line:
  // var newClassName = this.value == "books.year" ? "year" : "text";

  this.parentNode.className = "filter " + newClassName;
  // If the parent's class is year, then the css shows the year select.
  // The same is true for the class text.

  var adjacent_input = $(this).nextAll(".content");
  // Only get results if there is any content in the input box for this
  // particular filter row.
  if (adjacent_input.val() != "") {
    get_results_soon();
  }

});

// If they change the year or text selects, then we should probably refresh the
// results.
$("select.year, select.text").live("change", function(event) {
  var adjacent_input = $(this).nextAll(".content");
  // Only get results if there is any content in the input box for this
  // particular filter row.
  if (adjacent_input.val() != "") {
    get_results_soon();
  }
});



