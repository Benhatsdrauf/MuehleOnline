<script>
    export let x = 0;
    export let y = 0;
    export let isWhite = false;
</script>

<circle
    on:click
    cx="{x}%"
    cy="{y}%"
    r="22"
    class:white={isWhite}
    class:black={!isWhite}
/>

<style>
    .white {
        fill: var(--color-steel-200);
    }

    .black {
        fill: var(--color-terra-dark);
    }

    circle {
        stroke: var(--color-black);
        stroke-width: 2px;
        filter: drop-shadow(0px 0px 2px var(--color-steel-200));
    }
</style>
