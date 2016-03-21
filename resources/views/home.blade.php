@extends('layouts.master')

@section('content')
  <div class="content">
    <div class="dtd-form-wrapper">
      <form id="dtd-form" class="form">
        <div class="shadowBox">
          <div class="form-group">
            <h3> Enter your DTD here </h3>
            <textarea class="form-control" placeholder="Enter your DTD here..."></textarea>
          </div>
          <input type="submit" id="submitXMLButton" class="btn btn-primary submit">
        </div>
      </form>
    </div>

    <div class="xml-wrapper">
      <div class="shadowBox">
        <h3> Generated XML </h3>
        <div id="xml-output" class="panel-body"><span style="color:#A5A5A5;">Your XML will be generated here.</span></div>
        <div id="actionButtons">
          <button class="btn btn-primary copy">Select</button>
          <button class="btn btn-primary download">Download XML</button>
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
        $("#actionButtons").show();
      });
    });

    // $("#submitXMLButton").click(function(){
      
    // });
    
  </script>
@endsection

