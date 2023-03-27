<script>
  import { Request } from "../../../scripts/request";
  import { useNavigate } from "svelte-navigator";

  import Fa from "svelte-fa";
  import { faKey, faUser } from "@fortawesome/free-solid-svg-icons";
  import { hash } from "../../../scripts/hash";

  const navigate = useNavigate();

  let userName = "";
  let password = "";
  let errorMessages = [];

  async function Register() {
    errorMessages = [];

    const data = {
      name: userName,
      pw: password,
    };

    Request("auth/register", data)
      .then((response) => {
        localStorage.setItem("token", response.token);

        let splitToken = response.token.split("|")[1];

        hash(splitToken).then((hash) => {
          localStorage.setItem("hashedToken", hash);
        });
        navigate("home");
      })
      .catch((err) => {
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
      });
  }
</script>

<div class="container-fluid">
  <div class="row mb-3">
    <div class="col">
      <h1>Register</h1>
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
      <button type="button" class="btn btn-outline-primary" on:click={Register}
        >Register</button
      >
    </div>
  </div>
</div>

<style>
</style>
