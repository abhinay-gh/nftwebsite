<!--DOCTYPE html-->
<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['adlogin'])==0)
	{	
echo "<script type='text/javascript'> alert('Please Login as admin First!'); </script>";
header('location: loginpage/usermanagement/admin/index.php');
}

else{

   if(isset($_POST['submit'])){	




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
					<span class="label-input100">TokenId:</span>
					<input name = "tokenid" class="input100" type="text" id="tokenid" placeholder=".....">
					<span class="focus-input100"></span>
				</div>
				<div class="wrap-input100 validate-input" data-validate="">
					<span class="label-input100"> NFT Name</span>
					<input name = "nname"class="input100" type="text" id="nname" placeholder=".....">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input" data-validate="">
					<span class="label-input100"> URI  </span>
					<input name = "uri"class="input100" type="text" id="uri" placeholder=".....">
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

				<!--div class="wrap-input100 validate-input" data-validate = "Message is required">
					<span class="label-input100">Message:</span>
					<textarea class="input100" name="message" placeholder="Your Comment..."></textarea>
					<span class="focus-input100"></span>
				</div-->

				<!--div>
				<input name = "image1"type="file" id="actual-btn"/>
				<label for="actual-btn">No file chosen</label>
				
								
				</div-->

				<!--div class="container-contact100-form-btn">
					<button id = "submit" class="contact100-form-btn" name="submit" type="submit">
						<span>
							<a href="">Submit</a>
							<i class="fa fa-long-arrow-right m-l-7" aria-hidden="true"></i>
						</span>
					</button>
				</div-->
			</form>

		</div>
	</div>

  

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src=https://cdn.jsdelivr.net/npm/web3@1.3.6/dist/web3.min.js></script>

    <!--script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js@1.0.0-beta.36/dist/web3.min.js" integrity="sha256-nWBTbvxhJgjslRyuAKJHK+XcZPlCnmIAAMixz6EefVk=" crossorigin="anonymous"></script-->
    <!--script src="https://requirejs.org/docs/release/2.3.5/minified/require.js"></script-->
    <!--script src=”dist/web3.js”></script-->
    <script>
     async function CheckMetamaskConnection(){
       if(window.ethereum){
         window.web3 = new Web3(window.ethereum);
         try {
           await ethereum.enable();
           return true;
         } catch(error){
           return false;
         }
        }
        else if (window.web3) {
          window.web3 = new Web3(web3.currentProvider);
          return true;
        }
        else {
          console.log('non-eth browser detected use metamask');
          return false;
        }
     }

     var contaddress = "0x159B8D44fFe2925Ca6EBe581331DAdFB88Da9294";
		
	 var abi = [
	{
		"inputs": [],
		"stateMutability": "nonpayable",
		"type": "constructor"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"internalType": "address",
				"name": "_owner",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "address",
				"name": "_approved",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "Approval",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"internalType": "address",
				"name": "_owner",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "address",
				"name": "_operator",
				"type": "address"
			},
			{
				"indexed": false,
				"internalType": "bool",
				"name": "_approved",
				"type": "bool"
			}
		],
		"name": "ApprovalForAll",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"internalType": "address",
				"name": "previousOwner",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "address",
				"name": "newOwner",
				"type": "address"
			}
		],
		"name": "OwnershipTransferred",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"internalType": "address",
				"name": "_from",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "address",
				"name": "_to",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "Transfer",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": false,
				"internalType": "address",
				"name": "owneraddress",
				"type": "address"
			}
		],
		"name": "eventaddress",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": false,
				"internalType": "string",
				"name": "nftname",
				"type": "string"
			},
			{
				"indexed": false,
				"internalType": "address",
				"name": "owneraddress",
				"type": "address"
			},
			{
				"indexed": false,
				"internalType": "uint256",
				"name": "blocknumber",
				"type": "uint256"
			},
			{
				"indexed": false,
				"internalType": "uint256",
				"name": "blocktimestamp",
				"type": "uint256"
			},
			{
				"indexed": false,
				"internalType": "string",
				"name": "ipfsurl",
				"type": "string"
			}
		],
		"name": "eventdetails",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": false,
				"internalType": "uint256",
				"name": "length",
				"type": "uint256"
			}
		],
		"name": "eventlength",
		"type": "event"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_approved",
				"type": "address"
			},
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "approve",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_owner",
				"type": "address"
			}
		],
		"name": "balanceOf",
		"outputs": [
			{
				"internalType": "uint256",
				"name": "",
				"type": "uint256"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "getAddress",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "getApproved",
		"outputs": [
			{
				"internalType": "address",
				"name": "",
				"type": "address"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "getDetails",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [],
		"name": "getLength",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_owner",
				"type": "address"
			},
			{
				"internalType": "address",
				"name": "_operator",
				"type": "address"
			}
		],
		"name": "isApprovedForAll",
		"outputs": [
			{
				"internalType": "bool",
				"name": "",
				"type": "bool"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "string",
				"name": "_name",
				"type": "string"
			},
			{
				"internalType": "address",
				"name": "_to",
				"type": "address"
			},
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			},
			{
				"internalType": "string",
				"name": "_uri",
				"type": "string"
			}
		],
		"name": "mint",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [],
		"name": "name",
		"outputs": [
			{
				"internalType": "string",
				"name": "_name",
				"type": "string"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [],
		"name": "owner",
		"outputs": [
			{
				"internalType": "address",
				"name": "",
				"type": "address"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "ownerOf",
		"outputs": [
			{
				"internalType": "address",
				"name": "_owner",
				"type": "address"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [],
		"name": "renounceOwnership",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_from",
				"type": "address"
			},
			{
				"internalType": "address",
				"name": "_to",
				"type": "address"
			},
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "safeTransferFrom",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_from",
				"type": "address"
			},
			{
				"internalType": "address",
				"name": "_to",
				"type": "address"
			},
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			},
			{
				"internalType": "bytes",
				"name": "_data",
				"type": "bytes"
			}
		],
		"name": "safeTransferFrom",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_operator",
				"type": "address"
			},
			{
				"internalType": "bool",
				"name": "_approved",
				"type": "bool"
			}
		],
		"name": "setApprovalForAll",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "bytes4",
				"name": "_interfaceID",
				"type": "bytes4"
			}
		],
		"name": "supportsInterface",
		"outputs": [
			{
				"internalType": "bool",
				"name": "",
				"type": "bool"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [],
		"name": "symbol",
		"outputs": [
			{
				"internalType": "string",
				"name": "_symbol",
				"type": "string"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "tokenURI",
		"outputs": [
			{
				"internalType": "string",
				"name": "",
				"type": "string"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_from",
				"type": "address"
			},
			{
				"internalType": "address",
				"name": "_to",
				"type": "address"
			},
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "transferFrom",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "newOwner",
				"type": "address"
			}
		],
		"name": "transferOwnership",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	}
];
	var mycontract;
   
    $(document).ready(async function(){

	var IsMetamask = await CheckMetamaskConnection();
    if(IsMetamask){
			
      mycontract = new web3.eth.Contract(abi,contaddress);
      console.log("mycontract:", mycontract);
      console.log('metamask detected');
    }
    else{
      console.log('metamask not detected');
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'metamask not detected',
        onClose(){
          location.reload();
        }
      });
    }
	

	web3.eth.getAccounts().then(async function(accounts){
	 var acc = accounts[0];
     //console.log(acc);

	 /*var bruh1 = await mycontract.methods.getAddress(1).send({from: acc});
	  var eventlength = bruh1.events.eventaddress.returnValues.owneraddress; 
	  console.log(bruh1);
	  console.log(eventlength);
	 
*/
	  //var ag1 = parseInt($('#tokenid').val());
      //var ag2 = $('#nname').val();
      //var ag3 = $('#uri').val();
    var rg1 = sessionStorage.getItem('balayya1');
    var rg2 = sessionStorage.getItem('balayya2');
    var rg3 = sessionStorage.getItem('balayya3');
      
        
      console.log(rg1);
      console.log(rg2);

	  var det = await mycontract.methods.mint(rg2,acc,rg1,rg3).send({from: acc});
	  console.log("det:"+ det);
	  console.log("minting complete");
	  console.log(rg1);
	  //console.log(12);
	  console.log(rg2);


	 }).catch(function(tx){
         console.log("babu");
         console.log(tx);
	 })

	   
	 
	
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