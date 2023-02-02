<script>
    import {Request} from "../../../scripts/request";
    import { useNavigate } from "svelte-navigator";

    import Fa from 'svelte-fa';
    import { faKey, faUser } from '@fortawesome/free-solid-svg-icons';
  import { hash } from "../../../scripts/hash";

    const navigate = useNavigate();

    let userName = "";
    let password = "";

    async function Register() {
        const data = {
            name: userName,
            pw: password,
        };

        let response = await Request(
            "auth/register",
            data
        ).catch((err) => {
            console.log(err);
            return;
        });

        localStorage.setItem("token", response.token);

        let splitToken = response.token.split("|")[1];
    // @ts-ignore
    localStorage.setItem("hashedToken", hash(splitToken));
        navigate("home");
    }
</script>


<div class="container-fluid">
    <div class="row">
      <div class="col">
        <h1>Register Panel</h1>
      </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text h-100">
                  <Fa icon={faUser}/>
                </span>
              </div>
              <input
                type="text"
                class="form-control"
                placeholder="Username"
                bind:value={userName}
              />
            </div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text h-100">
                  <Fa icon={faKey}/>
                </span>
              </div>
              <input
                type="password"
                class="form-control"
                placeholder="Password"
                bind:value={password}
              />
            </div>
      </div>
      <div class="row">
        <div class="col">
          <button type="button" on:click={Register}>Login</button>
        </div>
      </div>
    </div>
  </div>

<style>
</style>
