<?php include('parts/header.php'); //on inclus le header?>
<form action="#" onsubmit="upload()">
	<input type="file" id="file1" />
	<input type="submit" />
</form>
<script type="text/javascript" src="node_modules/imagekit-javascript/dist/imagekit.js"></script>

<script>
    // SDK initilization
    // authenticationEndpoint should be implemented on your server
    var imagekit = new ImageKit({
        publicKey : "public_455lLx4XYwRN8q4k3cIxLHoJXbs=",
        urlEndpoint : "https://ik.imagekit.io/voyages",
        authenticationEndpoint : <?php echo(json_encode($url . 'auth', JSON_HEX_TAG)); ?>
    });
    
    // Upload function internally uses the ImageKit.io javascript SDK
    function upload(data) {
        var file = document.getElementById("file1");
        imagekit.upload({
            file : file.files[0],
            fileName : "abc1.jpg",
            tags : ["tag1"]
        }, function(err, result) {
            console.log(arguments);
            console.log(imagekit.url({
                src: result.url,
                transformation : [{ HEIGHT: 300, WIDTH: 400}]
            }));
        })
    }
</script>
<?php include('parts/footer.php'); //on inclus le header?>