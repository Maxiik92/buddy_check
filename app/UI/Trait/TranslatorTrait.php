<?php
declare(strict_types=1);

namespace App\UI\Trait;

use Nette\DI\Attributes\Inject;
use Nette\Localization\Translator;


trait TranslatorTrait
{
  #[Inject] public Translator $translator;
  protected $actualLanguage;


  public function injectTranslatorTrait()
  {
    $this->onStartup[] = function () {
      if ($this->translator instanceof CustomTranslator) {
        $this->translator->setLang($this->actualLanguage);
      }
    };
  }

  public function t($key)
  {
    return $this->translator->translate($key);
  }
}