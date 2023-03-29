<script>
  export let date = new Date();
  export let useTextFormat = false;

  let result = "";

$: date, countdown();

  function countdown() {
    // Update the count down every 1 second
    let x = setInterval(function () {
      // Get today's date and time
      let now = new Date().getTime();

      // Find the distance between now and the count down date
      let distance = date.getTime() - now;

      // Time calculations for days, hours, minutes and seconds
      let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      let seconds = Math.floor((distance % (1000 * 60)) / 1000);

      if (useTextFormat) {
        result = minutes + "m " + seconds + "s ";
      } else {
        result =
          minutes +
          ":" +
          (seconds.toString().length == 1 ? "0" + seconds : seconds);
      }

      // If the count down is over, write some text
      if (distance < 1000) {
        clearInterval(x);

        if (useTextFormat) {
          result = "no time";
        } else {
          result = "time is up";
        }
      }
    }, 1000);
  }
</script>

{result}
