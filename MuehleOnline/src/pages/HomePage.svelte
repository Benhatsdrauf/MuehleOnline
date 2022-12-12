<script>
    import { useNavigate } from "svelte-navigator";

    const navigate = useNavigate();

    async function Logout() {
        await fetch("http://localhost:420/logout", {
            method: "Post",
            mode: "cors",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        })
            .then(() => {
                localStorage.removeItem("token");
                navigate("/");
            })
            .catch((err) => {
                console.log(err);
            });
    }
</script>

<h3>Home</h3>
<p>This is gonna be the home/statistics page after the user is logged in</p>

<button on:click={Logout}>Logout</button>

<style>
</style>
