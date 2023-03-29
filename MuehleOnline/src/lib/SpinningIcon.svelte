<script>
import pizza from "../assets/Pizza_Pepperoni.png";
import blades from "../assets/BladesTransparent.png";
import mill from "../assets/BackgroundTransparent.png";

export let isFast = false;
let random = Math.floor(Math.random() * 99);

</script>

<div class="img-container">


    {#if random != 0}
    <img src={mill} alt="muehleIcon" width="100px" height="100px" />
    <img
      src={blades}
      alt="muehleIcon"
      width="80px"
      height="80px"
      class="overlay-img"
      class:spin-fast={isFast}
      class:spin-normal={!isFast}
    />
    {:else}
    <img
    src={pizza}
    alt="muehleIcon"
    width="80px"
    height="80px"
    class:spin-fast={isFast}
    class:spin-normal={!isFast}
  />
    {/if}


</div>

<style>
  .img-container {
    position: relative;
  }

  .overlay-img {
    position: absolute;
    top: 0px;
    left: 9px;
  }

  .spin-fast {
    -webkit-animation: spin 0.1s linear infinite;
    -moz-animation: spin 0.1s linear infinite;
    animation: spin 0.1s linear infinite;
  }

  .spin-normal {
    -webkit-animation: spin 1s linear infinite;
    -moz-animation: spin 1s linear infinite;
    animation: spin 1s linear infinite;
  }
  @-moz-keyframes spin {
    100% {
      -moz-transform: rotate(360deg);
    }
  }
  @-webkit-keyframes spin {
    100% {
      -webkit-transform: rotate(360deg);
    }
  }
  @keyframes spin {
    100% {
      -webkit-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
</style>
