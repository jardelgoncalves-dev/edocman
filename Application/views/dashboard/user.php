<?php

use Application\core\security\Auth;
use Application\core\security\CSRF;

require '../Application/views/layout/head.php';
require '../Application/views/layout/menu.php';
?>
  <div class="container" style="margin-top:160px">
    <div class="row">

      <div class="title-page">
        <span>User List</span>
        <hr>
      </div>

      <div class="text-right" style="margin-bottom: 20px;">
        <button class="btn btn-primary-line btn-full" id="modal-show">Add <i class="fas fa-plus"></i></button>
      </div>

      <div class="col responsive-table">
        <table class="header-primary highlight" id="UserTable">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Access Level</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div id="myModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <div class="title-page">
        <span>User Registration</span>
        <hr>
      </div>
      <div class="col">
        <form method="POST" id="formUser">
          <?= CSRF::token() ?>
          <div class="form-input">
            <label for="forName">Name</label>
            <input type="text" id="name" placeholder="Enter name">
          </div>
          <div class="form-input">
            <label for="forEmail">Email</label>
            <input type="email" id="email" placeholder="Enter email">
          </div>
          <div class="form-input">
            <label for="forEmail">Password</label>
            <input type="password" id="password" placeholder="Enter password">
          </div>
          <div class="form-input">
            <label for="forAccessLevel">Access Level</label>
            <select id="access_level">
            <?php
              foreach ($data['access_level'] as $access_level) {
                  echo '<option value="'. $access_level['id'] .'">'. ucfirst($access_level['name']) .'</option>';
              }
            ?>
            </select>
          </div>
          <div style="margin-top:20px">
            <button type="submit" class="btn btn-success-line">Save</button>
            <button type="reset" class="btn btn-danger-line">Reset</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End modal -->

  <footer></footer>

  <script>
    function mountTableRow(user){
      return '<tr>' +
                '<td>' + user.name + '</td>' + 
                '<td>' + user.email + '</td>' + 
                '<td>' + user.access_level + '</td>' + 
                '<td>' +
                  '<button onclick="deleteUser('+ user.id +')" title="Delete user" class="btn btn-danger-line btn-sm">'+
                    '<i class="fas fa-trash "></i>'+
                  '</button>'+
                '</td>' +
              '</tr>';
    }

    function is_user_logged(id) {
      return id === <?= Auth::get_user()->id ?> ? true : false;
    }

    function deleteUser(id) {
      $.get('/user/destroy/' + id, ()=>{
        $('#UserTable>tbody').empty();
        loadUser();
      });
    }

    $('#formUser').submit((event) => {
      event.preventDefault();

      let user = {
        name: $('#name').val(),
        email: $('#email').val(),
        password: $('#password').val(),
        access_level_id : $('#access_level').val(),
        csrf_token : $('#csrf_token').val()
      }

      $.post('/user/store', user, ()=>{
        $('#myModal').hide();
        loadUser();
      })
    })

    function loadUser(){
      $('#UserTable>tbody').empty();
      $.get('/user/all', (user)=>{
        for(i=0;i<user.data.length;i++) {
          if (!is_user_logged(user.data[i].id) ) {
            let row = mountTableRow(user.data[i]);
            $('#UserTable>tbody').append(row);
          }
        }
      });
    }

    $(()=>{
      loadUser();
    })
  </script>
<?php
require '../Application/views/layout/footer.php';
?>