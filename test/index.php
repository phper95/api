<?php
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>apiTest</title>
    <script src="http://lib.sinaapp.com/js/jquery/1.9.1/jquery-1.9.1.min.js"></script>
</head>
<body>
<form action="post">

</form>
<input type="text" id="url" autocomplete="on" />
<input type="text" id="canshu" autocomplete="on" />
<input type="submit" value="测试" id="tijiao" />
</body>
</html>
<script>
    $(function(){

        $('#tijiao').on('click',function(){
            var url=$('#url').val();
            var data=$('#canshu').val();
            $.ajax({
                url: url,
                type: 'post',
                data: data,
                success: function (res) {
                   alert(res);
                    console.log(res)
                }
            });
        });

    });
</script>