<script>
    export let won = false;
    export let opponent = "";
    export let playtime = 0;
    export let count = 0;
    export let username = "";
    export let start_date = "";
</script>



<div class="card " class:bg-success="{won}" class:bg-danger="{!won}">
    <div class="card-body">
        <div class="row">
            <div class="col-auto right-border">
                {count}
            </div>
            <div class="col">
                <div>
                    {username} VS {opponent}
                </div>
            </div>
            <div class="col">
                {start_date}
            </div>
            <div class="col">
                Play time: {new Date(playtime * 1000).toISOString().slice(11, 19)}
            </div>
        </div>
    </div>
</div>

<style>

    .right-border
    {
        border-right: solid black 2px;
    }

</style>
