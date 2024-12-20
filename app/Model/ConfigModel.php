<?php
namespace App\Model;

use App\Model\BaseModel;
use Nette\Caching\Cache;

class ConfigModel extends BaseModel
{
  public function getTableName(): string
  {
    return 'config';
  }

  public function getConfig(): array
  {
    $key = 'config';
    $return = $this->getCache()->load($key, function (&$dependencies) {
      $dependencies[Cache::Expire] = '1 day';
      $data = $this->getTable->fetchPairs('key', 'value');

      if (!$data) {
        return $data;
      }
      return $data;
    });

    return $return;
  }
}