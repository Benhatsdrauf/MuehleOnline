<script>
  export let user = {};
  export let hasTurn = false;
</script>

<div class="card" class:highlight={hasTurn} style="max-width: 10vw;">
  <div class="card-header bgc-secondary" style="padding-left: 8px !important;">
    <div class="row">
      <div class="col-auto" style="padding-right: 4px;">
        <img
          class="round-img"
          src="https://api.dicebear.com/5.x/initials/svg?seed={user.name}"
          alt="profile"
          width="30px"
          height="30px"
        />
      </div>
      <div class="col c-text">
        <h4 class="text-nowrap text-truncate">{user.name}</h4>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div>
      <p>Elo: {user.elo}</p>
      <p>Win rate: {user.wins}/{user.losses}</p>
    </div>
  </div>
</div>

<style>
  .highlight {
    border: 6px var(--color-success-light) solid !important;
  }

  .card {
    border: 6px var(--color-dark-grey) solid;
    border-radius: 10px;
  }
</style>
