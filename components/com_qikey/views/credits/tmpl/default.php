<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<h1><?php echo $this->msg; ?></h1>
<?php echo $this->pagination->getPagesCounter(); ?>
<?php 
?>
<form method = "post" action = "index.php?option=com_qikey&view=credits&task=filterByDate">
<label>From:</label><input type="date" name= "fromDate" max=" <?php echo $this->currentDate?> "/>
<label>To:</label><input type="date" name="toDate" max=" <?php echo $this->currentDate?> "/>
<input type = "submit"/>
</form>
<?php
echo "<pre>";
var_dump($this->currentDate); 
echo "</pre>";
echo "<pre>";
var_dump($this->profile); 
echo "</pre>";
echo "<pre>";
var_dump($this->completedLessons); 
echo "</pre>";
echo "<pre>";
var_dump($this->filteredLessons); 
echo "</pre>";
?>
<div>

<?php 
echo "<pre>";
var_dump($this->pagination->getPagesLinks());
echo "</pre>";
?>
</div>

<!-- <form method = "post" action = "index.php?option=com_qikey&view=credits&task=filterByDate">
<label>From:</label><input type="date" name= "fromDate" max=" <?php //echo $this->currentDate?> "/>
<label>To:</label><input type="date" name="toDate" max=" <?php //echo $this->currentDate?> "/>
<input type = "submit"/>
</form> -->
<?php //var_dump($this->filterForm); ?>
<?php //echo "<pre>";?>
<?php //var_dump($this->items); ?>
<?php //echo "</pre>";?>
<?php echo $this->pagination->getPagesCounter(); ?>
<!-- <h3>TEST</h3>

<form method = "post" action = "index.php?option=com_qikey&view=credits&task=filterByDate">
<label>From:</label><input type="date" name= "fromDate" max=" <?php //echo $this->currentDate?> "/>
<label>To:</label><input type="date" name="toDate" max=" <?php //echo $this->currentDate?> "/>
<input type = "submit"/>
</form>

<?php //if( //count( $this->items )) : ?>
<div class="credits row-fluid">
	<ul class="thumbnails">
    <?php //foreach( $this->items as $item ) : ?>
        <li>
        	<?php //echo $item->activity . ": " . $item->lesson; ?><br />
        	<?php //echo strip_tags($item->description)?><br />
        	<?php //echo "Credits Earned: " . $item->credit?>
        </li>
    <?php //endforeach; ?>
   	</ul>
</div>
<?php //endif;?>
<div id="creditPagination">
<?php //echo $this->pagination->getPagesLinks(); ?>
</div>-->

<?php
	$isModal = JRequest::getVar( 'print' ) == 1; // 'print=1' will only be present in the url of the modal window, not in the presentation of the page
	if( $isModal) {
		$href = '"#" onclick="window.print(); return false;"';
	} else {
		$href = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
		$href = "window.open(this.href,'win2','".$href."'); return false;";
		$href = '"index.php?option=com_qikey&view=credits&tmpl=component&print=1&Itemid=143" '.$href;
	}
?>

<a href=<?php echo $href; ?> >Click for Printing</a> 

<?php
$user = JFactory::getUser();
if ($user->guest)
{
    return '<a href="index.php?option=com_users&view=login">Login</a>';
}
else
{
    $userToken = JSession::getFormToken();
    echo '<a href="index.php?option=com_users&task=user.logout&' . $userToken . '=1">Logout '  . $user->username . '</a>.';
}
?>