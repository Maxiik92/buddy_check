<?php
declare(strict_types=1);

namespace App\Core\Factory;

use Nette\Database\Table\ActiveRow;
use stdClass;

class UtilityFactory
{

  /**
   * Checks if the file is really in www folder 
   * @param string $pathInWww
   * @return string|null path
   */
  public function getFilePathIfExists(string $pathInWww)
  {
    $wwwPath = realpath(__DIR__ . "/../../../www");
    $path = "$wwwPath/$pathInWww";
    return file_exists($path) ? $pathInWww : null;
  }

  public function createObjectFromMultipleResults(array $data)
  {
    $itemArray = null;
    //fetchAll
    foreach ($data as $key => $row) {
      $itemClass = new stdClass;
      foreach ($row as $key2 => $row2) {
        $itemClass->$key2 = $row2;
      }
      $itemArray[$key] = $itemClass;
    }
    return (Object) $itemArray;
  }

  public function createObjectFromOneResult(ActiveRow $data)
  {
    $itemArray = null;
    //Fetch
    foreach ($data as $key => $row) {
      $itemArray[$key] = $row;
    }
    return (Object) $itemArray;
  }
}