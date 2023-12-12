<?php

namespace Drupal\project_browser_recipe_demo\Plugin\ProjectBrowserSource;

use Drupal\project_browser\Plugin\ProjectBrowserSourceBase;
use Drupal\project_browser\ProjectBrowser\Project;
use Drupal\project_browser\ProjectBrowser\ProjectsResultsPage;

/**
 * @ProjectBrowserSource(
 *   id = "recipe_test",
 *   label = @Translation("Recipes (test only)"),
 *   description = @Translation("Exposes Drupal recipes for testing and demo purposes."),
 * )
 */
class RecipeTest extends ProjectBrowserSourceBase {

  /**
   * {@inheritdoc}
   */
  public function getProjects(array $query = []): ProjectsResultsPage {
    $list = [];
    $plugin_definition = $this->getPluginDefinition();

    $list[] = (new Project())
      ->setAuthor([
        'name' => 'Kanopi Studios',
      ])
      ->setCreatedTimestamp(time())
      ->setChangedTimestamp(time())
      ->setProjectStatus(1)
      ->setProjectTitle('Gin Admin Experience')
      ->setId('recipe-1')
      ->setSummary([
        'value' => 'A beautiful administrative experience based on the Gin theme.',
      ])
      ->setMachineName('gin_login')
      ->setLogo([
        'file' => [
          'uri' => '/core/misc/druplicon.png',
          'resource' => 'image',
        ],
        'alt' => 'Gin Admin Experience logo',
      ])
      ->setComposerNamespace('kanopi/gin-admin-experience')
      ->setProjectUrl('https://github.com/kanopi/gin-admin-experience')
      ->setProjectUsageTotal(1)
      ->setProjectStarUserCount(1)
      ->setIsActive(TRUE)
      ->setIsCovered(TRUE)
      ->setIsMaintained(TRUE)
      ->setIsCompatible(TRUE);

    return new ProjectsResultsPage(count($list), $list, $plugin_definition['label'], $this->getPluginId(), TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function getCategories(): array {
    return [];
  }

}
