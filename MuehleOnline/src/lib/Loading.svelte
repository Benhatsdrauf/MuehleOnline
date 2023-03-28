<script>
  import SpinningIcon from "./SpinningIcon.svelte";

export let show = false;
</script>

{#if show}
<div class="d-flex justify-content-center">
  <SpinningIcon/>
</div>
{/if}