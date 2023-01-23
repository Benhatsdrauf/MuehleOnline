<script>
    export let x = 0;
    export let y = 0;
    export let index = 0;
    export let status = "initial";
    export let isPossible = false;
</script>

<circle on:click cx="{x}%" cy="{y}%" r="15" class:possible={isPossible} />

<style>
    circle {
        cursor: pointer;
    }

    .possible {
        fill: green;
    }
</style>
