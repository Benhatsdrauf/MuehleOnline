<script>
  export let isWhite;

  let text = "";

  if (isWhite) {
    text = "White";
  } else {
    text = "Brown";
  }
</script>

<div class="card">
  <div
    class="card-body p-0 text-center rounded"
    class:white={isWhite}
    class:brown={!isWhite}
  >
    <h5 style="margin-bottom: 2px"
    class:text-white={!isWhite}><b>{text}</b></h5>
  </div>
</div>

<style>
  .white {
    background-color: var(--color-white) !important;
  }

  .brown {
    background-color: var(--color-terra-dark) !important;
  }

  .card {
    border: 6px var(--color-dark-grey) solid;
    border-radius: 10px;
  }
</style>
