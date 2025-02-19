<?php
class utility
{
  public function __construct()
  {
  }

  public function trimFilter($param)
  {
    return trim(filter_var($param, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  }

  public function specialChars($param)
  {
    echo htmlspecialchars($param);
  }

  public function showError($error_name)
  {
    if (isset($_SESSION[$error_name])) {
      echo $_SESSION[$error_name];
      unset($_SESSION[$error_name]);
    }
  }

  public function staticAssets($file)
  {
    $this->specialChars('../../assets/' . $file);
  }

  public function svgAssets($file)
  {
    $this->staticAssets('svg/' . $file . '.svg');
  }

  public function imgAssets($file)
  {
    $this->staticAssets('img/' . $file);
  }

  public function uploadedAsset($file)
  {
    $this->specialChars('../../public/uploads/' . $file);
  }

  public function profilePicture($file)
  {
    $this->uploadedAsset("user_pp/" . $file);
  }

  public function categoryPicture($type, $file)
  {
    if ($type == "base") {
      $this->uploadedAsset("category_images/base/" . $file);
    } elseif ($type == "hover") {
      $this->uploadedAsset("category_images/hover/" . $file);
    }
  }

  public function productImage($file)
  {
    $this->uploadedAsset("product_images/" . $file);
  }
}
