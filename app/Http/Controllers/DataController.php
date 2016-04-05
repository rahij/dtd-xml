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
		$xml_raw = $save->xml;
		
		//Code below is PHP way of encodeURIComponent
		//Needed to convert xml from database into downloadable format
		//Reference: http://stackoverflow.com/questions/1734250/what-is-the-equivalent-of-javascripts-encodeuricomponent-in-php
		$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
        $xml_output = strtr(rawurlencode($xml_raw), $revert);
		echo "<tr>";
		
		echo "<td class=\"xmlname\">";
		echo $xml_name;
		echo "</td>";
		echo "<td>";
		echo "<a xml_id=\"$xml_id\" class=\"btn btn-primary download secondcol\" href=\"data:text/xml;charset=utf-8,$xml_output\" download=\"$xml_name\">Download</a>";
		echo "</td>";
		echo "<td>";
		echo "<a xml_id=\"$xml_id\" class=\"btn btn-primary delete thirdcol\">Delete</a>";
		echo "</td>";
		
		echo "</tr>";
	}
	echo "</table>";
  }
  
  public function deletexml(Request $request) {
	DB::table('savedxml')->where('id', $request->input('xml_id'))->delete();
  }
  
}
