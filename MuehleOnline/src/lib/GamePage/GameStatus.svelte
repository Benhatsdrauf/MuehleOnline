<script>
  import { faBell } from "@fortawesome/free-solid-svg-icons";
  import Fa from "svelte-fa";
  import Countdown from "../Countdown.svelte";

    export let yourTurn = true;
    export let isWhite = true;
    export let ttm = null;
  </script>
  
  <div class="card">
    <div
      class="card-body p-0 text-center rounded text-white"
    >
    <div class="row">
      <div class="col">
        <h5 style="margin-bottom: 2px"><b>{`${(isWhite == yourTurn)? "White" : "Black"}'s turn`}</b></h5>

      </div>
      <div class="col">
        <h5><Fa icon={faBell}/> <Countdown date={ttm} useTextFormat={false}/></h5>
      </div>
    </div>
    </div>
  </div>
  
  <style>
  
    .card {
      border: 6px var(--color-black) solid;
      border-radius: 10px;
      background-color: var(--color-dark-grey);
    }
  </style>
  