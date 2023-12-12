<script>
  // eslint-disable-next-line import/prefer-default-export
  export let positionAbsolute = false;
</script>

<div
  class="loading__ajax-progress ajax-progress--fullscreen"
  class:absolute={positionAbsolute}
>
  <div class="ajax-progress__throbber ajax-progress__throbber--fullscreen">
    &nbsp;
  </div>
</div>
