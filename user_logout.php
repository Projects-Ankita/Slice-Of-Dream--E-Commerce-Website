<?php

include 'components/connect.php';

session_start();
session_unset();
session_destroy();

header('location:../Home/index.php');

?>

<script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
<script>
    window.addEventListener('load',function(){
        swal({
        title: "<?php foreach($message as $m){ echo $m; } ?>",
        icon: "success",
        button: "Ok",
        });
    });
</script>