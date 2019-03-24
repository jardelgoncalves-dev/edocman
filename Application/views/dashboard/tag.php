<?php
  require '../Application/views/layout/head.php';
  require '../Application/views/layout/menu.php';
?>

<div class="container" style="margin-top:160px">
    <div class="row">

      <div class="title-page">
        <span>Tag List</span>
        <hr>
      </div>

      <div class="text-right" style="margin-bottom: 20px;">
        <button class="btn btn-primary-line btn-full" id="modal-show">Add <i class="fas fa-plus"></i></button>
      </div>

      <div class="col responsive-table">
        <table class="header-primary highlight" id="TagTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
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
        <span>Tag Registration</span>
        <hr>
      </div>
      <div class="col">
        <form method="POST" id="formTag">
          <div class="form-input">
            <label for="forName">Name</label>
            <input type="text" id="name" placeholder="Enter name">
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
    function mountTableRow(tag){
      return '<tr>' +
                '<td>' + tag.id + '</td>' + 
                '<td>' + tag.name + '</td>' + 
                '<td>' +
                  '<button onclick="deleteTag('+ tag.id +')" title="Delete Tag" class="btn btn-danger-line btn-sm">'+
                    '<i class="fas fa-trash "></i>'+
                  '</button>'+
                '</td>' +
             '</tr>';
    }

    function deleteTag(id) {
      $.get('/tag/destroy/' + id, ()=>{
        $('#TagTable>tbody').empty();
        loadTag();
      });
    }

    function loadTag(){
      $('#TagTable>tbody').empty();
      $.get('/tag/all', (tag)=>{
        for(i=0;i<tag.data.length;i++) {
          let row = mountTableRow(tag.data[i]);
          $('#TagTable>tbody').append(row);
        }
      });
    }

    $('#formTag').submit((event) => {
      event.preventDefault();

      let tag = {
        name: $('#name').val()
      }

      $.post('/tag/store', tag, ()=>{
        $('#myModal').hide();
        loadTag();
      })
    })

    $(()=>{
      loadTag();
    })
  </script>
<?php
  require '../Application/views/layout/footer.php';
?>