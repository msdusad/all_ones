<?
	include("connect.php");
	include("functions.php");
	
	if($_REQUEST['command']=='update'){
		$name=$_REQUEST['name'];
		$email=$_REQUEST['email'];
		$address=$_REQUEST['address'];
		$phone=$_REQUEST['phone'];
		
		$result=mysql_query("insert into customers values('','$name','$email','$address','$phone')");
		$customerid=mysql_insert_id();
		$date=date('Y-m-d');
		$result=mysql_query("insert into orders values('','$date','$customerid')");
		$orderid=mysql_insert_id();
		
		$max=count($_SESSION['cart']);
		for($i=0;$i<$max;$i++){
			$pid=$_SESSION['cart'][$i]['productid'];
			$q=$_SESSION['cart'][$i]['qty'];
			$price=get_price($pid);
			mysql_query("insert into order_detail values ($orderid,$pid,$q,$price)");
		}
		die('Thank You! your order has been placed!');
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Billing Info</title>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="css/groceries.css">         
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
<script src="js/function.js"></script>     
<script language="javascript">
	function validate(){
		var f=document.form1;
		if(f.name.value==''){
			alert('Your name is required');
			f.name.focus();
			return false;
		}
		f.command.value='update';
		f.submit();
	}
</script>
</head>
<body>
<form name="form1" onsubmit="return validate()" class="form-group" role="form">
    <input type="hidden" name="command" class="form-control" />
	<div align="center" class="table-responsive">
        <h2>Billing Information</h2>
        <table class="table-condensed" id="bil">
            <thead>
            <tr><th>Order Total:</th><td><?=get_order_total()?></td></tr>
            <tr><th>Your Name:</th><td><input type="text" name="name" class="form-control" required /></td></tr>
            <tr><th>Address:</th><td><input type="text" name="address" class="form-control" required /></td></tr>
            <tr><th>Email:</th><td><input type="text" name="email" class="form-control" required /></td></tr>
            <tr><th>Phone:</th><td><input type="text" name="phone" class="form-control" required /></td></tr>
            <tr><th>&nbsp;</th><td><input type="submit" value="Place Order" class="btn-success" /></td></tr>    
            </thead>  
        </table>
	</div>
</form>
</body>
</html>
