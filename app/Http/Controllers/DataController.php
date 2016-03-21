<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\DTDtoXML;

use Illuminate\Http\Request;

class DataController extends Controller
{
  public function default_dtd() {
    return file_get_contents(public_path().'/data/default.dtd');
  }

  public function generate(Request $request) {
    $dtd = $request->input('dtd');
    $dtd_parser = new DTDtoXML($dtd);
    return $dtd_parser->writeXML();
  }
}
