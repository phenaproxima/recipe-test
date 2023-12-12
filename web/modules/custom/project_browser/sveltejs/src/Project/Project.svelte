<script>
  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let project;
  export let toggleView;
  import ActionButton from './ActionButton.svelte';
  import Image from './Image.svelte';
  import Categories from './Categories.svelte';
  import ProjectIcon from './ProjectIcon.svelte';
  import { focusedElement, mediaQueryValues } from '../stores';
  import { FULL_MODULE_PATH, ORIGIN_URL } from '../constants';

  const { Drupal } = window;

  let mqMatches;
  $: isDesktop = mqMatches;
  mediaQueryValues.subscribe((mqlMap) => {
    mqMatches = mqlMap.get('(min-width: 1200px)');
  });
</script>

<li class="project project--{isDesktop ? toggleView.toLowerCase() : 'list'}">
  <div class="project__logo">
    <Image sources={project.logo} class="project__logo-image" />
  </div>
  <div class="project__main">
    <h3
      on:click={() => {
        $focusedElement = `${project.project_machine_name}_title`;
      }}
      class="project__title"
    >
      <a
        id="{project.project_machine_name}_title"
        class="project__link"
        href="{ORIGIN_URL}/admin/modules/browse/{project.project_machine_name}"
        rel="noreferrer">{project.title}</a
      >
    </h3>
    <div class="project__body">{@html project.body.summary}</div>
    <Categories {toggleView} moduleCategories={project.module_categories} />
  </div>
  <div
    class="project__icons"
    class:warnings={project.warnings && project.warnings.length > 0}
  >
    {#if project.is_covered}
      <span class="project__status-icon">
        <ProjectIcon type="status" />
        <!-- Show the security policy description if it is accompanied by warnings,
             since those also have descriptions.  -->
        {#if project.warnings && project.warnings.length > 0}
          <small>{Drupal.t('Covered by the security advisory policy')}</small>
        {/if}
      </span>
    {/if}
    {#if toggleView === 'Grid' && project.project_usage_total !== -1}
      <div class="project__install-count-container">
        <span class="project__install-count"
          >{Drupal.t('@count installs ', {
            '@count': project.project_usage_total.toLocaleString(),
          })}</span
        >
      </div>
    {/if}
    {#if project.warnings && project.warnings.length > 0}
      {#each project.warnings as warning}
        <span class="project__status-icon">
          <img src="{FULL_MODULE_PATH}/images/triangle-alert.svg" alt="" />
          <small>{@html warning}</small>
        </span>
      {/each}
    {/if}
    {#if toggleView === 'List' && project.project_usage_total !== -1}
      <div class="project__project-usage-container">
        <div class="project__image">
          <ProjectIcon type="usage" variant="project-listing" />
        </div>
        <div class="project__active-installs-text">
          {project.project_usage_total.toLocaleString()} Active Installs
        </div>
      </div>
    {/if}
    <!--If there are no warnings, there is space to include the action button
        in the icons container -->
    {#if !project.warnings || project.warnings.length === 0}
      <ActionButton {project} />
    {/if}
  </div>
  <!--If there are warnings, the action button needs to be moved out of the
      icons container to provide space for the warning descriptions. -->
  {#if project.warnings && project.warnings.length > 0}
    <ActionButton {project} />
  {/if}
</li>
