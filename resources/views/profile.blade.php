@extends('layouts.master')

@section('content')
<div class="show-wrapper">
<h1>Here's the XML files you saved so far!</h1>
<div id="allsaves"></div>
</div>
@endsection

@section('javascripts')
  @parent

  <script type="text/javascript">
    $("document").ready(function() {
		var isUser = "{{ Auth::check() }}";
		var userId = "{{ Auth::id() }}"
		var result;
		var selected_xml;
		
		$.get("/data/showsave", function(response) {
            $.ajax({
                type: "GET",
                url: "/data/showsave",
                data: {
					user_id: userId,
                },
                success: function(data) {
                   $("#allsaves").html(data);
                }
            });
        });
		
        function getxml(id) {			
            $.ajax({
                type: "POST",
                url: "/data/getxml",
                data: {
                    xml_id: id,
                },
                success: function(data) {
					result = data;
                }
            });
        };		
		
        function deletexml(id) {			
            $.ajax({
                type: "POST",
                url: "/data/deletexml",
                data: {
                    xml_id: id,
                },
                success: function(data) {
                }
            });
        };	
		
        $("#allsaves").on("click", ".download", function(e) {
			selected_xml = $(this).attr('xml_id');
			getxml(selected_xml);
			alert("Success!"); //Need this to ensure that correct xml is given; without it only the previously created xml is given. Maybe syncronicity issues?
			$(this).attr("href", 'data:text/xml;charset=utf-8,' + encodeURIComponent(result));
        });		
		
        $("#allsaves").on("click", ".delete", function(e) {
			selected_xml = $(this).attr('xml_id');
			deletexml(selected_xml);
			alert("Success!"); //Need this to ensure that correct xml is deleted; without it no deletion done at all. Maybe syncronicity issues?
			window.location.reload();
        });		
    });
   </script>
@endsection