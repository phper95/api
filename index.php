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

<label>接口文件名：</label><input type="text" id="url" autocomplete="on" /><br/><br/><br/>
<label>接口参数：</label><textarea type="text" id="canshu" autocomplete="on" rows="3" cols="200" /></textarea><br/>
<input type="submit" value="测试" id="tijiao" / style="display:block;margin-left:1290px">
<div id="msg"></div>
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
                   $('#msg').html(res);
                }
            });
        });

    });
</script>