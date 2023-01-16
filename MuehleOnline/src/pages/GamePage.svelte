<script>
    import { AuthorizedRequest } from "../../scripts/request";
    import { onMount } from "svelte";

    onMount(async () => {});
</script>

<h3>gamePage</h3>
<p>That's what it's all about!</p>

<div class="board">
    <svg class="game-field">
        <rect
            x="5%"
            y="5%"
            width="90%"
            height="90%"
            fill="none"
            stroke="black"
            stroke-width="5"
        />
        <rect
            x="20%"
            y="20%"
            width="60%"
            height="60%"
            fill="none"
            stroke="black"
            stroke-width="5"
        />
        <rect
            x="35%"
            y="35%"
            width="30%"
            height="30%"
            fill="none"
            stroke="black"
            stroke-width="5"
        />
        <line
            x1="5%"
            y1="50%"
            x2="35%"
            y2="50%"
            stroke="black"
            stroke-width="5"
        />
        <line
            x1="65%"
            y1="50%"
            x2="95%"
            y2="50%"
            stroke="black"
            stroke-width="5"
        />
        <line
            x1="50%"
            y1="5%"
            x2="50%"
            y2="35%"
            stroke="black"
            stroke-width="5"
        />
        <line
            x1="50%"
            y1="65%"
            x2="50%"
            y2="95%"
            stroke="black"
            stroke-width="5"
        />

        <circle cx="5%" cy="5%" r="15" />
        <circle cx="50%" cy="5%" r="15" />
        <circle cx="95%" cy="5%" r="15" />

        <circle cx="20%" cy="20%" r="15" />
        <circle cx="50%" cy="20%" r="15" />
        <circle cx="80%" cy="20%" r="15" />

        <circle cx="35%" cy="35%" r="15" />
        <circle cx="50%" cy="35%" r="15" />
        <circle cx="65%" cy="35%" r="15" />

        <circle cx="5%" cy="50%" r="15" />
        <circle cx="20%" cy="50%" r="15" />
        <circle cx="35%" cy="50%" r="15" />
        <circle cx="65%" cy="50%" r="15" />
        <circle cx="80%" cy="50%" r="15" />
        <circle cx="95%" cy="50%" r="15" />

        <circle cx="35%" cy="65%" r="15" />
        <circle cx="50%" cy="65%" r="15" />
        <circle cx="65%" cy="65%" r="15" />

        <circle cx="20%" cy="80%" r="15" />
        <circle cx="50%" cy="80%" r="15" />
        <circle cx="80%" cy="80%" r="15" />

        <circle cx="5%" cy="95%" r="15" />
        <circle cx="50%" cy="95%" r="15" />
        <circle cx="95%" cy="95%" r="15" />
    </svg>

    <!-- <div class="game-field outer">
        <div class="game-field middle">
            <div class="game-field inner">
                <div>Field1</div>
            </div>
        </div>
    </div> -->
</div>

<style>
    .board {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .game-field {
        height: 80vh;
        width: 80vh;
        background: var(--color-board-background);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* .outer {
        background-color: aqua;
        border: 5px solid black;
    }

    .middle {
        height: 500px;
        width: 500px;
        background-color: red;
        border: 5px solid black;
    }

    .inner {
        height: 250px;
        width: 250px;
        background-color: green;
        border: 5px solid black;
    }  */
</style>
