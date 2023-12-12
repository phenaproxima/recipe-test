<script>
  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export let moduleCategories;
  export let toggleView;

  const extraCategories = moduleCategories.splice(3);
  const { Drupal } = window;
</script>

<div class="categories" data-label="Categories">
  {#if typeof moduleCategories !== 'undefined' && moduleCategories.length}
    <ul
      class:categories__list--grid={toggleView === 'Grid'}
      class="categories__list"
    >
      {#each moduleCategories || [] as category}
        <li class="categories__category">
          {category.name}
        </li>
      {/each}
      {#if extraCategories.length}
        <li class="categories__category categories__category--extra">
          {Drupal.t('+ @count more', { '@count': extraCategories.length })}
        </li>
      {/if}
    </ul>
  {/if}
</div>
