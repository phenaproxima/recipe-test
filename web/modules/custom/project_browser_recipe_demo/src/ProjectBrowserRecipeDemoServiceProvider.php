<?php

namespace Drupal\project_browser_recipe_demo;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\package_manager\PathExcluder\UnknownPathExcluder;
use Drupal\package_manager\Validator\ComposerPluginsValidator;

class ProjectBrowserRecipeDemoServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    parent::alter($container);

    // Package Manager's Composer plugins validator doesn't allow the 2.0.x
    // line of cweagans/composer-patches.
    $container->getDefinition(ComposerPluginsValidator::class)
      ->clearTag('event_subscriber');

    // The unknown path excluder causes patch information to be removed from
    // the stage directory.
    $container->getDefinition(UnknownPathExcluder::class)
      ->clearTag('event_subscriber');
  }

}
