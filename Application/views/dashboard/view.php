<?php

use Application\core\security\Auth;
use Application\core\security\CSRF;

require '../Application/views/layout/head.php';
require '../Application/views/layout/menu.php';
?>
  <div class="container" style="margin-top:160px">
    <div class="row">
      <?php
      if (count($data)) {
      ?>
      <div class="title-page">
        <span><?= $data['filename'] ?></span>
        <hr>
      </div>
      <div class="text-center">
        <img src="<?= $data['image'] ?>" alt="">
      </div>
      <?php } else {
        echo '<div style="margin-top:100px;margin-bottom:100px" class="text-center"><p style="font-size:30px">Image not found!</p></div>';
      } ?>
    </div>
  </div>
  <footer></footer>
<?php
require '../Application/views/layout/footer.php';
?>