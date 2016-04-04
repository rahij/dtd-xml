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
				<button id="copyButton" class="btn btn-primary copy">Select</button>
				<a id="downloadButton" class="btn btn-primary download" download="generated-xml">Download XML</a>
				<a id="saveButton" class="btn btn-primary show-save">Save XML</a>
			</div>
		</div>
		<div class="save-wrapper">
			<div class="shadowBox">
				<h3> Save XML </h3>
				<div id="xml-savename-wrapper">
					<input type="text" name="xml-name" class="form-control" id="xml-name" placeholder="Enter your desired XML name here..."></input>
				</div>
				<button id ="save" class="btn btn-primary save">Save!</button>
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

        var isUser = "{{ Auth::check() }}";
		var userId = "{{ Auth::id() }}"
        $(".save-wrapper").hide();
        $("#copyButton").hide();
        $("#downloadButton").hide();
        $("#saveButton").hide();

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
                    if (isUser == true) {
                        document.getElementById("copyButton").style.width = "32%";
                        document.getElementById("downloadButton").style.width = "32%";
                        document.getElementById("saveButton").style.width = "32%";
                        $("#copyButton").show();
                        $("#downloadButton").show();
                        $("#saveButton").show();
						$("#actionButtons").show();
						$('html,body').animate({scrollTop: $(".xml-wrapper").offset().top},'fast');
                    } else {
                        document.getElementById("copyButton").style.width = "49%";
                        document.getElementById("downloadButton").style.width = "49%";
                        $("#copyButton").show();
                        $("#downloadButton").show();
						$("#actionButtons").show();
						$('html,body').animate({scrollTop: $(".xml-wrapper").offset().top},'fast');
                    }
                }
            });
        });

        $(".copy").on("click", function(e) {
            $("#xml-output").select();
        });

        $("#saveButton").on("click", function(e) {
            $(".save-wrapper").show();
        });

        $(".download").on("click", function(e) {
            $(this).attr("href", 'data:text/xml;charset=utf-8,' +
                encodeURIComponent($("#xml-output").val()));
        });
		
        $("#save").on("click", function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "/data/savexml",
                data: {
					user_id: userId,
                    xml: $("#xml-output").val(),
					savedxml_name: $("#xml-name").val(),
                },
                success: function(data) {
                   alert("Saved!");
                }
            });
        });		
    });
  </script>
@endsection
