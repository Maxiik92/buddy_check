<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\TranslateModel;
use Nette\Localization\Translator;


class CustomTranslator implements Translator
{
  private $translator;

  public function __construct(
    private TranslateModel $translateModel,
    private string $defaultLang,
  ) {
    $this->translator = $this->translateModel->getTranslateObject();
  }

  public function setLang(string $lang): void
  {
    $this->defaultLang = $lang;
  }

  public function translate($message, ...$parameters): string
  {
    $defaultLang = $this->defaultLang;
    $mess = $this->translator->$message ?? $message;
    if (is_string($mess)) {
      return $mess;
    } else if ($mess == null) {
      return '';
    }
    return $mess->$defaultLang ?? $message;
  }
}