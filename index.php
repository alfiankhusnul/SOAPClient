<?php 
	ini_set('display_errors', true);
	error_reporting(E_ALL);
	require_once('lib/nusoap.php');
	$keys = "";
	$result = array();
	$wsdl = "http://localhost/SOAPServer/service.php?wsdl";
	
	$client = new nusoap_client($wsdl, true);
	$client->soap_defencoding = 'UTF-8';
	$client->decode_utf8 = false;
	$err = $client->getError();
	if($err){
		
		echo '<h2>Constructor error</h2><pre>'.$err.'</pre>';
		echo '<h2>Debug</h2><pre>'.htmlspecialchars($client->getDebug(), ENT_QUOTES).'</pre>';
		exit();
		
	}
	
	$err='';
	
	if (isset($_POST["txtCari"])) {
        if(trim($_POST['txtCari']) && !empty($_POST["txtCari"])){  
    		$keys = $_POST["txtCari"];
    		$result = $client->call('ambilDataUser', array('cari' => $keys));
    //		var_dump($result);
    //		die();
            if(empty($result)){
    		    $err = "<div class=\"alert alert-danger\" role=\"alert\">";
    			$err .= 'Data tidak ditemukan.';
    			$err .= "</div>";
    	   }
			
        } else{
            $err = "<div class=\"alert alert-danger\" role=\"alert\">";
			$err .= 'Kolom tidak boleh kosong.';
			$err .= "</div>";
		}
    }
?>

<!doctype html>
<html>
	<head>
		<title>Tes Web Service Client</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	</head>
	<body>
		<div class="container">
    		<form action="index.php" method="POST">
    			<div class="form-group row">
        			<label class="col-sm-2 col-form-label">Masukkan nama:</label>
        			<div class="col-sm-4">
          				<input type="text" class="form-control" id="txtCari" name="txtCari" placeholder="sebuah nama" autocomplete="off">
        			</div>
        			<div class="col-sm-2">
          				<input type="submit" class="btn" value="CARI">
        			</div>
      			</div>
    		</form>
			<table class="table">
    			<thead>
    				<th>Id User</th>
    				<th>Nama Lengkap</th>
    				<th>Alamat</th>
    				<th>Jenis Kelamin</th>
    			</thead>
    			<tbody>
				<?php
					if(count($result) > 0) {
						if (!isset($result["0"])) { //hanya 1 data user
							echo "<tr>
									<td>" . $result["id_user"] . "</td>
									<td>" . $result["nama_lengkap"] . "</td>
									<td>" . $result["alamat"] . "</td>
									<td>" . $result["jenis_kelamin"] . "</td>
								</tr>";
						} else { //lebih dari 1 data user
							foreach($result as $user) {
								echo "<tr>
										<td>" . $user["id_user"] . "</td>
										<td>" . $user["nama_lengkap"] . "</td>
										<td>" . $user["alamat"] . "</td>
										<td>" . $user["jenis_kelamin"] . "</td>
									</tr>";
							}
						}
					}
				?>
				</tbody>
    		</table>
<!--     		placehoder untuk pesan error -->
    		<?php echo $err; ?>
		</div>
	</body>
</html>
