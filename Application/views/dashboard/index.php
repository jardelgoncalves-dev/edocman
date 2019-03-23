<?php

require '../Application/views/layout/head.php';
require '../Application/views/layout/menu.php';
?>
  <div class="container" style="margin-top:160px">
    <div class="row">

      <div class="title-page">
        <span>Recent Documents</span>
        <hr>
      </div>

      <div class="col responsive-table">
        <table class="header-primary highlight" id="documentsTable">
          <thead>
            <tr>
              <th>Name</th>
              <th>Tag</th>
              <th>Size</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <div class="text-right" style="margin-top:20px">
        <a href="/document/" class="btn btn-primary-line btn-full">See All</a>
      </div>
    </div>
  </div>
  
  <footer></footer>

  <script>
    function mountTableRow(document){
      return '<tr>' +
                '<td>' + document.filename + '</td>' + 
                '<td>' + document.tag_name + '</td>' + 
                '<td>' + document.size + '</td>' + 
                '<td><a class="link-view" href="/home/view/'+ document.id +'">View</a></td>' +
              '</tr>';
    }

    function loadDocuments(){
      $('#documentsTable>tbody').empty();
      $.get('/document/all', (document)=>{
        for(i=0;i<document.data.length;i++) {
          let row = mountTableRow(document.data[i]);
          $('#documentsTable>tbody').append(row);
        }
      });
    }

    $(()=>{
      loadDocuments();
    })
  </script>
<?php
require '../Application/views/layout/footer.php';
?>