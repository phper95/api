<html>
<body>
	<?php
		require_once('./AES.php');
		//$aes = new AES();
		$aes = new AES(true);// ?????????????????
		//$aes = new AES(true,true);//???????????????????
		$key = "this is a 32 byte key";// ??
		$keys = $aes->makeKey($key);
		$encode = "123456";//???????
		$ct = $aes->encryptString($encode, $keys);
		echo "encode = ".$ct."<br>";
		$cpt = $aes->decryptString($ct, $keys);
		echo "decode = ".$cpt;
	?>
</body>
</html>