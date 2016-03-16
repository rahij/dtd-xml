<?php
/*
** Usage:
** $dtx = new DTDtoXML(file_get_contents("test.dtd"));
** $dtd->writeXML();
** Returns XML if it was passed a valid DTD, warnings otherwise
**
** TODO: Handle errors / warnings.
** TODO: Make API nicer to use?
** TODO: Make it possible to specify which elements serves as root element.
**          (Right now the topmost element in the DTD is always root)
**
**/

$loader = require '../vendor/autoload.php';

class DTDtoXML {
  private $dtdContent;
  private $dtdParser;
  private $domImplementation;
  private $xmlTree;
  private $faker;

  public function __construct($dtd) {
      $this->dtdContent = $dtd;
      $this->dtdParser = \Soothsilver\DtdParser\DTD::parseText($dtd);
      $this->faker = Faker\Factory::create();


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
    $root = $this->dtdParser->elements[key($this->dtdParser->elements)];

    $this->domImplementation = new DOMImplementation();

    $dtdData = 'data://text/plain;base64,' . base64_encode($this->dtdContent);
    $dtd = $this->domImplementation->createDocumentType($root->type, '', $dtdData);

    $this->xmlTree = $this->domImplementation->createDocument(NULL, NULL, $dtd);
    $this->xmlTree->xmlVersion = '1.0';
    $this->xmlTree->encoding="UTF-8";

    $rootElement = $this->xmlTree->createElement($root->type);
    $this->xmlTree->appendChild($rootElement);

    $this->addChildren($root, $rootElement);
  }

  private function addAttributes($dtdNode, $xmlNode) {
    $attributes = $this->dtdParser->elements[$dtdNode->type]->attributes;
    foreach($attributes as $attribute) {
      $value = '';

      if($attribute->defaultType == '#IMPLIED') {
        if(mt_rand(0, 99) > 49)
          $value = $this->stringGenerator(1, 1);
      } else if($attribute->defaultType == '#REQUIRED') {
        $value = $this->stringGenerator(1, 1);
      } else if($attribute->defaultType == '#FIXED') {
        $value = $attribute->defaultValue;
      } else if(count($attribute->enumeration) > 0) { // Enumeration
          if(mt_rand(0, 99) > 20) {
            $value = $attribute->enumeration[array_rand($attribute->enumeration)];
          }
      } else if($attribute->defaultValue) {
        if(mt_rand(0, 99) > 49) {
          $value = $attribute->defaultValue;
        }
      }

      if(strlen($value) > 0)
        $xmlNode->setAttribute($attribute->name, $value);
    }
  }

  private function addChildren($dtdNode, $xmlNode) {
    $this->addAttributes($dtdNode, $xmlNode);

    $children = $this->parseContentSpecification($dtdNode->contentSpecification);
    $children = explode(',', $children);
    foreach($children as $child) {
      if(strlen($child) == 0) continue;

      if($child == '#PCDATA' or $child == 'ANY') {
        $xmlNode->nodeValue = $this->stringGenerator();
      } else if($child == 'EMPTY') {
        // Nothing
      } else {
        $childNode = $this->dtdParser->elements[$child];
        $element = $this->xmlTree->createElement($childNode->type);
        $xmlNode->appendChild($element);
        $this->addChildren($childNode, $element);
      }
    }
  }

  public function writeXML() {
    if($this->xmlTree->validate()) {
      echo $this->xmlTree->saveXML()."\n";
    } else {
      // errors??
    }
  }

  private function getQuantifierAction($s) {
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

  private function parseContentSpecification($cs) {
    $foundParens = preg_match('/\(([^(]*?)\)/', $cs, $matches, PREG_OFFSET_CAPTURE);
    if($foundParens === 0) {
      // Go through each element in the final list and check for individual quantifiers
      $elements = explode(',', $cs);
      foreach($elements as &$element) {
        $lastChar = substr($element, -1, 1);
        $quantifierAction = $this->getQuantifierAction($lastChar);
        if($quantifierAction >= 0) {
          $element = substr($element, 0, -1);
          $element = implode(',', array_fill(0, $quantifierAction, $element));
        }
      }
      $cs = implode(',', $elements);

      return $cs;
    }

    $match = $matches[1];
    $quantifier = substr($cs, $matches[1][1] + strlen($matches[1][0])+1, 1);

    // Make the string an array so we can multiply it
    $parsed = array(substr($cs, $match[1], strlen($match[0])));

    // Evaluate quantifier
    $extraChar = 0; // If we need to remove an extra character when substringing
    $quantifierAction = $this->getQuantifierAction($quantifier);
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
    return $this->parseContentSpecification($cs);
  }

  private function stringGenerator($minLength = 10, $maxLength = 20) {
    return $this->faker->words($this->faker->numberBetween($minLength, $maxLength), true);
  }
}
