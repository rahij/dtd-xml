<?php

namespace App\Helpers;

use DOMImplementation;
use Faker;
/*
** Usage:
** $dtd = new DTDtoXML(file_get_contents("test.dtd"));
** $dtd->writeXML();
** Returns XML if it was passed a valid DTD, warnings otherwise
**
** TODO: Handle warnings?
** TODO: Make it possible to specify which elements serves as root element.
**          (Right now the topmost element in the DTD is always root)
**
**/

$loader = require '../vendor/autoload.php';

class DTDtoXML {
  private static $dtdContent;
  private static $dtdParser;
  private static $domImplementation;
  private static $xmlTree;
  private static $faker;

  public static function parseDTD($dtd) {
      self::$dtdContent = $dtd;
      self::$dtdParser = \Soothsilver\DtdParser\DTD::parseText($dtd);
      self::$faker = Faker\Factory::create();

      if(self::$dtdParser->isWellFormedAndValid()) {
        self::generateXML();
        self::writeXML();
      } else {
        $errorString = '';
        foreach(self::$dtdParser->errors as $error) {
          $errorString .= $error->getMessage() . "\n";
        }
        throw new \Exception($errorString);
      }
  }

  private static function generateXML() {
    $root = self::$dtdParser->elements[key(self::$dtdParser->elements)];

    self::$domImplementation = new DOMImplementation();

    $dtdData = 'data://text/plain;base64,' . base64_encode(self::$dtdContent);
    $dtd = self::$domImplementation->createDocumentType($root->type, '', $dtdData);

    self::$xmlTree = self::$domImplementation->createDocument(NULL, NULL, $dtd);
    self::$xmlTree->xmlVersion = '1.0';
    self::$xmlTree->encoding="UTF-8";

    $rootElement = self::$xmlTree->createElement($root->type);
    self::$xmlTree->appendChild($rootElement);

    self::addChildren($root, $rootElement);
  }

  private static function addAttributes($dtdNode, $xmlNode) {
    $attributes = self::$dtdParser->elements[$dtdNode->type]->attributes;
    foreach($attributes as $attribute) {
      $value = '';

      if($attribute->defaultType == '#IMPLIED') {
        if(mt_rand(0, 99) > 49)
          $value = self::stringGenerator(1, 1);
      } else if($attribute->defaultType == '#REQUIRED') {
        $value = self::stringGenerator(1, 1);
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

  private static function addChildren($dtdNode, $xmlNode) {
    //self::addAttributes($dtdNode, $xmlNode);

    $children = self::parseContentSpecification($dtdNode->contentSpecification);
    $children = explode(',', $children);
    foreach($children as $child) {
      if(strlen($child) == 0) continue;

      if($child == '#PCDATA' or $child == 'ANY') {
        $xmlNode->nodeValue = self::stringGenerator();
      } else if($child == 'EMPTY') {
        // Nothing
      } else {
        $childNode = self::$dtdParser->elements[$child];
        $element = self::$xmlTree->createElement($childNode->type);
        $xmlNode->appendChild($element);
        self::addChildren($childNode, $element);
      }
    }
  }

  private static function writeXML() {
    if(self::$xmlTree->validate()) {
      echo self::$xmlTree->saveXML()."\n";
    } else {
      echo 'oops';
      // errors??
    }
  }

  private static function getQuantifierAction($s) {
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

  private static function parseContentSpecification($cs) {
    $foundParens = preg_match('/\(([^(]*?)\)/', $cs, $matches, PREG_OFFSET_CAPTURE);
    if($foundParens === 0) {
      // Go through each element in the final list and check for individual quantifiers
      $elements = explode(',', $cs);
      foreach($elements as &$element) {
        $lastChar = substr($element, -1, 1);
        $quantifierAction = self::getQuantifierAction($lastChar);
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
    $quantifierAction = self::getQuantifierAction($quantifier);
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
    return self::parseContentSpecification($cs);
  }

  private static function stringGenerator($minLength = 10, $maxLength = 20) {
    return self::$faker->words(self::$faker->numberBetween($minLength, $maxLength), true);
  }
}
