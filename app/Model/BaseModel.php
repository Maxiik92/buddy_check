<?php
declare(strict_types=1);

namespace App\Model;

use Nette\Caching\Cache;
use Nette\Caching\Storage;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
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

  protected abstract function getTableName(): string;

  public function getTable()
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

  public function getById(string|int $id): ActiveRow|bool|int
  {
    return $this->getTable()->where('id', $id)->fetch();
  }

  public function getByParameter(string $column, string $value, ?array $columns = null): Selection
  {
    $data = $this->getTable();
    if ($columns) {
      $data = $data->select(implode($columns));
    }
    $data = $data->where($column, $value);
    return $data;
  }

  public function getByColumn(string $column, mixed $value): Selection
  {
    return $this->getTable()->where($column, $value);
  }

  public function updateByParam(string $column, mixed $value, array $update)
  {
    $result = $this->getByColumn($column, $value)->update($update);
    return $result;
  }

}

