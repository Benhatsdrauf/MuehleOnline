<script>
    export let won = false;
    export let opponent = "";
    export let playtime = 0;
    export let count = 0;
    export let username = "";
    export let start_date = "";
    export let elo = 0;

    let date = new Date(start_date);
</script>



<div class="card" class:bg-success="{won}" class:bg-danger="{!won}">
    <div class="card-body">
        <div class="row">
            <div class="col-auto right-border c-text">
                {count}
            </div>
            <div class="col c-text">
                {username} VS {opponent}
            </div>
            <div class="col c-text text-center">
                {(won) ? "+" : ""}{elo}
            </div>
            <div class="col c-text text-center">
                Play time: {new Date(playtime * 1000).toISOString().slice(11, 19)}
            </div>
            <div class="col c-text text-end">
                {`${date.getDate()}.${date.getMonth() + 1}.${date.getFullYear()}`}
            </div>
        </div>
    </div>
</div>

<style>

    .right-border
    {
        border-right: solid var(--color-white) 2px;
    }

    .card{
        border: solid var(--color-black) 2px;
    }

</style>
