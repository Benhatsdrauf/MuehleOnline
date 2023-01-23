<script>
    export let x = 0;
    export let y = 0;
    export let index = 0;
    export let status = "initial";
    export let isPossible = false;
    export let isDisabled = false;
</script>

<circle
    on:click
    cx="{x}%"
    cy="{y}%"
    r="15"
    class:possible={isPossible}
    class:disabled={isDisabled}
/>

<style>
    circle {
        cursor: pointer;
    }

    .possible {
        fill: green;
    }

    .disabled {
        pointer-events: none;
    }
</style>
