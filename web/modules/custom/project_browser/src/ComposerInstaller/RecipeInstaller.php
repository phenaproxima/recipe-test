<?php

namespace Drupal\project_browser\ComposerInstaller;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Queue\QueueFactory;
use Drupal\Core\TempStore\SharedTempStoreFactory;
use Drupal\package_manager\FailureMarker;
use Drupal\package_manager\PathLocator;
use Drupal\package_manager\StageBase;
use PhpTuf\ComposerStager\API\Core\BeginnerInterface;
use PhpTuf\ComposerStager\API\Core\CommitterInterface;
use PhpTuf\ComposerStager\API\Core\StagerInterface;
use PhpTuf\ComposerStager\API\Path\Factory\PathFactoryInterface;
use PhpTuf\ComposerStager\API\Path\Value\PathInterface;
use PhpTuf\ComposerStager\API\Path\Value\PathListInterface;
use PhpTuf\ComposerStager\API\Process\Service\OutputCallbackInterface;
use PhpTuf\ComposerStager\API\Process\Service\ProcessInterface;
use Symfony\Component\Process\Process;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class RecipeInstaller extends StageBase {

  public function __construct(
    PathLocator $pathLocator,
    BeginnerInterface $beginner,
    StagerInterface $stager,
    CommitterInterface $committer,
    QueueFactory $queueFactory,
    EventDispatcherInterface $eventDispatcher,
    SharedTempStoreFactory $tempStoreFactory,
    TimeInterface $time,
    PathFactoryInterface $pathFactory,
    FailureMarker $failureMarker
  ) {
    $beginner = new class () implements BeginnerInterface {

      public function begin(
        PathInterface $activeDir,
        PathInterface $stagingDir,
        ?PathListInterface $exclusions = NULL,
        ?OutputCallbackInterface $callback = NULL,
        int $timeout = ProcessInterface::DEFAULT_TIMEOUT,
      ): void {}

    };
    $stager = new class () implements StagerInterface {

      public function stage(
        array $composerCommand,
        PathInterface $activeDir,
        PathInterface $stagingDir,
        ?OutputCallbackInterface $callback = NULL,
        int $timeout = ProcessInterface::DEFAULT_TIMEOUT,
      ): void {}

    };
    $committer = new class () implements CommitterInterface {

      public function commit(
        PathInterface $stagingDir,
        PathInterface $activeDir,
        ?PathListInterface $exclusions = NULL,
        ?OutputCallbackInterface $callback = NULL,
        int $timeout = ProcessInterface::DEFAULT_TIMEOUT,
      ): void {}

    };
    parent::__construct($pathLocator, $beginner, $stager, $committer,
      $queueFactory, $eventDispatcher, $tempStoreFactory, $time, $pathFactory,
      $failureMarker);
  }

  /**
   * {@inheritdoc}
   */
  public function require(array $runtime, array $dev = [], ?int $timeout = 300): void {
    [$package_name] = explode(':', reset($runtime), 2);
    $recipe_path = match ($package_name) {
      'kanopi/gin-admin-experience' => 'recipes/contrib/gin-admin-experience',
      'drupal/spotify-media-type' => 'recipes/custom/spotify-media-type',
    };
    $process = new Process([PHP_BINARY, 'core/scripts/drupal', 'recipe', $recipe_path], \Drupal::root());
    $process->mustRun();
  }

  public function lockCameFromProjectBrowserInstaller(): bool {
    return TRUE;
  }

}
