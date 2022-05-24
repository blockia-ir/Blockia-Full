    <footer class="footer d-print-none">
      <div class="container">
        <span class="text-success">
        <?php	// Script end
        $time_end = microtime(true);
        $time = round(($time_end - $time_start),3);
        echo " &nbsp;&nbsp;&nbsp;<span class='green' id='pt' > PT: {$time} </span> ";?>
        </span>
        
      </div>
    </footer>
    <script src="../lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
     // $(document).ready(function(){
       // $('a[href^="#none"]').html("none");
       // $('.multiple').multipleSelect();
     // });
     
    $(document).ready(function(){
      $('[data-toggle="popover"]').popover();
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    </script>
