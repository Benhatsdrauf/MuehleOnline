<script>
  import { Router, Route } from "svelte-navigator";
  import LandingPage from "./pages/LandingPage.svelte";
  import HomePage from "./pages/HomePage.svelte";
  import GamePage from "./pages/GamePage.svelte";
  import LobbyPage from "./pages/LobbyPage.svelte";
  import Impressum from "./pages/Impressum.svelte";
</script>

<Router>
  <main>
    <Route path="home">
      <HomePage />
    </Route>

    <Route path="gamePage">
      <GamePage />
    </Route>

    <Route path="game/join/:id" let:params>
      <LobbyPage />
    </Route>

    <Route>
      <LandingPage />
    </Route>

    <Route path="impressum">
      <Impressum/>
    </Route>
  </main>
</Router>

<style>
</style>
