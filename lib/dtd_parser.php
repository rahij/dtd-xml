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

function getQuantifier($s) {
  if($s == '?') { // zero or one
    $multiply = decision(0, 1);
  } elseif($s == '+') { // one or more
    $multiply = decision(0, 1);
  } elseif($s == '*') { // zero or more
    $multiply = decision(0, 1);
  } else {
    return [false, 1];
  }

  return [true, $s];
}


function parseX($cs) {
  // Find leftmost parenthesis
  $currentChar = 0;
  $endChar = 0;
  $parenCount = 0;
  $foundParenthesis = false;
  for($i = 0; $i < strlen($cs); $i++) {
    $char = substr($cs, $i, 1);
    if($char == '(') {
      $parenCount++;
      if(!$foundParenthesis) { $currentChar = $i + 1; }
      $foundParenthesis = true;
    }

    if($char == ')') {
      $parenCount--;
    }

    if($parenCount == 0 && $foundParenthesis) {
      $endChar = $i;
      break;
    }
  }
  if($foundParenthesis) {
    print_r(getQuantifier(@$cs[$endChar + 1]));
    print(substr($cs, $currentChar, $endChar - $currentChar) . "\n");
    $end = parseX(substr($cs, $currentChar, $endChar - $currentChar));
    if($end) {
      return substr($cs, $currentChar, $endChar - $currentChar);
    } else {
      return $cs;
    }
  } else {
    return true;
  }
}

//parseX("(to+,(from|heading)*,body)+");
parseX("(a, b,(c, d,(e, f)*))+");

/*
function parseContentSpecification($contentSpecification) {
  $parsedCharacters = 0;
  while($parsedCharacters < size($contentSpecification)) {
    if (substr($contentSpecification, $parsedCharacters, 1) == '(') {
      $cs = explode($contentSpecification);
      $parenCount = 1;
      for($i = $parsedCharacters; $i < size($cs); $i++) {
        if($cs[$i] == '(') { $parenCount++; }
        if($cs[$i] == ')') { $parenCount--; }
        if($parenCount == 0) {
          $endCharacter = $i;
          break;
        }
      }

      // Found matching parenthesis
      $multiply = 1;
      if($cs[$endCharacter + 1] == '?') { // zero or one
        $multiply = decision(0, 1);
        $cs[$endCharacter + 1] = '';
      } elseif($cs[$endCharacter + 1] == '+') { // one or more
        $multiply = decision(0, 1);
        $cs[$endCharacter + 1] = '';
      } elseif($cs[$endCharacter + 1] == '*') { // zero or more
        $multiply = decision(0, 1);
        $cs[$endCharacter + 1] = '';
      }
      $inside = replaceParenthesis($cs, $parsedCharacters, $endCharacter);

      for($i = $parsedCharacters; $i < $endCharacter; i++) {
        $cs[$i] = '';
      }
      $cs[$parsedCharacter] = $inside;
      $contentSpecification = implode($cs);
    }

    $parsedCharacters++;
  }
}

function parseParenthesis($cs, $start, $end) {
  $contentSpecification = implode($cs);
  $contentSpecification = substr($contentSpecification, $start, $end);
  return parseContentSpecification($contentSpecification);
}*/

//$dtx = new DTDtoXML(file_get_contents("test.dtd"));



/*$dtd = \Soothsilver\DtdParser\DTD::parseText(file_get_contents("test.dtd"));
print_r($dtd);
foreach($dtd->generalEntities as $entity){
  echo $entity->Name . ": " . $entity->replacementText . "\n";
}

foreach($dtd->parameterEntities as $entity) {
  echo $entity->Name . ": " . $entity->replacementText . "\n";
}*/

//evaluateContentSpecification($dtd->elements[key($dtd->elements)]->contentSpecification);
