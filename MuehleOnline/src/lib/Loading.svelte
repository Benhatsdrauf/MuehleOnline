<script>
export let show = false;
</script>

{#if show}
<div class="d-flex justify-content-center">
    <div class="spinner-border" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
</div>
{/if}