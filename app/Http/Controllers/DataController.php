<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\DTDtoXML;
use DB;

use Illuminate\Http\Request;

class DataController extends Controller
{
  public function default_dtd() {
    return file_get_contents(public_path().'/data/default.dtd');
  }

  public function generate(Request $request) {
    $dtd = $request->input('dtd');
    try {
      return DTDtoXML::parseDTD($dtd);
    } catch(\Exception $e) {
      return $e->getMessage();
    }
  }
  
  public function savexml(Request $request) {
	DB::table('savedxml')->insert(
		['user_id' => $request->input('user_id'),
        'savedxml_name' => $request->input('savedxml_name'),
        'xml' => $request->input('xml')]
    );
  }
  
  public function showsave(Request $request) {
	$saves = DB::table('savedxml')->where('user_id', $request->input('user_id'))->get();
	
	echo "<table>";
	echo "<tr>";
	echo "<th class=\"firstcol\">";
	echo "Name";
	echo "</th>";
	echo "<th class=\"secondcol\">";
	echo "Download";
	echo "</th>";
	echo "<th class=\"thirdcol\">";
	echo "Delete";
	echo "</th>";
	echo "</tr>";
	
	foreach ($saves as $save) {
		$xml_id = $save->id;
		$xml_name = $save->savedxml_name;
		echo "<tr>";
		
		echo "<td class=\"xmlname\">";
		echo $xml_name;
		echo "</td>";
		echo "<td>";
		echo "<a xml_id=\"$xml_id\" class=\"btn btn-primary download secondcol\" download=\"$xml_name\">Download</a>";
		echo "</td>";
		echo "<td>";
		echo "<a xml_id=\"$xml_id\" class=\"btn btn-primary delete thirdcol\">Delete</a>";
		echo "</td>";
		
		echo "</tr>";
	}
	echo "</table>";
  }
  
  public function getxml(Request $request) {
	$result = DB::table('savedxml')->where('id', $request->input('xml_id'))->value('xml');

	return $result;
  }  
  
  public function deletexml(Request $request) {
	DB::table('savedxml')->where('id', $request->input('xml_id'))->delete();
  }
}
