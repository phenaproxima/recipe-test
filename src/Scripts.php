<?php

namespace phen;

use Drupal\Component\Serialization\Yaml;

final class Scripts {

  /**
   * Changes the gin-admin-experience recipe to enable testing.
   */
  public static function rewriteRecipe(): void {
    $path = 'web/recipes/contrib/gin-admin-experience/recipe.yml';
    if (file_exists($path)) {
      $data = file_get_contents($path);
      $data = Yaml::decode($data);
      unset($data['config']['actions']);
      file_put_contents($path, Yaml::encode($data));
    }
  }

}
