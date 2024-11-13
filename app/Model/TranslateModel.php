<?php
/* Copyright (C) 2019 ActiveNet s.r.o. - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by ActiveNet s.r.o. <info@activenet.sk>
 */
namespace App\Model;

use App\Model\BaseModel;
use Nette\Caching\Cache;
use Nette\Database\Explorer;

class TranslateModel extends BaseModel
{
  protected ?Cache $cache = null;

  public function __construct(Explorer $database)
  {
    parent::__construct($database);
  }

  public function getTableName(): string
  {
    return 'translate';
  }

  public function getTranslateTable(): object
  {
    $key = 'translateTable';
    $translate = $this->getCache()->load($key, function (&$dependencies) {
      $dependencies[Cache::Expire] = '1 day';
      $data = $this->getTranslateObject();

      if (!$data) {
        return $data;
      }
      return $data;
    });
    return (object) $translate;
  }

  public function getTranslateObject(): ?object
  {
    $data = $this->getTable()->fetchAll();
    if (!$data) {
      return null;
    }
    $object = (object) array();
    foreach ($data as $row) {
      $key = $row['key'];
      $lang = $row['language'];
      $val = $row['value'];
      if (!isset($object->$key)) {
        $object->$key = (object) array();
      }
      $object->$key->$lang = $val;
    }
    return $object;
  }

}