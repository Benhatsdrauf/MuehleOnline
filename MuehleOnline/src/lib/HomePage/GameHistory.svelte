<script>
  import { faBolt, faBurst, faCrown, faExplosion, faL } from "@fortawesome/free-solid-svg-icons";
  import Fa from "svelte-fa";

    export let won = false;
    export let opponent = "";
    export let playtime = 0;
    export let start_date = "";
    export let elo = 0;

    let date = new Date(start_date);
</script>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-auto right-border">
                {#if won}
                <Fa icon={faCrown} color="var(--color-win)" size="1.6x" />
                {:else}
                <Fa icon={faBolt} color="var(--color-los)" size="2x" />
                {/if}
            </div>
            <div class="col-2">
                <b>{opponent}</b>
            </div>
            <div class="col text-center" class:text-win="{won}" class:text-los="{!won}">
                <b>{(won) ? "+" : ""}{elo}</b>
            </div>
            <div class="col text-center">
                Play time: <b>{new Date(playtime * 1000).toISOString().slice(11, 19)}</b>
            </div>
            <div class="col text-end">
                <b>{`${date.getDate()}.${date.getMonth() + 1}.${date.getFullYear()}`}</b>
            </div>
        </div>
    </div>
</div>

<style>

    .right-border
    {
        border-right: solid var(--color-black) 2px;
    }

    .card{
        border: solid var(--color-black) 2px;
    }
    
    .text-los{
        color: var(--color-los)
    }

    .text-win {
        color: var(--color-win)
    }

</style>
