<script>
    import Request from "../../../scripts/request";
    import { useNavigate } from "svelte-navigator";

    const navigate = useNavigate();
    let userName = "";
    let password = "";

    async function Login() {
        const data = {
            name: userName,
            pw: password,
        };

        let response = await Request("http://localhost:420/login", data).catch(
            (err) => {
                console.log(err);
                return;
            }
        );

        localStorage.setItem("token", response.token);
        navigate("home");

        console.log(localStorage.getItem("token"));
    }
</script>

<h1>Login Panel</h1>

<div class="container">
    <input type="text" placeholder="Username" bind:value={userName} />
    <input type="password" placeholder="Password" bind:value={password} />
    <button type="button" on:click={Login}>Login</button>
</div>

<style>
    .container {
        display: flex;
        gap: 16px;
    }
</style>
