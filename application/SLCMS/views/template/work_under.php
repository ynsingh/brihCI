<!--
 * @name work_under.php
   @author sumit saxena (sumitsesaxena@gmail.com)
 --->
<style>
body, html {
    height: 100%;
    margin: 0;
}

.bgimg {
    background-image: url('/w3images/forestbridge.jpg');
    height: 100%;
    background-position: center;
    background-size: cover;
    position: relative;
    color: black;
    font-family: "Courier New", Courier, monospace;
    font-size: 25px;
}

.topleft {
    position: absolute;
    top: 0;
    left: 16px;
}

.bottomleft {
    position: absolute;
    bottom: 0;
    left: 16px;
}

.middle {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

hr {
    margin: auto;
    width: 40%;
}
</style>
<div class="bgimg" style="">

  <div class="middle">
    <h1>Work Under Construction</h1>
    <hr>
   <p>Some work is left.</p>
	<?php echo date('d-m-Y H:i');?>

  </div>

