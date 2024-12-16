<?php
declare(strict_types=1);
namespace App\UI\Components\Menu\SearchPanel;

interface ControlFactory
{
  public function create(): Control;
}