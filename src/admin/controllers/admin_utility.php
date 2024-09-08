<?php
require_once("../../controllers/utility.php");

class admin_utility extends utility
{
  public function uploadedAsset($file)
  {
    $this->specialChars('../../../public/uploads/' . $file);
  }

  public function staticAssets($file)
  {
    echo '../../../assets/' . $file;
  }

  // public function svgAssets($file)
  // {
  //   echo '../../../assets/svg/' . $file . '.svg';
  // }

  // public function productImage($file)
  // {
  //   $this->uploadedAsset("product_images/" . $file);
  // }

  // public function categoryPicture($type, $file)
  // {
  //   if ($type == "base") {
  //     $this->uploadedAsset("category_images/base/" . $file);
  //   } elseif ($type == "hover") {
  //     $this->uploadedAsset("category_images/hover/" . $file);
  //   }
  // }

  public function paymentProof($file)
  {
    $this->uploadedAsset("user_payment/" . $file);
  }

}