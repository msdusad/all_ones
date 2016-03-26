<?php 
include('connect.php');
include('functions.php');
if($_REQUEST['command']=='add' && $_REQUEST['productid']>0){
		$pid=$_REQUEST['productid'];
		addtocart($pid,1);
		header("location:groceries.php");
		exit();
	}
?>
<html>
    <head>
<title>Groceries</title>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="css/groceries.css">         
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>      
<script src="js/jquery.carouFredSel-6.1.0-packed.js"></script>
<script src="js/tms-0.4.1.js"></script>        
<script src="js/function.js"></script>        
<script type="text/javascript">
    
    function addtocart(pid)
    {
		document.form1.productid.value=pid;
		document.form1.command.value='add';
		document.form1.submit();
	}
    
///filter product according country state city ////////
   $('.act a').click(function(e) {
    e.preventDefault(); 
    $('.act a').removeClass('selected');
    var show=$(this).addClass('selected');
       alert(show);
});
function country_data(country){
    cat_val=$('ul#ajax_cat_val').find('li.active').data('interest');
    subcat_val=$('ul#ajax_subcat_val').find('li.active').data('interest');
    alert(cat_val);
    //alert(subcat_val);
    alert(country);
        $.ajax({
          type: "POST",
          data: { cat_val:cat_val,subcat_val:subcat_val,country:country},
          url: "get_data.php",
          success: function(data) 
          {  
              $("#pro_items").html(data);
          }
        });
      }
function state_data(country,state){
    cat_val=$('ul#ajax_cat_val').find('li.active').data('interest');
    subcat_val=$('ul#ajax_subcat_val').find('li.active').data('interest');
        $.ajax({
          type: "POST",
          data: { cat_val:cat_val,subcat_val:subcat_val,country_id:country,state_id:state},
          url: "get_data.php",
          success: function(data) 
          { 
            $("#pro_items").html(data);
          }
        });
      }
function city_data(country,state,city){
    cat_val=$('ul#ajax_cat_val').find('li.active').data('interest');
    subcat_val=$('ul#ajax_subcat_val').find('li.active').data('interest');
        $.ajax({
          type: "POST",
          data: { cat_val:cat_val,subcat_val:subcat_val,country_ids:country,state_ids:state,city_ids:city},
          url: "get_data.php",
          success: function(data) 
          { 
            $("#pro_items").html(data);
          }
        });
      }    
</script>
</head>
<body>
<div class="container">
    <div class="header">
                <form name="form1" class="form-group">
	                <input type="hidden" name="productid" />
                    <input type="hidden" name="command" />
                </form>

<?php 
    $query=mysql_query("select * from categorys");
       if($query && mysql_num_rows($query)>0)
        { 
?>
<div class="navcontainer">        
<ul id="ajax_cat_val"> 
        <?php
          while($cat=mysql_fetch_array($query)){
          $id_val=$cat['c_id'];
        ?>
        <li  class="active" data-interest="<?php echo $id_val; ?>">
            <a href="#" id="cat_val" onclick="showData('<?php echo $id_val; ?>')">
                <?php echo $cat['c_name'];?>
            </a>
        </li>
    <?php } ?>
           
        <li>
            <?php
			 if(is_array($_SESSION['cart'])){
				$max=count($_SESSION['cart']);
				for($i=0;$i<$max;$i++){
					$pid=$_SESSION['cart'][$i]['productid'];
					$q=$_SESSION['cart'][$i]['qty'];
					if($q==0) continue;		
                } } ?>
            <a href="shoppingcart.php" class="cart" title="View Cart"><?=$i++;?></a>
        </li>   
</ul>
</div>
   <?php } ?>
   
    
                        
<?php 
    $rs=mysql_query("select * from sub_category where c_id='1'");
    if($rs && mysql_num_rows($rs)>0)
    {
?>
   <div class="submenu" id="hide">
        <ul id="ajax_subcat_val" class="act">
        <?php
            while($data = mysql_fetch_array($rs)){
            $id_val= $data['c_id'];
            $id_val1= $data['sc_id'];
        ?> 
            <li class="active" data-interest="<?php echo $id_val1; ?>">
                <a  href="#" onclick="subcat('<?php echo $id_val; ?>','<?php echo $id_val1; ?>')" id="subcat_val" class="activate">
                    <?php echo $data['sc_name'];?>
                </a>
            </li>
        <?php } ?>
        </ul>
    </div>
<?php } ?>
                
            <div class="filter" style="position:absolute;">
                    <select class="form-control" id="country" name ="country" onchange="country_data(this.value)">
                        <option select="selected">Select Country</option> 
                        <?php foreach($country as $countrys){ ?>
                        <option value='<?php echo $countrys['country_id'] ;?>'><?php echo $countrys['country_name'] ;?></option>
                        <?php }?>
                    </select>
                    <select class="form-control" name="state" id="state" onchange="state_data(country.value,this.value)">
                        <option select="selected">Select State</option>
                    </select>
                    <select class="form-control" name="city" id="city" onchange="city_data(country.value,state.value,this.value)">
                        <option select="selected">Select City</option>
                    </select>
            </div>
    </div>    
<div class="content-main">
    
<div class="container_12">
<div class="grid_12">
<a href="#" class="prev"></a><a href="#" class="next"></a>
    <h3 id="top-hed">Grocery Products</h3>
<div class="car_wrap">
    <ul class="carousel1">  
        <?php
            $pro=mysql_query("select * from products");
            while($items=mysql_fetch_array($pro)){
            echo '<li>';    
            echo '<div id="img"><img src="data:image/jpeg;base64,'.base64_encode( $items['p_image'] ).'"></div>';
            echo '<div class="name"><h3>'.$items['p_name'].'</h3></div>';
            echo'<div class="price"><h3>'.'Price : '.'&#8377; '.$items['p_price'].'</h3></div>';
            echo '<div class="desc">';?>
            <input type="submit" value="Buy Now" class="btn" onclick="addtocart(<?=$items['p_id']?>)">
            <?php echo '</div></li>';
        } ?>
    </ul>
</div>
</div>
</div> 
<hr>
    <div class="grid_view" id="pro_items">
            <?php
            $pro=mysql_query("select * from products");
            while($items=mysql_fetch_array($pro)){
                echo '<div class="col-sm-2" id="data">';
                echo '<div id="img"><img src="data:image/jpeg;base64,'.base64_encode( $items['p_image'] ).'"></div>';
                echo '<div class="name"><h3>'.$items['p_name'].'</h3></div>';
                echo'<div class="price"><h3>'.'Price : '.'&#8377; '.$items['p_price'].'</h3></div>';
                echo '<div class="desc">';?>
                <input type="submit" value="Buy Now" class="btn" onclick="addtocart(<?=$items['p_id']?>)">
                <?php echo '</div></div>';
            } ?>
        </div>
    </div>
            <div class="footer">
                <div class="adds">
                    <div class="row">
                        <?php
                        $spon=mysql_query("select * from sponcer");
                        while($new=mysql_fetch_array($spon)){
                        ?>
                        <div class="col-sm-2" id="add_box">
        <?php  echo '<a href="'.( $new['link'] ).'" target="_blank"/>';    
        echo '<img src="data:image/jpeg;base64,'.base64_encode( $new['image'] ).'" class="image-responsive">'; 
        echo "</a>"; }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
