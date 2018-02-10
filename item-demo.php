<?php
/**
 * item-demo.php, based on demo_postback_nohtml.php is a single page web application that allows us to 
 * select a quantity of items and extras and returns a total 
 *
 * @package WN18
 * @author Michael Nomura <mnnomura@gmail.com>
 * @version 0.9
 * @link http://michaelnomura.dreamhosters.com
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @todo finish instruction sheet
 * @todo add more complicated checkbox & radio button examples
 */

# '../' works for a sub-folder.  use './' for the root  
require 'config_inc.php'; #provides configuration, pathing, error handling, db credentials
include 'items.php'; 
/*
$config->metaDescription = 'Web Database ITC281 class website.'; #Fills <meta> tags.
$config->metaKeywords = 'SCCC,Seattle Central,ITC281,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/

//END CONFIG AREA ----------------------------------------------------------

# Read the value of 'action' whether it is passed via $_POST or $_GET with $_REQUEST
if(isset($_REQUEST['act'])){$myAction = (trim($_REQUEST['act']));}else{$myAction = "";}

switch ($myAction) 
{//check 'act' for type of process
	case "display": # 2)Display total
	 	showData();
	 	break;
	default: # 1)Ask user to enter their order
	 	showForm();
}

function showForm()
{// shows form so user can enter their order.  Initial scenario
	global $config;
    get_header(); #defaults to header_inc.php	
	
	//form
    echo 
	'<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
		function checkForm(thisForm)
		{//check form data for valid info
			if(empty(thisForm.YourName,"Please Enter Your Order")){return false;}
			return true;//if all is passed, submit!
		}
	</script>
	<h3 align="center">' . smartTitle() . '</h3>
	<p align="center">Menu</p> 
	<form action="' . THIS_PAGE . '" method="post" onsubmit="return checkForm(this);">
             ';
  
    
		foreach($config->items as $item)
        {//loop through items from items.php
            
            //diplays item info and input for amount
            echo '<p>' . $item->Name . " $" . $item->Price .' <input type="number" name="item_' . $item->ID . '" /></p>';
            
            
            foreach ($item->Extras as $extra)
            {//loop through extras and display check boxes
                
                //checkboxes
                echo '<p>' . $extra . ' ' . '$0.25 ' . '<input type="checkbox" name="ex@' . $item->ID . '@' . $extra . '" /></p>';
            }//end foreach ($item->Extras as $extra)
     
        }//end foreach($config->items as $item)       
 
    
    echo '
    <p><input type="submit" value="Submit"></p>
    <input type="hidden" name="act" value="display" />
	</form>
	';
	get_footer(); #defaults to footer_inc.php

}//end function


function getItem($id,$ar)
{//returns item by id
    
    foreach($ar as $item)
    {//loop through items in file
        
        if ($id == $item->ID)
        {//check if id matches
            
            //return item info
            return $item;
            
        }//end if statement
    }//end for loop
}//end function


function showData()
{//form submits here we show entered name
    
    //connect to config
    global $config;
	
    get_header(); #defaults to footer_inc.php
    
    //title on top of page
	echo '<h3 align="center">Menu</h3>';
    
    //initate total variable
    $total = 0.0;
    
    $itemOrder = [];
	
	foreach($_POST as $name => $value)
    {//loop the form elements
        
        //if form name attribute starts with 'item_', process it
        if(substr($name,0,5)=='item_')
        {
            //explode the string into an array on the "_"
            $name_array = explode('_',$name);

            //id is the second element of the array
			//forcibly cast to an int in the process           
            $id = (int)$name_array[1];
            
            //can extras be added to string?
            
            if($value > 0)
                {//check for items
                
                //use getItem function to return item data
                $item = getItem($id,$config->items);
                
                //mutiply price by number of items
                $priceMulti = (float)$item->Price * $value;
                echo "<p>You ordered " . $value . " " . $item->Name . "(s) $" . number_format($priceMulti,2) . ' ' .$item->Description . "</p>";
                
                //make array of itemIDs of itmes that have been ordered
                //maybe a stringof itemID and amount ordered
                $itemOrders[] = $id . '_' . $value;
                
                $total += $priceMulti;
                }//end if statement

        //if entry is a extra
        }else if(substr($name,0,3)=='ex@')
            {
                  
            //split array on '@'            
            $ex_array = explode('@',$name);
            
            //add to total
            //$total += $value * 0.25;
            
            $itemID = $ex_array[1];
            
            //loop through itemOrder
            foreach ($itemOrders as $itemOrder)
            {//loop through items that have been ordered
                
                //split data
                $itemInfo = explode('_',$itemOrder);
                
                //check if id matches
                if($itemID == $itemInfo[0])
                {//check if item has been ordered
                    
                    //mutiply .25 by amount of item ordered
                    $extraTotal = $itemInfo[1] * 0.25;
                    
                    $total += $extraTotal;
                
                }//end if statment
                
            }//end for each loop
            //if itemID matches extraID (need to add something to tag extraID)
            //mutiply price of extra by value
            
            //displays name of extra
            $ex = $ex_array[2];
            
            echo "<p>Add $extraTotal $ex</p>";
            //echo "<p>$itemID</p>";
            }
        
	
    }//end of for each
    
    //add tax
    $total = $total * 1.101;
    
    //show total
    echo "Total: $" . number_format($total,2);
    
	echo '<p align="center"><a href="' . THIS_PAGE . '">RESET</a></p>';
	get_footer(); #defaults to footer_inc.php
    
}
?>