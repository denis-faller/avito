<?

Class Avito{
    
    private $iblockId;
    private $url;
    private $quantity_allow;
    private $subcategoryProduct;
    private $adType;
    private $contactPhone;
    private $address;
    private $morePhotoPropCode;
    private $oemPropCode;
    private $conditionPropCode;
    private $cml2articlePropCode;
    
    public function __construct($iblockId, $url, $quantity_allow, $subcategoryProduct, $adType, 
            $contactPhone, 
            $address, 
            $morePhotoPropCode,
            $oemPropCode,
            $conditionPropCode,
            $cm2articlePropCode){
        $this->iblockId = $iblockId;
        $this->url = $url;
        $this->quantity_allow = $quantity_allow;
        $this->subcategoryProduct = $subcategoryProduct;
        $this->adType = $adType;
        $this->contactPhone = $contactPhone;
        $this->address = $address;
        $this->morePhotoPropCode = $morePhotoPropCode;
        $this->oemPropCode = $oemPropCode;
        $this->conditionPropCode = $conditionPropCode;
        $this->cml2articlePropCode = $cm2articlePropCode;
    }
    
    public function getSectionsIDs(){
        $dbSect = CIBlockSection::GetList(array("ID"=>"asc"), array("IBLOCK_ID"=>$this->iblockId, "ACTIVE"=>"Y"), false, array("ID", "LEFT_MARGIN","RIGHT_MARGIN", "DEPTH_LEVEL"));
        while($resSect = $dbSect->GetNext()){
            $sectionsIDs[] = $resSect["ID"];

            $dbSectNested = CIBlockSection::GetList(array("ID"=>"asc"), array("IBLOCK_ID"=>$this->iblockId, ">LEFT_MARGIN"=>$resSect["LEFT_MARGIN"], "<RIGHT_MARGIN"=>$resSect["RIGHT_MARGIN"], ">DEPTH_LEVEL"=>$resSect["DEPTH_LEVEL"], "ACTIVE"=>"Y"));

            while($resSectNested = $dbSectNested->GetNext()){
                $sectionsIDs[] = $resSectNested["ID"];
            }
        }
        if(isset($sectionsIDs)){
            $sectionsIDs = array_unique($sectionsIDs);
        }
        
        return $sectionsIDs;
    }
    
    public function getProducts($sectionsIDs){

        $arrFilter = array("IBLOCK_ID"=>$this->iblockId, "IBLOCK_SECTION_ID"=>$sectionsIDs);
        
        $dbRes = CIBlockElement::GetList(array(), $arrFilter, false, false, array());
        
        $products = [];
        
        $addition = file_get_contents("addition.php");

        while($obRes = $dbRes->GetNextElement()){
            $arRes = $obRes->GetFields();
            
            $products[$arRes["ID"]]["name"] = $arRes["NAME"];
            $products[$arRes["ID"]]["category"] = "Запчасти и аксессуары";
            $products[$arRes["ID"]]["type_id"] = $this->subcategoryProduct;
            $products[$arRes["ID"]]["ad_type"] = $this->adType;
            $products[$arRes["ID"]]["contact_phone"] = $this->contactPhone;
            $products[$arRes["ID"]]["adress"] = $this->address;
            $products[$arRes["ID"]]["img"] = CFile::GetPath($arRes["DETAIL_PICTURE"]);
            $products[$arRes["ID"]]["img"] = $this->url.$products[$arRes["ID"]]["img"];
            $products[$arRes["ID"]]["price"] = CPrice::GetBasePrice($arRes["ID"]);
            $products[$arRes["ID"]]["price"] = intval($products[$arRes["ID"]]["price"]["PRICE"]);
            $products[$arRes["ID"]]["quantity"] = CCatalogProduct::GetByID($arRes["ID"]);
            $products[$arRes["ID"]]["quantity"] = $products[$arRes["ID"]]["quantity"]["QUANTITY"];
            
            $props = $obRes->GetProperties();
            
           if(isset($props[$this->morePhotoPropCode]["VALUE"])){
                foreach($props[$this->morePhotoPropCode]["VALUE"] as $morePhotoID){
                    $products[$arRes["ID"]]["photo"][$morePhotoID] = CFile::GetPath($morePhotoID);
                    $products[$arRes["ID"]]["photo"][$morePhotoID] = $this->url.$products[$arRes["ID"]]["photo"][$morePhotoID];
                }
            }
            if(isset($props[$this->oemPropCode]["VALUE"])){
                $products[$arRes["ID"]]["part_number"] = $props[$this->oemPropCode]["VALUE"];
            }
            if(isset($props[$this->conditionPropCode]["VALUE"])){
                $products[$arRes["ID"]]["condition"] = $props[$this->conditionPropCode]["VALUE"];
            }
            if(isset($props[$this->cml2articlePropCode]["VALUE"])){
                $products[$arRes["ID"]]["description"] = "<p>Артикул: ".$props[$this->cml2articlePropCode]["VALUE"]."</p><p>".$arRes["DETAIL_TEXT"]."</p>".$addition;
            }
            else{
                $products[$arRes["ID"]]["description"] = "<p>".$arRes["DETAIL_TEXT"]."</p>".$addition;
            }
        }

        return $products;
    }

    public function getSimpleXmlElement($products){
        $xmlstr = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Ads formatVersion=\"3\" target=\"Avito.ru\"></Ads>";
        $sxe = new SimpleXMLElement($xmlstr);
        
        $currency = CCurrency::GetBaseCurrency();

        foreach($products as $key=>$value){
            if($this->quantity_allow && ($value["quantity"] <= 0)){
                continue;
            }
            else{
                $ad = $sxe->addChild('Ad');
                $id = $ad->addChild("Id", $key);
                $category = $ad->addChild("Category", $value["category"]);
                $typeID = $ad->addChild("TypeId", $value["type_id"]);
                $adType = $ad->addChild("AdType", $value["ad_type"]);
                $contactPhone = $ad->addChild("ContactPhone", $value["contact_phone"]);
                $adress = $ad->addChild("Address", $value["adress"]);
                $title = $ad->addChild("Title", htmlspecialchars($value["name"]));
                $oem = $ad->addChild("OEM", $value["part_number"]);
                $condition = $ad->addChild("Condition", $value["condition"]);
                $desc = $ad->addChild("Description");
                
                $node = dom_import_simplexml($desc);
                $no = $node->ownerDocument; 
                $node->appendChild($no->createCDATASection($value["description"])); 
                
                $price = $ad->addChild("Price", $value["price"]);
                $images = $ad->addChild("Images");
                if($value["img"] != $this->url){
                    $img = $images->addChild("Image");
                    $img->addAttribute("url", $value["img"]);
                }
                foreach($value["photo"] as $keyPhoto=>$photo){
                    if($photo != $this->url){
                        $img2 = $images->addChild("Image");
                        $img2->addAttribute("url", $photo);
                    }
                }
            }
        }
        return $sxe;
    }
}

?>