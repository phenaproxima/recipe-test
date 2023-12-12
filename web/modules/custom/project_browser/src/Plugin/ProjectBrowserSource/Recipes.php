<?php

namespace Drupal\project_browser\Plugin\ProjectBrowserSource;

use Drupal\project_browser\Plugin\ProjectBrowserSourceBase;
use Drupal\project_browser\ProjectBrowser\Project;
use Drupal\project_browser\ProjectBrowser\ProjectsResultsPage;

/**
 * @ProjectBrowserSource(
 *   id = "recipes",
 *   label = @Translation("Recipes"),
 *   description = @Translation("A set of recipes for testing purposes."),
 * )
 */
class Recipes extends ProjectBrowserSourceBase {

  /**
   * {@inheritdoc}
   */
  public function getProjects(array $query = []): ProjectsResultsPage {
    $list = [];
    $logo = [
      'file' => [
        'uri' => '/core/misc/druplicon.png',
        'resource' => 'image',
      ],
      'alt' => 'Project logo',
    ];
    // A recipe that is known to work properly.
    $list[] = (new Project())
      ->setProjectTitle('Gin Admin Experience')
      ->setLogo($logo)
      ->setAuthor([
        'name' => 'Kanopi Studios',
      ])
      ->setSummary([
        'value' => 'Sets up a beautiful admin experience based on Gin.',
      ])
      ->setProjectUrl('https://github.com/kanopi/gin-admin-experience')
      ->setIsActive(TRUE)
      ->setIsCovered(TRUE)
      ->setIsCompatible(TRUE)
      ->setMachineName('gin_admin_experience')
      ->setComposerNamespace('kanopi/gin-admin-experience')
      ->setIsMaintained(TRUE)
      ->setProjectStarUserCount(-1)
      ->setId('0')
      ->setChangedTimestamp(time())
      ->setCreatedTimestamp(time())
      ->setProjectStatus(1)
      ->setProjectUsageTotal(-1);

    // A recipe that does not work properly.
    $list[] = (new Project())
      ->setProjectTitle('Spotify Media Type')
      ->setLogo($logo)
      ->setAuthor([
        'name' => 'Gru',
      ])
      ->setSummary([
        'value' => 'Adds a Spotify media type.',
      ])
      ->setIsActive(TRUE)
      ->setIsCovered(TRUE)
      ->setIsCompatible(TRUE)
      ->setMachineName('spotify_media_type')
      ->setComposerNamespace('drupal/spotify-media-type')
      ->setIsMaintained(TRUE)
      ->setProjectStarUserCount(-1)
      ->setId('1')
      ->setChangedTimestamp(time())
      ->setCreatedTimestamp(time())
      ->setProjectStatus(1)
      ->setProjectUsageTotal(-1);

    return new ProjectsResultsPage(count($list), $list, 'Recipes', $this->getPluginId(), FALSE);
  }

  /**
   * {@inheritdoc}
   */
  public function getCategories(): array {
    return [];
  }

}
