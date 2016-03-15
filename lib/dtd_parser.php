<?php
$loader = require '../vendor/autoload.php';

class DTDtoXML {
  private $dtdParser;
  private $xmlTree;

  public function __construct($dtd) {
      $this->dtdParser = \Soothsilver\DtdParser\DTD::parseText($dtd);
      print_r($this->dtdParser);

      if($this->dtdParser->isWellFormedAndValid()) {
        $this->generateXML();
      } else {
        print_r($this->dtdParser->errors);
      }
  }

  public function errors() {
    return $this->dtdParser->errors;
  }

  public function generateXML() {
    $this->xmlTree = new DOMDocument('1.0', 'UTF-8');

    $root = $this->dtdParser->elements[key($this->dtdParser->elements)];

    $rootElement = $this->xmlTree->createElement($root->type);

    // loop through attrlist and add attributes
    $this->xmlTree->appendChild($rootElement);

    $this->addChildren($root, $rootElement);
    $this->evaluateContentSpecification($root);
  }

  private function addChildren($dtdNode, $xmlNode) {
    $dtdNode->contentSpecification;
  }

  public function writeXML() {
    if($XMLtree->validate()) {
      return $XMLtree->saveXML();
    } else {
      // errors??
    }
  }

  private function decision($min, $max) {

  }

  private function evaluateContentSpecification($contentSpecification) {
    $lastCh = substr($contentSpecification, -2, 2);

    $multiply = 0;
    if($lastCh === ')?' ) { // Zero or one
      $cs = substr($contentSpecification, 0, -1);
      $multiply = decision(0, 1);
    } elseif ($lastCh === ')*') { // Zero or many
      $cs = substr($contentSpecification, 0, -1);
      $multiply = decision(0, 10);
    } elseif ($lastCh == ')+') { // One or many
      $cs = substr($contentSpecification, 0, -1);
      $multiply = decision(1, 10);
    } else { // Exactly one
      $cs = $contentSpecification;
    }

    // Find matching parenthesis



    $cs = substr($cs, 1, -1);  // Remove parens

    $parts = explode('|', $cs); // Evaluate each part
    foreach($parts as $part) {
      $piece = explode(',', $part);
      print_r($piece);
    }
  }
}

function decision($min, $max) {
  return 1;
}

function hasQuantifier($s) {
  if($s == '?') { // zero or one
    return true;
  } elseif($s == '+') { // one or more
    return true;
  } elseif($s == '*') { // zero or more
    return true;
  }

  return false;
}

function parseX($cs) {
  echo $cs . "\n";
  $foundParens = preg_match('/\(([^(]*?)\)/', $cs, $matches, PREG_OFFSET_CAPTURE);
  if($foundParens === 0) return;

  $match = $matches[1];
  $quantifier = substr($cs, $matches[1][1] + strlen($matches[1][0])+1, 1);

  // Evaluate quantifier
  $extraChar = 0;
  if($quantifierAction = hasQuantifier($quantifier)) {
    // Do thing
    echo $quantifierAction;
    $extraChar = 1;
  }
  $parsed = substr($cs, $match[1], strlen($match[0]));
  $cs = substr_replace($cs, $parsed, $match[1] - 1, strlen($match[0]) + 2 + $extraChar);
  parseX($cs);
}

parseX('(a,b,(c,d,(f,g)+),(a|b|c))*') . "\n";

//$dtx = new DTDtoXML(file_get_contents("test.dtd"));
