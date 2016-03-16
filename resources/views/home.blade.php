@extends('layouts.master')

@section('content')
  <div class="content">
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
@endsection

@section('javascripts')
  @parent

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
@endsection

