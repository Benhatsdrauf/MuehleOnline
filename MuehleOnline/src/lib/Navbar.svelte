<script>
  import logo from "../assets/MühleIcon.png";
</script>

<nav class="navbar bgc-secondary">
  <div class="container-fluid">
    <a class="navbar-brand c-text me-auto" href="/home">
      <img src={logo} alt="muehleIcon" width="30px" height="30px" class="d-inline-block align-text-top"/>
      MühleOnline
    </a>

    <slot />
  </div>
</nav>

<style>
  img{
    filter: invert(100%);
  }
</style>
