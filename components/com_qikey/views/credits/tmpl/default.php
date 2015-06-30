<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<!-- <h1><?php echo $this->msg; ?></h1>
<?php echo $this->pagination->getPagesCounter(); ?>
<?php 
?>
<form method = "post" action = "index.php?option=com_qikey&view=credits&task=filterByDate">
<label>From:</label><input type="date" name= "fromDate" max=" <?php //echo $this->currentDate?> "/>
<label>To:</label><input type="date" name="toDate" max=" <?php //echo $this->currentDate?> "/>
<input type = "submit"/>
</form>
<?php
/*echo "<pre>";
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
echo "</pre>";*/
?>
<div>

<?php 
/*echo "<pre>";
var_dump($this->pagination->getPagesLinks());
echo "</pre>";*/
?>
</div>

<form method = "post" action = "index.php?option=com_qikey&view=credits&task=filterByDate">
<label>From:</label><input type="date" name= "fromDate" max=" <?php //echo $this->currentDate?> "/>
<label>To:</label><input type="date" name="toDate" max=" <?php //echo $this->currentDate?> "/>
<input type = "submit"/>
</form> -->
<?php //var_dump($this->filterForm); ?>
<?php //echo "<pre>";?>
<?php //var_dump($this->items); ?>
<?php //echo "</pre>";?>
<?php //echo $this->pagination->getPagesCounter(); ?>
<h3>TEST</h3>
<?php if( count( $this->items )) : ?>
<div class="credits row-fluid">
	<ul class="thumbnails">
    <?php foreach( $this->items as $item ) : ?>
        <li>
        	<?php echo $item->activity . ": " . $item->lesson; ?><br />
        	<?php echo strip_tags($item->description)?><br />
        	<?php echo "Credits Earned: " . $item->credit?>
        </li>
    <?php endforeach; ?>
   	</ul>
</div>
<?php endif;?>
<div id="creditPagination">
<?php echo $this->pagination->getPagesLinks(); ?>
</div>