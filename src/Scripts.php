<?php

namespace phen;

use Drupal\Component\Serialization\Yaml;
use Symfony\Component\Finder\Finder;

final class Scripts {

  /**
   * Makes all info files compatible with Drupal 11.
   */
  public static function rewriteInfoFiles(): void {
    $finder = Finder::create()
      ->in('web')
      ->name('*.info.yml');

    /** @var \Symfony\Component\Finder\SplFileInfo $file */
    foreach ($finder as $file) {
      $data = Yaml::decode($file->getContents());
      if (empty($data['core_version_requirement'])) {
        continue;
      }
      if (str_contains($data['core_version_requirement'], ' || ^11')) {
        continue;
      }
      $data['core_version_requirement'] .= ' || ^11';
      file_put_contents($file->getPathname(), Yaml::encode($data));
    }
  }

}
