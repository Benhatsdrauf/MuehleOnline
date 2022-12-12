<script>
    import authorizedRequest from "../../scripts/authorizedRequest";

    import { useNavigate } from "svelte-navigator";

    const navigate = useNavigate();

    async function Logout() {
        let response = await authorizedRequest(
            "http://localhost:420/logout"
        ).catch((err) => {
            console.log(err);
            return;
        });

        navigate("/");
    }
</script>

<h3>Home</h3>
<p>This is gonna be the home/statistics page after the user is logged in</p>

<button on:click={Logout}>Logout</button>

<style>
</style>
