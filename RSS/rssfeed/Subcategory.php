<?php
//subcategory rss


class Subcategory 
{
    public $subcategoryID = 0;
    public $subcategoryName = '';
    public $subcategoryDescription = '';
    public $subcategoryRSS = '';
    public $categoryID = 0;
  //  public $subcategoryImage = '';

    //constructor
    public function __construct($subcatID, $subcatName, $subcatDescription, $subcatRSS, $catID) {
        $this->subcategoryID = (int) $subcatID;
        $this->subcategoryName = $subcatName;
        $this->subcategoryDescription = $subcatDescription;
        $this->subcategoryRSS = $subcatRSS;
        $this->categoryID = (int) $catID;
     //   $this->setImages($this->subcategoryName);
    }//end of constructor

    
    
    
    
    

}//end of subcategory class