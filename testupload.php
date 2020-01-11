<?php include('parts/header.php'); //on inclus le header?>
<?php
  require 'vendor/autoload.php';
  use Ramsey\Uuid\Uuid;
  use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
  
  try {
    // Generate a version 4 (random) UUID object
    $uuid4 = Uuid::uuid4();
  } catch (UnsatisfiedDependencyException $e) {
    // Some dependency was not met. Either the method cannot be called on a
    // 32-bit system, or it can, but it relies on Moontoast\Math to be present.
    echo 'Caught exception: ' . $e->getMessage() . "\n";
  }

  $token = $uuid4->toString();
  $expire = time() + (60 * 60);
  $signature = hash_hmac('sha1', $token . $expire, $imagekitapi);
?>

<script>
	// This endpoitn should be implemented on your server
  
  var authenticationEndpoint = <?= json_encode($url . 'auth', JSON_HEX_TAG); ?>;
	
	$('.img-upload').submit(function() {
	  var file = document.getElementById("file1");
		var formData = new FormData();
		formData.append("file", file);
		formData.append("fileName", "abc.jpg");
		formData.append("publicKey", "your_public_api_key");
		// Let's get the signature, token and expire from server side
    formData.append("signature", $signature || "");
    formData.append("expire", $expire || 0);
    formData.append("token", $token);
	
		// Now call ImageKit.io upload API
    $.ajax({
        url : "https://upload.imagekit.io/api/v1/files/upload",
        method : "POST",
        mimeType : "multipart/form-data",
        dataType : "json",
        data : formData,
        processData : false,
        contentType : false,
        error : function(jqxhr, text, error) {
            console.log(error)
        },
        success : function(body) {
            console.log(body)
        }
    });
	});
</script>

<form action="#" class="img-upload">
	<input type="file" id="file1" />
	<input type="submit" value="submit" />
</form>

<?php include('parts/footer.php'); //on inclus le footer?>