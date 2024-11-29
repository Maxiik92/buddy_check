<?php
declare(strict_types=1);

namespace App\Model;

use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\SmartObject;
use Nette\DI\Attributes\Inject;

abstract class BaseModel
{
  use SmartObject;
  protected ?Cache $cache = null;
  #[Inject] private Storage $storage;
  public function __construct(
    private Explorer $database
  ) {
  }

  public abstract function getTableName(): string;

  protected function getTable()
  {
    return $this->database->table($this->getTableName());
  }

  protected function getCache(): Cache
  {
    if ($this->cache == null) {
      return new Cache($this->storage);
    } else {
      return $this->cache;
    }
  }

  public function insert($data): ActiveRow|bool|int
  {
    return $this->getTable()->insert($data);
  }

  protected function valueExistsInTable(string $column, mixed $value)
  {
    $data = $this->getTable()
      ->select('id')
      ->where($column, $value)
      ->fetch();
    if ($data) {
      return true;
    }
    return false;
  }

}

