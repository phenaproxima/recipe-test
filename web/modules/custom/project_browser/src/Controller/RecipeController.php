<?php

namespace Drupal\project_browser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RecipeController extends ControllerBase {

  public function applyRecipe(string $which): JsonResponse {
    $path = match ($which) {
      'gin-admin-experience' => 'recipes/contrib/gin-admin-experience',
      'spotify-media-type' => 'recipes/custom/spotify-media-type',
    };

    $process = new Process([PHP_BINARY, 'core/scripts/drupal', 'recipe', $path], \Drupal::root());
    try {
      $process->mustRun();
      return new JsonResponse(['status' => 0]);
    }
    catch (ProcessFailedException $e) {
      return new JsonResponse([
        'message' => $e->getMessage(),
      ]);
    }
  }

}
