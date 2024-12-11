<?php
declare(strict_types=1);

namespace App\UI\Trait;

use App\Model\ConfigModel;
use Nette\DI\Attributes\Inject;

trait ConfigTrait
{
  #[Inject] public ConfigModel $configModel;
  protected $config;

  public function injectConfigTrait()
  {
    $this->onStartup[] = function () {
      $this->config ??= $this->configModel->getConfig();
    };
  }

  public function getConfig(string $key)
  {
    if (isset($this->config[$key])) {
      return $this->config[$key];
    }
    return false;
  }
}