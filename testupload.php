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

<form method="post" action="" enctype="multipart/form-data" id="myform">
  <div >
    <input type="file" id="file" name="file" onchange="loadImageFile();"/>
    <input type="button" class="button" value="Upload" id="televerser">
  </div>
</form>

<div id="#apercu"></div>
<img id="original-Img"/>

<!-- Your jQuery script (modified to remove the GET auth part) -->
<script>
var fileReader = new FileReader();
var filterType = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;

fileReader.onload = function (event) {
  var image = new Image();
  
  image.onload=function(){
      document.getElementById("original-Img").src=image.src;
      var canvas=document.createElement("canvas");
      var context=canvas.getContext("2d");
      canvas.width=image.width/4;
      canvas.height=image.height/4;
      context.drawImage(image,
          0,
          0,
          image.width,
          image.height,
          0,
          0,
          canvas.width,
          canvas.height
      );
      
      document.getElementById("upload-Preview").src = canvas.toDataURL();
  }
  image.src=event.target.result;
};

var loadImageFile = function () {
  var uploadImage = document.getElementById("upload-Image");
  
  //check and retuns the length of uploded file.
  if (uploadImage.files.length === 0) { 
    return; 
  }
  
  //Is Used for validate a valid file.
  var uploadFile = document.getElementById("upload-Image").files[0];
  if (!filterType.test(uploadFile.type)) {
    alert("Please select a valid image."); 
    return;
  }
  
  fileReader.readAsDataURL(uploadFile);
}

$(document).ready(function(){
  $("#televerser").click(function(){
	  var files = $('#file')[0].files[0];
		var formData = new FormData();
		formData.append("file", files);
		formData.append("fileName", "abc.jpg");
    formData.append("publicKey", "public_455lLx4XYwRN8q4k3cIxLHoJXbs=");

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
  });
});

  // Note that security isn't a problem atm as this code purpose is only testing
</script>
<?php include('parts/footer.php'); //on inclus le footer?>