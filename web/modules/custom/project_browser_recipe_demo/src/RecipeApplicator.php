<?php

namespace Drupal\project_browser_recipe_demo;

use Composer\InstalledVersions;
use Drupal\Core\State\StateInterface;
use Drupal\package_manager\ComposerInspector;
use Drupal\package_manager\Event\PostApplyEvent;
use Drupal\package_manager\Event\PostRequireEvent;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Process\Process;

class RecipeApplicator implements EventSubscriberInterface, LoggerAwareInterface {

  use LoggerAwareTrait;

  private const STATE_KEY = 'staged_recipes';

  public function __construct(
    private readonly StateInterface $state,
    private readonly ComposerInspector $inspector,
    private readonly string $appRoot,
  ) {
    $this->setLogger(new NullLogger());
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      PostRequireEvent::class => 'onRequire',
      PostApplyEvent::class => 'onApply',
    ];
  }

  public function onRequire(PostRequireEvent $event): void {
    $list = $this->state->get(self::STATE_KEY, []);

    $staged_packages = $this->inspector->getInstalledPackagesList($event->stage->getStageDirectory());

    foreach (array_keys($event->getRuntimePackages()) as $name) {
      if ($staged_packages[$name]?->type === 'drupal-recipe') {
        $list[] = $name;
      }
    }
    $this->logger->debug('Recipes were staged: @list', [
      '@list' => implode(', ', $list),
    ]);
    $this->state->set(self::STATE_KEY, array_unique($list));
  }

  public function onApply(): void {
    $names = $this->state->get(self::STATE_KEY, []);

    $paths = array_map(InstalledVersions::getInstallPath(...), $names);
    $paths = array_filter($paths);

    try {
      foreach ($paths as $path) {
        $process = new Process([PHP_BINARY, 'core/scripts/drupal', 'recipe', $path]);
        $process->setWorkingDirectory($this->appRoot)->mustRun();

        $this->logger->debug("Output from @command:\n\n@output\n\n@error_output", [
          '@command' => $process->getCommandLine(),
          '@output' => $process->getOutput(),
          '@error_output' => $process->getErrorOutput(),
        ]);
      }
    }
    finally {
      $this->state->delete(self::STATE_KEY);
    }
  }

}
