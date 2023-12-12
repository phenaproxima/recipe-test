<script>
  import { onMount } from 'svelte';
  import ActionButton from './Project/ActionButton.svelte';
  import Image from './Project/Image.svelte';
  import ImageCarousel from './ImageCarousel.svelte';
  import { ORIGIN_URL } from './constants';
  import { moduleCategoryFilter, page } from './stores';
  import ProjectIcon from './Project/ProjectIcon.svelte';

  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let project;
  const { Drupal } = window;

  function filterByCategory(id) {
    $moduleCategoryFilter = [id];
    $page = 0;
    window.location.href = `${ORIGIN_URL}/admin/modules/browse`;
  }

  onMount(() => {
    const anchors = document
      .getElementById('description-wrapper')
      .getElementsByTagName('a');
    for (let i = 0; i < anchors.length; i++) {
      anchors[i].setAttribute('target', '_blank');
    }
  });
</script>

<a
  class="module-page--back-to-browsing action-link"
  href="{ORIGIN_URL}/admin/modules/browse"
>
  <span aria-hidden="true">&#9001&#xA0</span>
  {Drupal.t('Back to Browsing')}
</a>

<div class="module-page__wrapper">
  <div class="module-page__sidebar">
    <Image sources={project.logo} class="module-page__project-logo" />
    <div class="module-page__action-button-wrapper">
      <ActionButton {project} />
    </div>
    <div class="module-page__divider">&nbsp;</div>
    <h4>{Drupal.t('Details')}</h4>
    <div class="module-page__project-data">
      {#if project.module_categories.length}
        <p class="module-page__categories-label" id="categories">
          {Drupal.t('Categories:')}
        </p>
        <ul class="module-page__category-list" aria-labelledby="categories">
          {#each project.module_categories || [] as category}
            <li
              on:click={() => filterByCategory(category.id)}
              class="module-page__category-list-item"
            >
              {category.name}
            </li>
          {/each}
        </ul>
      {/if}
      <div class="module-page__module-details-grid">
        {#if project.is_compatible}
          <ProjectIcon
            type="compatible"
            variant="module-details"
            classes="module-page__module-details-grid__icon"
          />
          <p class="module-page__module-details-grid__description">
            {Drupal.t('Compatible with your Drupal installation')}
          </p>
        {/if}
        {#if project.project_usage_total !== -1}
          <ProjectIcon
            type="usage"
            variant="module-details"
            classes="module-page__module-details-grid__icon"
          />
          <p class="module-page__module-details-grid__description">
            {project.project_usage_total
              .toString()
              .replace(/\B(?=(\d{3})+(?!\d))/g, ',')}{Drupal.t(
              ' sites report using this module',
            )}
          </p>
        {/if}
        {#if project.is_covered}
          <ProjectIcon
            type="status"
            variant="module-details"
            classes="module-page__module-details-grid__icon"
          />
          <p class="module-page__module-details-grid__description">
            {Drupal.t(
              'Stable releases for this project are covered by the security advisory policy',
            )}
          </p>
        {/if}
      </div>
    </div>
  </div>
  <div class="module-page__main">
    <h2 class="module-page__h2">{project.title}</h2>
    <p class="module-page__author">
      {Drupal.t('By ')}{project.author.name}
    </p>
    {#if project.project_images.length}
      <div class="module-page__carousel-wrapper">
        <ImageCarousel sources={project.project_images} />
      </div>
    {/if}
    <div class="module-page__project-description" id="description-wrapper">
      {@html project.body.value}
    </div>
  </div>
</div>
