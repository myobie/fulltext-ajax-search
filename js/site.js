if ($("#search").length == 1) {

  $("#add-filter-button").click(function(event) {
    event.preventDefault();
    $("#search").append(filter_html);
  });

  $(".remove-button").live("click", function(event) {
    event.preventDefault();
    $(this).parent().remove();
    get_results();
  });

  $("select.field").live("change", function(event) {
    var newClassName = this.value == "books.year" ? "year" : "text";
    this.parentNode.className = "filter " + newClassName;

    if ($(this).nextAll(".content").val() != "")
      get_results_soon();
  });

  $("select.year, select.text").live("change", function(event) {
    if ($(this).nextAll(".content").val() != "")
      get_results_soon();
  });

  $("#search").submit(function(event) {
    event.preventDefault();
    get_results();
  });

  var get_results_soon_timeout_id = 0; // so we can clear a timeout if needed

  function get_results_soon() {
    clearTimeout(get_results_soon_timeout_id); // cancel any before this
    get_results_soon_timeout_id = setTimeout(get_results, 300); // get results in 0.3 seconds
  }

  $("#q, .content").live("keyup", function(event) {
    get_results_soon();
  });

  function get_results() {
    $.post("/search-results.php", $("#search").serialize(), function(data, status, request) {
      $("#results").html(data);
    });
  }


}
