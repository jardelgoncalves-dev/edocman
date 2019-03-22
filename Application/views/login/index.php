<?php

use Application\core\security\CSRF;

require '../Application/views/layout/head.php';
?>

  <div class="wave-login">
    <div class="logo-login">
      <img src="assets/images/folder.png" alt="">
    </div>
    <div class="form-login">
    <div id="message"class="col-md-12"></div>
      <form method="POST" id="form-login">
        <?= CSRF::token() ?>
        <div class="form-input">
          <label for="">Email</label>
          <input type="text" id="email" placeholder="example@example.com">
        </div>
        <div class="form-input">
          <label for="">Password</label>
          <input type="password" id="password" placeholder="Enter password">
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-white-line text-md btn-full">Log in</button>
        </div>
      </form>
    </div>
  </div>



  <script>
    function create_alert(message, type = '') {
      return '<div class="alert '+ type + '" id="alert-message">' +
      '<span class="close-alert" onclick="this.parentElement.style.display=\'none\';">&times;</span>' +
       message + '</div>';
    }

    $('#form-login').submit((event) => {
      event.preventDefault();
      let result = $.post('/page/login', {
        email: $('#email').val(),
        password: $('#password').val(),
        csrf_token : $('#csrf_token').val()
      });

      result.done((data) => {
        window.location.replace(data.auth);
      });

      result.fail((data) =>{
        $('#message').empty();
        let error = data.responseJSON.error;
        if (error.password !== undefined || error.email !== undefined) {
          for(let prop in error) {
            $('#message').prepend(create_alert(error[prop][0], 'danger'));
          }
        }else {
          $('#message').prepend(create_alert(error, 'danger'));
        }
      });
    })
  </script>
<?php
require '../Application/views/layout/footer.php';
?>