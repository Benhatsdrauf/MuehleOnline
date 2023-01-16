<script>
    export let x = 0;
    export let y = 0;
    export let index = 0;
    export let status = "initial";
</script>

<circle on:click cx="{x}%" cy="{y}%" r="15" />

<style>
    circle {
        cursor: pointer;
    }
</style>
