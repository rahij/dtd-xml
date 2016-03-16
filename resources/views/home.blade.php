<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Open Sans:100" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="container">
            <div class="content">
              <div class="title">DTD -> XML</div>

              <div
              <div class="dtd-form-wrapper">
                <form id="dtd-form" class="form">
                  <div class="form-group">
                    <label>Enter your DTD here</label>
                    <textarea class="form-control"></textarea>
                  </div>
                  <input type="submit" class="btn btn-primary">
                </form>
              </div>
              <br />
              <div class="xml-wrapper">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">Generated XML</h3>
                  </div>
                  <div id="xml-output" class="panel-body">

                  </div>
                </div>
              </div>
            </div>
        </div>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script type="text/javascript">
          $("document").ready(function() {
            $("#dtd-form").on('submit', function(e) {
              e.preventDefault();
              $("#xml-output").html("Generating...");
              //AJAX request here
              $("#xml-output").html("Random XML Output");
            });
          });
        </script>
    </body>
</html>
