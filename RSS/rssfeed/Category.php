<?php
//Category.php
/**
 * Category Class retrieves data info for an individual Category
 * 
 * The constructor an instance of the Category class creates multiple instances of the 
 * Question class and the Answer class to store subCategorys & answers data from the DB.
 *
 * Properties of the Category class like Title, Description and TotalQuestions provide 
 * summary information upon demand.
 * 
 * A Category object (an instance of the Category class) can be created in this manner:
 *
 *<code>
 *$myCategory = new RssFeed\Category(1);
 *</code>
 *
 * In which one is the number of a valid Category in the database. 
 *
 * The forward slash in front of IDB picks up the global namespace, which is required 
 * now that we're here inside the RssFeed namespace: \IDB::conn()

 * @todo none
 */
include '../RSS/rssfeed/Subcategory.php';
class Category
{
     public $CategoryID = 0;
     public $CategoryName = ''; 
     public $CategoryDescription = '';
    //public $SubcategoriesURL = '';
	 
     public $isValid = FALSE;
	 public $TotalFeed = 0; #stores number of subCategorys
	 protected $aSubcategory = Array();#stores an array of subCategory objects
	
	/**
	 * Constructor for Category class. 
	 *
	 * @param integer $id The unique ID number of the Category
	 * @return void 
	 * @todo none
	 */ 
    function __construct($id)
	{#constructor sets stage by adding data to an instance of the object
		$this->CategoryID = (int)$id;
		if($this->CategoryID == 0){return FALSE;}
		
		#get Category data from DB
		$sql = sprintf("select CategoryName, CategoryDescription from categories Where CategoryID =%d",$this->CategoryID);
		
		#in mysqli, connection and query are reversed!  connection comes first
		$result = mysqli_query(\IDB::conn(),$sql) or die(trigger_error(mysqli_error(\IDB::conn()), E_USER_ERROR));
		if (mysqli_num_rows($result) > 0)
		{#Must be a valid Category!
			$this->isValid = TRUE;
			while ($row = mysqli_fetch_assoc($result))
			{#dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
			     $this->CategoryName = dbOut($row['CategoryName']);
			     $this->Description = dbOut($row['CategoryDescription']);
			}
		}
		@mysqli_free_result($result); #free resources
		
		if(!$this->isValid){return;}  #exit, as Category is not valid
		
		#attempt to create subCategory objects
		$sql = sprintf("select s.SubcategoryID, s.SubcategoryName, s.SubcategoryDescription, s.SubcategoryRSS, c.CategoryID from subcategories s join categories c on c.CategoryID = s.CategoryID where s.CategoryID =%d",$this->CategoryID);
		$result = mysqli_query(\IDB::conn(),$sql) or die(trigger_error(mysqli_error(\IDB::conn()), E_USER_ERROR));
		if (mysqli_num_rows($result) > 0)
		{#show results
		   while ($row = mysqli_fetch_assoc($result))
		   {
				#create subCategory, and push onto stack!
				$this->aSubcategory[] = new Subcategory(dbOut($row['SubcategoryID']),dbOut($row['SubcategoryName']),dbOut($row['SubcategoryDescription']), dbOut($row['SubcategoryRSS']),dbOut($row['CategoryID'])); 
		   }
		}
		$this->TotalFeed = count($this->aSubcategory); //the count of the aSubcategory array is the total number of subCategorys
		@mysqli_free_result($result); #free resources
		
		
	}# end Category() constructor
	
	/**
	 * Reveals subCategorys in internal Array of Question Objects 
	 *
	 * @param none
	 * @return string prints data from Question Array 
	 * @todo none
	 */ 
	function showSubcategory()
	{
		$myReturn = '';
        
        if($this->TotalFeed > 0)
		{#be certain there are subCategorys
			foreach($this->aSubcategory as $Subcategory)
			{#print data for each 
                $myReturn .='
                
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">' . $Subcategory->subcategoryName . '</h2>
                        <p>' . $Subcategory->subcategoryDescription . '</p><br/>
                        <p><a href="' . $Subcategory->subcategoryRSS . '">' . $Subcategory->subcategoryRSS . '</a></p>
                    
                    </div>
                    <div class="panel-body">
                        <em> <br />
                    </div>
                </div>

                
                
                
                
                
                ';
                
               
                
                
                /*
                ' . xxx . '
                
                echo $subCategory->SubcategoryID . " ";
				echo $subCategory->Text . " ";
				echo $subCategory->Description . "<br />";
				#call showAnswers() method to display array of Answer objects
				$subCategory->showAnswers() . "<br />";
			*/
            }
		}else{
			echo "There are currently no RSS FEED for this Category.";	
		}
        
        
        return $myReturn;
        
	}# end showSubcategory() method
}# end Category class
?>