<!--DOCTYPE html-->
<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
echo "<script type='text/javascript'>alert('Please Login First!');</script>";
//echo "<div>PLease login first </div>";
header('location: loginpage/usermanagement/index.php');
}
else{

   if(isset($_POST['submit'])){	

	$file = $_FILES['image']['name'];
	$file_loc = $_FILES['image']['tmp_name'];
	$folder="images/"; 
	$new_file_name = strtolower($file);
	$final_file=str_replace(' ','-',$new_file_name);
	
	$time=$_POST['time'];
	$money=$_POST['money'];
	$performance= $_POST['performance'];
	$message=$_POST['message'];
	$image1=$_POST['image1'];
	$image2=$_POST['image2'];
	$image3=$_POST['image3'];
	
	if(move_uploaded_file($file_loc,$folder.$final_file))
		{
			$image=$final_file;
		}
	//$notitype='Send Image Query';
	//$reciver='Admin';
	//$sender=$email;
	
	//$sqlnoti="insert into notification (notireciver,notitype) values (:notiuser,:notireciver,:notitype)";
	//$querynoti = $dbh->prepare($sqlnoti);
	//$querynoti-> bindParam(':notiuser', $sender, PDO::PARAM_STR);
	//$querynoti-> bindParam(':notireciver',$reciver, PDO::PARAM_STR);
	//$querynoti-> bindParam(':notitype', $notitype, PDO::PARAM_STR);
	//$querynoti->execute();    
		
	$sql ="INSERT INTO imagequery(Time,Money, Performance, Message, Image1, Image2, Image3) VALUES(:time, :money, :performance, :message, :image1, :image2, :image3)";
	//$sql ="SELECT Time,password FROM users WHERE email=:email and password=:password and status=(:status)";
	$query= $dbh -> prepare($sql);
	$query-> bindParam(':time', $time, PDO::PARAM_STR);
	$query-> bindParam(':money', $money, PDO::PARAM_STR);
	$query-> bindParam(':performance', $performance, PDO::PARAM_STR);
	$query-> bindParam(':message', $message, PDO::PARAM_STR);
	$query-> bindParam(':image1', $image1, PDO::PARAM_STR);
	$query-> bindParam(':image2', $image2, PDO::PARAM_STR);
	$query-> bindParam(':image3', $image3, PDO::PARAM_STR);
	$query->execute();
	$lastInsertId = $dbh->lastInsertId();
	if($lastInsertId)
	{
	echo "<script type='text/javascript'>alert('Query succesfully sent Sucessfull!');</script>";
	echo "<script type='text/javascript'> document.location = 'index.html'; </script>";
	}
	else 
	{
	$error="Something went wrong. Please try again";
	}
	}





?>
<html lang="en">
<head>
	<title>NFT viewer</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<!--link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css"-->
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<!--link rel="stylesheet" type="text/css" href="vendor/animate/animate.css"-->
<!--===============================================================================================-->
	<!--link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css"-->
<!--===============================================================================================-->
	<!--link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css"-->
<!--===============================================================================================-->
	<!--link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css"-->
<!--===============================================================================================-->
	<!--link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css"-->
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>


	<div class="container-contact100">
		<div class="contact100-map" id="google_map" data-map-x="40.722047" data-map-y="-73.986422" data-pin="images/icons/map-marker.png" data-scrollwhell="0" data-draggable="1"></div>

		<div class="wrap-contact100">
			<div class="contact100-form-title" style="background-image: url(images/bg-01.jpg);">
				<span class="contact100-form-title-1">
					Please provide the details
				</span>

				<span class="contact100-form-title-2">
					Feel free to drop us a line below!
				</span>
			</div>

			<form class="contact100-form validate-form">
				<div class="wrap-input100 validate-input" data-validate="">
					<span class="label-input100">Time: </span>
					<input name = "time" class="input100" type="text" id="time" placeholder=".....">
					<span class="focus-input100"></span>
				</div>
				<div class="wrap-input100 validate-input" data-validate="">
					<span class="label-input100"> Money: </span>
					<input name = "money"class="input100" type="text" id="money" placeholder=".....">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input" data-validate="">
					<span class="label-input100"> Performance: </span>
					<input name = "performance"class="input100" type="text" id="performance" placeholder=".....">
					<span class="focus-input100"></span>
				</div>



				<!--div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
					<span class="label-input100">Email:</span>
					<input class="input100" type="text" name="email" placeholder="Enter email addess">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input" data-validate="Phone is required">
					<span class="label-input100">Phone:</span>
					<input class="input100" type="text" name="phone" placeholder="Enter phone number">
					<span class="focus-input100"></span>
				</div-->

				<div class="wrap-input100 validate-input" data-validate = "Message is required">
					<span class="label-input100">Message:</span>
					<textarea class="input100" name="message" placeholder="Your Comment..."></textarea>
					<span class="focus-input100"></span>
				</div>

				<div>
				<input name = "image1"type="file" id="actual-btn"/>
				<label for="actual-btn">No file chosen</label>
				
				<input name = "image2"type="file" id="actual-btn"/>
				<label for="actual-btn">No file chosen</label>
				
				<input name = "image3"type="file" id="actual-btn"/>
				<label for="actual-btn">No file chosen</label>
								
				</div>

				<div class="container-contact100-form-btn">
					<button id = "submit" class="contact100-form-btn"  name="submit" type="submit">
						<span>
							<a href="">Submit</a>
							<i class="fa fa-long-arrow-right m-l-7" aria-hidden="true"></i>
						</span>
					</button>
				</div>
			</form>

		</div>
	</div>

  

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src=https://cdn.jsdelivr.net/npm/web3@1.3.6/dist/web3.min.js></script>
    <!--script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js@1.0.0-beta.36/dist/web3.min.js" integrity="sha256-nWBTbvxhJgjslRyuAKJHK+XcZPlCnmIAAMixz6EefVk=" crossorigin="anonymous"></script-->
    <!--script src="https://requirejs.org/docs/release/2.3.5/minified/require.js"></script-->
    <!--script src=”dist/web3.js”></script-->
    <script >
  
	$('#submit').click(function()
	{
     var nftid =0;
   	 nftid = parseInt($('#nfttokenid').val());
     //console.log('nftid should be returned');
     //console.log(nftid);
     sessionStorage.setItem('balayya',nftid);
	   /*web3.eth.getAccounts().then(async function(accounts){
		 var acc = accounts[0];
     console.log(acc);
     var results0;
     var results1;
		 results0 =  mycontract.methods.getAddress(nftid).send({from: acc});
     console.log(results0);
     //console.log(results1);
     sessionStorage.setItem('edfe', results0);
     sessionStorage.setItem('dsaf', results1);
     //localStorage.setItem("dasf", results[2]);
     //localStorage.setItem("dssf", results[0]);
	 }).then(function(tx){
		 console.log(tx);
	 }).catch(function(tx){
         console.log("babu");
         console.log(tx);
	 })*/
	
	});

	</script>




	<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
	
<!--===============================================================================================-->
	<!--script src="vendor/animsition/js/animsition.min.js"></script-->
<!--===============================================================================================-->
	<!--script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script-->
<!--===============================================================================================-->
	<!--script src="vendor/select2/select2.min.js"></script-->
<!--===============================================================================================-->
	<!--script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script-->
<!--===============================================================================================-->
	<!--script src="vendor/countdowntime/countdowntime.js"></script-->
<!--===============================================================================================-->
	<!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKFWBqlKAGCeS1rMVoaNlwyayu0e0YRes"></script>
	<script src="js/map-custom.js"></script-->
<!--===============================================================================================-->
	<script src="js/main.js"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-23581568-13');
	</script>

</body>
</html>

<?php } ?>