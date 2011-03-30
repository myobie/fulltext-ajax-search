$("#search").submit(function(event) {
  event.preventDefault(); // prevent the form from actually submitting

  // do the post ourselves in javascript
  $.post("/templates/search-results.php", $(this).serialize(), function(data, status, request) {
    $("#results").html(data);
  });

});
