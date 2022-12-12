<script>
    import { useNavigate } from "svelte-navigator";

    const navigate = useNavigate();

    let userName = "";
    let password = "";

    async function Register() {
        const data = {
            name: userName,
            pw: password,
        };
        await fetch("http://localhost:420/register", {
            method: "Post",
            mode: "cors",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify(data),
        })
            .then((response) => response.json())
            .then((data) => {
                const token = data.token;
                localStorage.setItem("token", token);
                navigate("home");
            })
            .catch((err) => {
                console.log(err);
            });

        console.log(localStorage.getItem("token"));
    }
</script>

<h1>Register Panel</h1>

<input type="text" placeholder="Username" bind:value={userName} />
<input type="password" placeholder="Password" bind:value={password} />

<button type="button" on:click={Register}>Register</button>

<style>
</style>
