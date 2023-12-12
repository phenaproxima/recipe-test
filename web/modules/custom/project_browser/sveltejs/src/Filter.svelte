<script>
  import { createEventDispatcher, getContext, onMount } from 'svelte';
  import {
    moduleCategoryFilter,
    moduleCategoryVocabularies,
    activeTab,
  } from './stores';
  import MediaQuery from './MediaQuery.svelte';
  import { normalizeOptions, shallowCompare } from './util';
  import { ORIGIN_URL } from './constants';

  const { Drupal } = window;
  const dispatch = createEventDispatcher();
  const stateContext = getContext('state');

  async function onSelectCategory(event) {
    const state = stateContext.getState();
    const detail = {
      originalEvent: event,
      category: $moduleCategoryFilter,
      page: state.page,
      pageIndex: state.pageIndex,
      pageSize: state.pageSize,
      rows: state.filteredRows,
    };
    dispatch('selectCategory', detail);
    stateContext.setPage(0, 0);
    stateContext.setRows(detail.rows);
  }

  async function fetchAllCategories() {
    const response = await fetch(`${ORIGIN_URL}/drupal-org-proxy/categories`);
    if (response.ok) {
      return response.json();
    }
    return [];
  }

  const apiModuleCategory = fetchAllCategories();
  // eslint-disable-next-line import/no-mutable-exports,import/prefer-default-export
  export async function setModuleCategoryVocabulary() {
    apiModuleCategory.then((value) => {
      const normalizedValue = normalizeOptions(value[$activeTab]);
      const storedValue = $moduleCategoryVocabularies;
      if (
        storedValue === null ||
        !shallowCompare(normalizedValue, storedValue)
      ) {
        moduleCategoryVocabularies.set(normalizedValue);
      }
    });
  }
  onMount(async () => {
    await setModuleCategoryVocabulary();
  });
</script>

<MediaQuery query="(min-width: 800px)" let:matches>
  <form class="filter">
    <section aria-label={Drupal.t('Filter categories')}>
      <details class="filter__categories" open={matches}>
        <summary class="filter__summary" hidden={matches}>
          <h2 class="filter__heading">{Drupal.t('Filter Categories')}</h2>
        </summary>
        <fieldset class="filter__fieldset">
          <h2 class:visually-hidden={!matches}>
            {Drupal.t('Filter Categories')}
          </h2>
          {#await apiModuleCategory then categoryList}
            {#each categoryList[$activeTab] as dt}
              <label class="filter__checkbox-label">
                <input
                  type="checkbox"
                  id={dt.id}
                  class="filter__checkbox"
                  bind:group={$moduleCategoryFilter}
                  on:change={onSelectCategory}
                  value={dt.id}
                />{dt.name}</label
              >
            {/each}
          {/await}
        </fieldset>
      </details>
    </section>
  </form>
</MediaQuery>
