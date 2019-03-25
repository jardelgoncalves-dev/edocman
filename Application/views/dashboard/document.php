<?php

require '../Application/views/layout/head.php';
require '../Application/views/layout/menu.php';
?>

  <div class="container" style="margin-top:160px">
    <div class="row">
      <div class="col">

        <form method="POST" id="formSearch">
          <div class="form-input">
            <label for="forKeyword">Keyword</label>
            <input type="text" id="keyword" placeholder="e.g: joão">
          </div>
          <div style="margin-top:20px">
            <label class="radio">Simple
              <input checked="checked" type="radio" value="simple" name="type_search" id="type_search">
              <span class="checkmark"></span>
            </label>
            <label class="radio">Advanced
              <input type="radio" value="advanced" name="type_search" id="type_search">
              <span class="checkmark"></span>
            </label>
          </div>
          <div class="text-right">
            <button type="submit" class="btn btn-primary-line btn-full">Search <i class="fas fa-search"></i></button>
          </div>

        </form>


        <div class="row" style="margin-top:50px">

          <div class="title-page">
            <span>Documents</span>
            <hr>
          </div>

          <div class="text-right" style="margin-bottom: 20px;">
            <button class="btn btn-primary-line btn-full" id="modal-show">Add <i class="fas fa-plus"></i></button>
          </div>

          <div id="message"></div>
          <div id="loading" class="text-center">
            <img src="/assets/images/loading.gif" width="100px">
          </div>
          <div id="directoryTag"></div>
          <div id="button-back" class="text-right">
            <button onclick="loadInfoTags()" class="btn btn-primary-line btn-full"> Back </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div id="myModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <div class="title-page">
        <span>Add File</span>
        <hr>
      </div>
      <div class="col">
        <form method="POST" enctype="multipart/form-data" id="formFile">
          <div class="form-input">
            <label for="forName">Filename</label>
            <input type="text" id="filename" placeholder="Enter name">
          </div>
          <div class="form-input">
            <label for="forEmail">Author</label>
            <input type="text" id="author" placeholder="Enter email">
          </div>
          <div class="form-input">
            <label for="forAccessLevel">Tag</label>
            <select id="tag_id">
              <?php
              foreach($data['tags'] as $tag) {
                echo '<option value="'. $tag['id'] .'">'. $tag['name'] .'</option>';
              }
              ?>
            </select>
          </div>
          <div class="form-input">
            <label for="forEmail">File</label>
            <input type="file" id="file">
          </div>
          <div style="margin-top:20px">
            <button class="btn btn-success-line">Save</button>
            <button type="reset" class="btn btn-danger-line">Reset</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End modal -->

  <footer></footer>
  <script>
    function hideLoader() {
      $('#loading').hide();
      $('#button-back').hide();
    }

    $(window).ready(hideLoader);


    function create_alert(message, type = '') {
      return '<div class="alert '+ type + '" id="alert-message">' +
      '<span class="close-alert" onclick="this.parentElement.style.display=\'none\';">&times;</span>' +
       message + '</div>';
    }

    function getFileTag(id) {
      $('#directoryTag').empty();
      $('#directoryTag').empty();
      $('#loading').show();
      let result = $.get('/document/tag/' + id);
      result.done(document=>{
        $('#loading').hide();
        if (document.data.length > 0 ){
          for (let i = 0; i < document.data.length; i++) {
            $('#directoryTag').append(createFile(document.data[i]));
          }
        } else {
          $('#directoryTag').append(createTitle('Document not found!'));
        }
        $('#button-back').show();
      })

    }

    function mountTableRow( infoTag ) {
      return '<div class="card col col-3">' +
                '<div class="flip-card"> '+
                  '<div class="flip-card-inner">' +
                    '<div class="flip-card-front">' +
                      '<img src="/assets/images/folder-tag.png" alt="Avatar" style="width:200px;height:200px;">' +
                    '</div>'+
                    '<div class="flip-card-back">' +
                      '<p style="font-size:17px">' +
                        '<strong>Create:</strong> ' + infoTag.created +
                      '</p>'+
                      '<p style="font-size:17px">' +
                        '<strong>Size:</strong> '+ infoTag.total_size +' b' +
                      '</p>' +
                      '<p style="font-size:17px">'+
                        '<strong>Number of files:</strong> ' + infoTag.total_files +
                      '</p>' +
                    '</div>' +
                  '</div>' +
                '</div>' +
                '<button onclick="getFileTag('+ infoTag.id +')" class="link-view"><span>'+ infoTag.name +'</span></button>'+
              '</div>';
    }

    function createTitle(message) {
      return  '<div class="text-center">' +
                '<p style="font-size:30px">'+ message +'</p>' +
              '</div> <br>';
    }

    function createFile(document) {
      return '<div class="card col col-3 text-center">' + 
                '<img src="/assets/images/document.png" width="200" >' +
                '<p><a class="link-view" href="/home/view/'+ document.id +'"  style="font-size:20px">'+ document.filename +'</a></p>' +
                '<hr> <br>' +
             '</div>'; 
    }

    function loadInfoTags(){
      $('#directoryTag').empty();
      $('#button-back').hide();
      $.get('/tag/info', (infoTag)=>{
        for(i=0;i<infoTag.data.length;i++) {
          let row = mountTableRow(infoTag.data[i]);
          $('#directoryTag').append(row);
        }
      });
    }

    $('#formFile').submit((event) => {
      event.preventDefault();
      var fd = new FormData();
      fd.append('filename', $('#filename').val());
      fd.append('author', $('#author').val());
      fd.append('tag_id', $('#tag_id').val());
      fd.append('image', $('input[type="file"]')[0].files[0]);

      let result = $.ajax({
        url: '/document/store',
        type: 'post',
        data : fd,
        contentType: false,
        processData: false,
      });

      result.done((data) => {
        $('#myModal').hide();
        $('#message').empty().prepend(create_alert('File uploaded successfully', 'success'));
        loadInfoTags();
      })

      result.fail((data) => {
        $('#myModal').hide();
        $('#message').empty();
        let error = data.responseJSON.error;
        if (error.filename !== undefined || error.author !== undefined ||
            error.size !== undefined || error.image !== undefined || error.tag_id !== undefined) {
          for(let prop in error) {
            $('#message').prepend(create_alert(error[prop][0], 'danger'));
          }
        }else {
          $('#message').prepend(create_alert(error, 'danger'));
        }
      })
    });

    $('#formSearch').submit((event)=>{
      $('#directoryTag').empty();
      event.preventDefault();

      $('#loading').show();

      var keyword = $('#keyword').val();
      let type = $('#type_search:checked').val();
      let result = $.get('/document/search/' + keyword + '/' + type);
      result.done(document => {
        $('#loading').hide();
        $('#directoryTag').append(createTitle('Documents that contain the word "'+ keyword+'"'));
        if (document.data.length > 0 ){
          for (let i = 0; i < document.data.length; i++) {
            $('#directoryTag').append(createFile(document.data[i]));
          }
        $('#button-back').show();
        } else {
          $('#button-back').show();
          $('#directoryTag').append(createTitle('Document not found!'));
        }
      })

      result.fail(fail=>{
        $('#button-back').show();
        $('#loading').hide();
        $('#directoryTag').append(createTitle('Document not found!'));
      })

    })
    

    $(()=>{
      loadInfoTags();
    })

  </script>

<?php
require '../Application/views/layout/footer.php';
?>