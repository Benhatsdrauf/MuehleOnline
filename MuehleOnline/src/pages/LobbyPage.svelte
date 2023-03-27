<script>
  import Modal from "../lib/Modal.svelte";
  import { LoginPanel, RegisterPanel } from "../lib/Panels";
  import { Tabs, TabList, TabPanel, Tab } from "../lib/Tabs";
  import { useNavigate } from "svelte-navigator";
  import { AuthorizedGetRequest, Request } from "../../scripts/request";
  import { onMount } from "svelte";
  import { hash } from "../../scripts/hash";
  import Fa from "svelte-fa";
  import { faKey, faUser } from "@fortawesome/free-solid-svg-icons";
  import MessageModal from "../lib/MessageModal.svelte";

  const navigate = useNavigate();
  let showModal = false;
  let path = window.location.pathname;

  onMount(() => {
    if (localStorage.getItem("token") === null) {
      showModal = true;
      return;
    }

    RegisterUsertoGame();
  });

  function RegisterUsertoGame() {
    AuthorizedGetRequest("game/join/" + path.split("/")[3])
      .then((response) => {
        navigate("/gamePage");
      })
      .catch((err) => {
        try {
          err.json().then((response) => {
            if (response.errors.guid) {
              messageModalMessage = response.errors.guid;
              messageModalIsError = true;
              messageModalShow = true;
            }
          });
        } catch (exception) {
          messageModalMessage =
            "Faild to connect to server, please try again later.";
          messageModalIsError = true;
          messageModalShow = true;
        }
      });
  }

  let userName = "";
  let password = "";
  let errorMessages = [];

  let messageModalMessage = "";
  let messageModalShow = false;
  let messageModalIsError = true;

  function Login() {
    errorMessages = [];

    const data = {
      name: userName,
      pw: password,
    };

    Request("auth/login", data)
      .then((response) => {
        localStorage.setItem("token", response.token);

        let splitToken = response.token.split("|")[1];
        hash(splitToken).then((hash) => {
          localStorage.setItem("hashedToken", hash);
        });
        RegisterUsertoGame();
      })
      .catch((err) => {
        try {
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
        } catch (exception) {
          errorMessages = [
            ...errorMessages,
            {
              field: "server",
              message:
                "Could not connect to the server, please try again later.",
            },
          ];
        }
      });
  }
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
        RegisterUsertoGame();
      })
      .catch((err) => {
        try {
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
        } catch (exception) {
          errorMessages = [
            ...errorMessages,
            {
              field: "server",
              message:
                "Could not connect to the server, please try again later.",
            },
          ];
        }
      });
  }
</script>

<div class="container-fluid">
  <div class="row">
    <div class="col d-flex justify-content-center mt-5">
      <div class="card w-50">
        <div class="card-header">
          <h5>Join game</h5>
        </div>
        <div class="card-body">
          <Tabs>
            <TabList>
              <Tab>Login</Tab>
              <Tab>Register</Tab>
            </TabList>

            <TabPanel>
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
                      {errorMessages.find((x) => x.field == "name")?.message ??
                        ""}
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
                      {errorMessages.find((x) => x.field == "pw")?.message ??
                        ""}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-auto">
                    <button
                      type="button"
                      class="btn btn-outline-primary"
                      on:click={Login}>Login</button
                    >
                  </div>
                  <div class="col-auto text-danger">
                    {errorMessages.find((x) => x.field == "server")?.message ??
                      ""}
                  </div>
                </div>
              </div>
            </TabPanel>
            <TabPanel>
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
                      {errorMessages.find((x) => x.field == "name")?.message ??
                        ""}
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
                      {errorMessages.find((x) => x.field == "pw")?.message ??
                        ""}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-auto">
                    <button
                      type="button"
                      class="btn btn-outline-primary"
                      on:click={Register}>Register</button
                    >
                  </div>
                  <div class="col-auto text-danger">
                    {errorMessages.find((x) => x.field == "server")?.message ??
                      ""}
                  </div>
                </div>
              </div>
            </TabPanel>
          </Tabs>
        </div>
      </div>
    </div>
  </div>
</div>
<MessageModal
  bind:showModal={messageModalShow}
  isError={messageModalIsError}
  message={messageModalMessage}
/>
