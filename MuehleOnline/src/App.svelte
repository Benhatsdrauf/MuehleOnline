<script>
  import { Router, Route } from "svelte-navigator";
  import LandingPage from "./pages/LandingPage.svelte";
  import HomePage from "./pages/HomePage.svelte";
  import GamePage from "./pages/GamePage.svelte";
</script>

<Router>
  <main>
    <Route path="home">
      <HomePage />
    </Route>

    <Route path="gamePage">
      <GamePage />
    </Route>

    <Route>
      <LandingPage />
    </Route>
  </main>
</Router>

<style>
</style>
