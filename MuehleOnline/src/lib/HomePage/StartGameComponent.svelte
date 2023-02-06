<script>
  import Fa from "svelte-fa";
  import { faPlay, faCopy } from "@fortawesome/free-solid-svg-icons";
  import { AuthorizedRequest } from "../../../scripts/request";

  

  export let showModal = false;
  export let inviteLink = "";
  export let showErrorModal = false;

  async function StartGame() {
    let response = await AuthorizedRequest("game/create").catch((err) => {
      showErrorModal = true;
      console.log(err);
      return;
    });

    if (response) {
      inviteLink = response.invite_link;
      showModal = true;
    }
  }
</script>

<div class="card card-border">
  <div class="card-header bgc-secondary">
    <div class="row">
      <div class="col-auto">
        <Fa icon={faPlay} color="#ffffff" size="2x" />
      </div>
      <div class="col c-text">
        <h3>Start Game</h3>
      </div>
    </div>
  </div>
  <div class="card-body">
    <p>Click here to get a invite link for your friends.</p>
    <button class="btn btn-outline-primary" on:click={StartGame}> Play Now! </button>
  </div>
</div>
