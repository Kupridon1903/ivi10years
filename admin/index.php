
<?php

require_once "../src/DB.php";
require_once "../src/Config.php";

use MiniUpload\DB;

$db = new DB();
$query = $db->getComments();
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<title>IVI</title>

<body class="container-fluid bg-light" style="padding: 0">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
    <span class="navbar-brand">IVI</span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        </ul>
    </div>
</nav>
</br>
<p class="font-weight-bold text-md-left pl-1">Комментарии с хештегом:</p>

<table class="table table-hover table-striped pt-1" >
    <tbody>

    <?php
    while ($result = mysqli_fetch_array($query)) {
        echo "<tr>
                  <th scope = 'row'>
                    ID пользователя:  <a href='http://vk.com/id".$result['user_id']."'>".$result['user_id']."</a><div class=\"btn btn-warning ml-3 cover\" id='".$result['id']."'>На обложку</div>";
                    if ($result['visible'] == 0) echo "<div class=\"btn btn-info ml-3 visible\" id='".$result['id']."'>Отметить как просмотренное</div>";
                    echo "<div></div>
                      <div class='card card-body mt-3' style='max-width: 500px'>
                        ".$result['comment']."
                      </div>
                  </th>
              </tr>";
    }
    ?>
    </tbody>
</table>
</body>

<script>
    $(".cover").click(function () {
        $.ajax({
            type : 'POST',
            url :'cover.php',
            data: {
                id: $(this).attr('id'),
            },
            success: function(){
                location.reload();
            }
        });

    });
    $(".visible").click(function () {
        $.ajax({
            type : 'POST',
            url :'visible.php',
            data: {
                id: $(this).attr('id'),
            },
            success: function(){
                location.reload();
            }
        });
    });
</script>

