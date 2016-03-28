@extends('layouts.master')

@section('content')
  <div class="content">
    <div class="dtd-form-wrapper">
      <form id="dtd-form" class="form">
        <div class="shadowBox">
          <div class="form-group">
            <h3> Enter your DTD here </h3>
            <textarea class="form-control" id="dtd-content" name="dtd-content" placeholder="Enter your DTD here..."></textarea>
          </div>
          <input type="submit" id="submitXMLButton" class="btn btn-primary submit">
        </div>
      </form>
    </div>

    <div class="xml-wrapper">
      <div class="shadowBox">
        <h3> Generated XML </h3>
        <div id="xml-output-wrapper" class="panel-body">
          <textarea class="form-control" id="xml-output" placeholder="Your XML will be generated here."></textarea>
        </div>
        <div id="actionButtons">
          <button class="btn btn-primary copy">Select</button>
          <a class="btn btn-primary download" download="generated-xml">Download XML</a>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('javascripts')
  @parent

  <script type="text/javascript">
    $("document").ready(function() {
      /*
      $("textarea").on('change keyup paste', function(e) {
        console.log($(this));
        $(this).css('height', 0);
        $(this).css('height', $(this).prop('scrollHeight'));
      });*/

      $.get("/data/default_dtd", function(response) {
        $("#dtd-content").val(response);
      });

      $("#dtd-form").on('submit', function(e) {
        e.preventDefault();
        $("#xml-output").html("Generating...");
        $.ajax({
          type: "POST",
          url: "/data/generate",
          data: {
            dtd: $("#dtd-content").val(),
          },
          success: function(data) {
            $("#xml-output").html(data);
            $("#actionButtons").show();
          }
        });
      });

      $(".copy").on("click", function(e) {
        $("#xml-output").select();
      });

      $(".download").on("click", function(e) {
        $(this).attr("href", 'data:text/xml;charset=utf-8,'
          + encodeURIComponent($("#xml-output").val()));
      });
    });
  </script>
@endsection
