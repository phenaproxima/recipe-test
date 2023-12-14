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
      $data['install'] = array_diff($data['install'], [
        'admin_toolbar',
        'admin_toolbar_search',
        'admin_toolbar_tools',
      ]);
      unset($data['config']['import']['admin_toolbar']);
      unset($data['config']['import']['admin_toolbar_search']);
      unset($data['config']['import']['admin_toolbar_tools']);
      unset($data['config']['actions']);
      file_put_contents($path, Yaml::encode($data));
    }
  }

}
