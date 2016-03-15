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

function getQuantifierAction($s) {
  $limit = 49;
  $amount = -1;

  if($s == '?') { // zero or one
    if(mt_rand(0, 99) > 49)
      $amount = 1;
    $amount = 0;
  } elseif($s == '+' || $s == '*') {
    $amount = 0;
    // One or more, so we need at least one
    if($s == '+') {
      // But we don't want too many
      $limit *= 1.25;
      $amount++;
    }

    while(mt_rand(0, 99) > $limit) {
      $amount++;
      $limit *= 1.25;
    }
  }

  return $amount;
}

function parseX($cs) {
  echo $cs . "\n";
  $foundParens = preg_match('/\(([^(]*?)\)/', $cs, $matches, PREG_OFFSET_CAPTURE);
  if($foundParens === 0) return;

  $match = $matches[1];
  $quantifier = substr($cs, $matches[1][1] + strlen($matches[1][0])+1, 1);

  // Make the string an array so we can multiply it
  $parsed = array(substr($cs, $match[1], strlen($match[0])));

  // Evaluate quantifier
  $extraChar = 0; // If we need to remove an extra character when substringing
  $quantifierAction = getQuantifierAction($quantifier);
  if($quantifierAction >= 0) {
    $extraChar = 1;
    // parsed = parsed * quantifier action
    $parsed = array_fill(0, $quantifierAction, $parsed[0]);
  }

  // Remove pipes, this needs to be done after multiplication to ensure they get
  // different evaluations.
  foreach ($parsed as &$s) {
    $or = explode('|', $s);
    $s = $or[array_rand($or, 1)];
  }

  // Concat the evaluated string
  $innerEvaluted = implode(',', $parsed);

  // Put it back without quantifier and parens.
  $cs = substr_replace($cs, $innerEvaluted, $match[1] - 1, strlen($match[0]) + 2 + $extraChar);

  // Recurse until we have nothing more to parse
  parseX($cs);
}

parseX('(a,b,(c,d,(f,g)+),(a|b|c))*') . "\n";

//$dtx = new DTDtoXML(file_get_contents("test.dtd"));
