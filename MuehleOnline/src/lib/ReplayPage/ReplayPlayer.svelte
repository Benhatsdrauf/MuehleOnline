<script>
  import { faBackward } from "@fortawesome/free-solid-svg-icons";
  import Fa from "svelte-fa";

</script>

<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col">
        <button class="btn">
            <Fa icon={faBackward}/>
        </button>
      </div>
    </div>
  </div>
</div>

<style>
  .card {
    border: 6px var(--color-black) solid;
    border-radius: 10px;
  }
</style>
