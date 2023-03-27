<script>
  import { Request } from "../../../scripts/request";
  import { useNavigate } from "svelte-navigator";
  import { hash } from "../../../scripts/hash";

  import Fa from "svelte-fa";
  import { faKey, faUser } from "@fortawesome/free-solid-svg-icons";
  import { each } from "svelte/internal";

  const navigate = useNavigate();
  let userName = "";
  let password = "";
  let errorMessages = [];

  async function Login() {
    errorMessages = [];

    const data = {
      name: userName,
      pw: password,
    };

    Request("auth/login", data)
      .then((response) => {
        localStorage.setItem("token", response.token);
        navigate("home");

        let splitToken = response.token.split("|")[1];
        hash(splitToken).then((hash) => {
          localStorage.setItem("hashedToken", hash);
        });
      })
      .catch((err) => {

        try
        {
          err.json().then((response) => {
          for (const property in response.errors) {
            errorMessages = [
              ...errorMessages,
              {
                field: property,
                message: response.errors[property],
              },
            ];
          }
        });
        }
        catch(exception)
        {
          
        }
      });
  }
</script>

<div class="container-fluid">
  <div class="row mb-3">
    <div class="col">
      <h1>Login</h1>
    </div>
  </div>
  <div class="row mb-3">
    <div class="col">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text h-100">
            <Fa icon={faUser} />
          </span>
        </div>
        <input
          type="text"
          class="form-control"
          placeholder="Username"
          bind:value={userName}
        />
      </div>
      <div class="form-text text-danger">
        {errorMessages.find((x) => x.field == "name")?.message ?? ""}
      </div>
    </div>
  </div>
  <div class="row mb-3">
    <div class="col">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text h-100">
            <Fa icon={faKey} />
          </span>
        </div>
        <input
          type="password"
          class="form-control"
          placeholder="Password"
          bind:value={password}
        />
      </div>
      <div class="form-text text-danger">
        {errorMessages.find((x) => x.field == "pw")?.message ?? ""}
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <button type="button" class="btn btn-outline-primary" on:click={Login}
        >Login</button
      >
    </div>
  </div>
</div>

<style>
</style>
