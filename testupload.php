<?php include('parts/header.php'); //on inclus le header?>
<?php
  // JQuery was previously included
  // Including an external dependency to generate UUID tokens
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

  $token = $uuid4->toString(); // Generating the token e.g. 06a7c148-e553-4a2a-80ca-10dd8ad7eb64
  $expire = time() + (60 * 59);  // Generating the expire time (in 59 minutes) e.g. 1567317691
  $signature = hash_hmac('sha1', $token . $expire, $imagekitapi); // Creating the signature. I have tested your code and mine, and with the same inputs there is the exact same outputs.
?>

<!-- Your form code -->
<form action="#" onsubmit="upload()">
	<input type="file" id="file1" />
	<input type="submit" />
</form>


<div><?php echo $signature ?></div>
<div><?php echo json_encode($signature, JSON_HEX_TAG); ?></div>
<div><?php echo $token ?></div>
<div><?php echo json_encode($token, JSON_HEX_TAG); ?></div>
<div><?php echo $expire ?></div>
<div><?php echo json_encode($expire, JSON_HEX_TAG); ?></div>
<div><?php echo json_encode($token . $expire, JSON_HEX_TAG); ?></div>

<!-- Your jQuery script (modified to remove the GET auth part) -->
<script>

	function upload() {
	  var file = document.getElementById("file1");
		var formData = new FormData();
		formData.append("file", file);
		formData.append("fileName", "abc.jpg");
    formData.append("publicKey", "public_455lLx4XYwRN8q4k3cIxLHoJXbs");

    // Let's get the signature, token and expire from server side
    formData.append("signature", <?php echo json_encode($signature, JSON_HEX_TAG); ?> || "");
		formData.append("expire", <?php echo $expire ?> || 0);
		formData.append("token", <?php echo json_encode($token, JSON_HEX_TAG); ?>);
		$.ajax({
      url : "https://upload.imagekit.io/api/v1/files/upload",
      method : "POST",
      mimeType : "multipart/form-data",
      dataType : "json",
      data : formData,
      processData : false,
      contentType : false,
      error : function(jqxhr, text, error) {
        console.log(error);
        return false;
      },
      success : function(body) {
        console.log(body);
        return false;
      }
    });
  }

  // Note that security isn't a problem atm as this code purpose is only testing
</script>
<?php include('parts/footer.php'); //on inclus le footer?>