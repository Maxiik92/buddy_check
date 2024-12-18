<?php

namespace App\Model;

use App\Model\BaseModel;
use Nette\Caching\Cache;

class TranslateModel extends BaseModel
{
  protected ?Cache $cache = null;

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