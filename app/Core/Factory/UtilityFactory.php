<?php
declare(strict_types=1);

namespace App\Core\Factory;

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
}