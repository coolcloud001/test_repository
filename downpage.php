<?php
ob_start();
session_start();
include("bunchdbconn.php");
include("timediff.php");
$admin = '';
$category = 'ALL';
if(isset($_GET['category']) && $_GET['category'] != 'ALL' && is_numeric($_GET['category'])){
			$category =  $_GET['category'];
}

if(isset($_SESSION['admin']) && $_SESSION['admin'] == 'apden@admin')
{
	$admin = $_SESSION['admin'];
}
$type = '';
if(isset($_GET['type']))
 $type = $_GET['type'];
 
 
	   if($type  && $admin != ''){
	   		$where = "WHERE lnk_status = '0' ";
	   } else {		
			$where = "WHERE lnk_status = '1' ";
	   }
		if($category > 0 && $category != 'ALL'){
			$where .= ' AND category ='.$category;
		}
		
		if(isset($_GET['str'])) {
			$where .= " AND title like '%".$_GET['str']."%'";
		}
		$where .= 	" and lnkid < {$_GET['linkid']} ";
		$where .= 	" ORDER BY lnkid DESC ";
		$where .= 	" Limit 20 ";
			
			
	$select = "SELECT * FROM site_links $where ";
	$rsid = mysql_query($select,$conn);
	$rows = mysql_num_rows($rsid);			
	?>
	<?php
			if($rows > 0) 
			{
				while($row = mysql_fetch_object($rsid))
				{ 
					preg_match("/^(http:\/\/)?([^\/]+)/i",$row->url, $matches);
					$host = $matches[2]; 
					preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
					$url_title = str_replace(" ","-",trim(stripslashes($row->title)));
					?>
<div class="box" id='<?php echo $row->lnkid?>'>
<div class="thum"><a href="<?=$baseurl?>/details/<?php echo $row->lnkid?>/<?php echo $row->category?>/<?php echo $url_title?>"><img src="<?=$baseurl?>/<?php echo $row->image;?>" width="80"/></a></div>
<div class="thum_right">
<div class="descri">
<h3><a href="<?=$baseurl?>/details/<?php echo $row->lnkid?>/<?php echo $row->category?>/<?php echo $url_title?>"><?php echo $row->title;?></a></h3>
<?php if($admin != ''){?>
<a href="javascript:void(0);" onClick="doDelete('<?php echo $row->lnkid?>')"><img src="<?=$imgurl?>/delete.png" /></a>
<?php }?>
<p><?php echo substr($row->description,0,120);?>....</p>
<div class="link"><a href="<?=$baseurl?>/views.php?url=<?php echo $row->url?>&id=<?php echo $row->lnkid?>" rel="nofollw" target="_blank"><?php echo $row->domain;?></a>&nbsp;&nbsp;&nbsp; <?php //echo $time = get_time_to_days_ago("$row->add_date")?> &nbsp;&nbsp;&nbsp;<?php if($clicks>0)echo $clicks;else echo 0;?> views</div>
</div>
<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
<?php
					
					$LastLinkID = $row->lnkid;
		  		}
				
			}
		?>