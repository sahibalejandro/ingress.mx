<?php
class QuarkImage_dev
{
  private $img_file;
  private $src_img_info;
  private $dst_img_info;
  
  private $bg_color_r = 255;
  private $bg_color_g = 255;
  private $bg_color_b = 255;
  
  private $jpg_quality     = 80;
  private $png_compression = 9;
  private $png_filter      = null;
  
  private $keep_transparency = true;
  
  const RESIZE_PROPORTIONAL = 1;
  const RESIZE_STRETCH      = 2;
  const RESIZE_CROP         = 4;
  
  public function __construct($img_file)
  {
    // Tasks to do in output()
    $this->tasks        = array();
    
    $this->img_file     = $img_file;
    $this->src_img_info = getimagesize($img_file);
    
    if (!$this->isImageTypeSupported($this->src_img_info[2])) {
      die('Unsupported image');
    }
    
    $this->dst_img_info = $this->src_img_info;
  }
  
  public function resize($w, $h, $resize_type = QuarkImage_dev::RESIZE_PROPORTIONAL)
  {
    // Check if resize will be in proportional & stretching
    $proportional_stretch = false;
    if ($resize_type == (self::RESIZE_PROPORTIONAL|self::RESIZE_STRETCH)) {
      $proportional_stretch = true;
      $resize_type          = self::RESIZE_PROPORTIONAL;
    }
    
    // Calculate the new sizes
    if ($resize_type == self::RESIZE_STRETCH) {
      $this->dst_img_info[0] = $w;
      $this->dst_img_info[1] = $h;
      
    } elseif ($resize_type == self::RESIZE_PROPORTIONAL) {
      
      if ($this->src_img_info[0] >= $this->src_img_info[1]) {
        // Resize proportional based on width
        list($this->dst_img_info[0], $this->dst_img_info[1])
          = $this->calculateProportionalSizes(
            $this->src_img_info[0],
            $this->src_img_info[1],
            $w,
            $h,
            $proportional_stretch
          );
      
      } elseif ($this->src_img_info[0] < $this->src_img_info[1]) {
        // Resize proportional based on height
        list($this->dst_img_info[1], $this->dst_img_info[0])
          = $this->calculateProportionalSizes(
            $this->src_img_info[1],
            $this->src_img_info[0],
            $h,
            $w,
            $proportional_stretch
          );
      }
    } elseif ($resize_type == self::RESIZE_CROP) {
      
    }
  }
  
  public function output($file_name)
  {
    $image_type = $this->getImageTypeFromFileName($file_name);
    if (!$this->isImageTypeSupported($image_type)) {
      die('output image type not supported');
    }

    if ($this->src_img_info[2] == IMAGETYPE_JPEG || $this->src_img_info[2] == IMAGETYPE_JPEG2000) {
      $src_img = imagecreatefromjpeg($this->img_file);
    } elseif ($this->src_img_info[2] == IMAGETYPE_PNG) {
      $src_img = imagecreatefrompng($this->img_file);
    } else {
      $src_img = imagecreatefromgif($this->img_file);
    }
    
    $dst_img = imagecreatetruecolor($this->dst_img_info[0], $this->dst_img_info[1]);
    
    // Fill with background color
    imagefill($dst_img, 0, 0, imagecolorallocate(
      $dst_img,
      $this->bg_color_r,
      $this->bg_color_g,
      $this->bg_color_b
    ));
    
    // Preserve transparency for PNG and GIF
    if ($this->keep_transparency
      && ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_GIF)
    ) {
      // Use same background color for transparency
      $transparent = imagecolorallocatealpha(
        $dst_img,
        $this->bg_color_r,
        $this->bg_color_g,
        $this->bg_color_b,
        127
      );
      imagealphablending($dst_img, false);
      imagesavealpha($dst_img, true);
      imagecolortransparent($dst_img, $transparent);
      imagefilledrectangle(
        $dst_img,
        0,
        0,
        $this->dst_img_info[0],
        $this->dst_img_info[1],
        $transparent
      );
    }
    
    $this->imgCopy(
      $dst_img, $src_img,
      0, 0,
      0, 0,
      $this->dst_img_info[0], $this->dst_img_info[1],
      $this->src_img_info[0], $this->src_img_info[1],
      $image_type
    );
    
    // Write image file (or send to buffer)
    if ( $image_type == IMAGETYPE_JPEG) {
      imagejpeg($dst_img, $file_name, $this->jpg_quality);
    } elseif ($image_type == IMAGETYPE_PNG) {
      imagepng($dst_img, $file_name, $this->png_compression, $this->png_filter);
    } elseif ($image_type == IMAGETYPE_GIF) {
      imagegif($dst_img, $file_name);
    }
    
    imagedestroy($src_img);
    imagedestroy($dst_img);
  }
  
  public function setBackgroundColor($red, $green, $blue)
  {
    $this->bg_color_r = $red;
    $this->bg_color_g = $green;
    $this->bg_color_b = $blue;
  }
  
  public function setJPGQuality($quality)
  {
    $this->jpg_quality = $quality;
  }
  
  public function setPNGCompression($compression)
  {
    $this->png_compression = $compression;
  }
  
  public function setPNGFilter($filter)
  {
    $this->png_filter = $filter;
  }
  
  public function keepTransparency($keep)
  {
    $this->keep_transparency = $keep;
  }
  
  private function imgCopy(
    $dst_img, $src_img,
    $dst_x, $dst_y,
    $src_x, $src_y,
    $dst_w, $dst_h,
    $src_w, $src_h,
    $dst_imagetype
  ) {
    if ($dst_imagetype == IMAGETYPE_GIF) {
      imagecopyresized(
        $dst_img, $src_img,
        $dst_x, $dst_y,
        $src_x, $src_y,
        $dst_w, $dst_h,
        $src_w, $src_h
      );
    } else {
      imagecopyresampled(
        $dst_img, $src_img,
        $dst_x, $dst_y,
        $src_x, $src_y,
        $dst_w, $dst_h,
        $src_w, $src_h
      );
    }
  }
  
  /**
   * Proportionally calculate the sizes of $base_size1 and $base_size2 to fit
   * $max_size1 and $max_size2 and return the new size1 and new size2 in an array.
   * 
   * @param int $base_size1 Original "size1"
   * @param int $base_size2 Original "size2"
   * @param int $max_size1 Max value that "size1" can have
   * @param int $max_size2 Max value that "size2" can have
   * @return array(new_size1, new_size2)
   */
  private function calculateProportionalSizes(
    $base_size1,
    $base_size2,
    $max_size1,
    $max_size2,
    $stretch = false
  ) {
    
    if (!$stretch && $base_size1 <= $max_size1 && $base_size2 <= $max_size2) {
      $new_size1 = $base_size1;
      $new_size2 = $base_size2;
    } else {
      // Output sizes
      $new_size1 = $max_size1;
      $new_size2 = $max_size2;
      
      /*
       * Calculate new sizes.
       *
       * Algorithm is like:
       *   1. Match new_size1 to max_size1.
       *   2. Calculate the new_size2 proportional to max_size1.
       *   3. If new_size2 is still exceeding the value of max_size2 then recalculate
       *      the value of new_size1 proportional to max_size2 and match new_size2
       *      to max_size2
       */
      if ($base_size1 > $max_size1 || ($stretch && $max_size1 >= $base_size1)) {
        // new height = original height / original width * new width
        $new_size2 = ($base_size2 / $base_size1) * $max_size1;
      }
          
      if ($new_size2 > $max_size2) {
        // new width = original width / original height * new height
        $new_size1 = ($new_size1 / $new_size2) * $max_size2;
        $new_size2 = $max_size2;
      }
    }
    
    return array(round($new_size1), round($new_size2));
  }
  
  /**
   * Check if $image_type is supported
   * 
   * @param int $image_type Image type value like PHP's IMATETYPE_XXX
   * @return bool true if supported, false if not.
   */
  private function isImageTypeSupported($image_type)
  {
    return !(
      $image_type    !== IMAGETYPE_JPEG
      && $image_type !== IMAGETYPE_JPEG2000
      && $image_type !== IMAGETYPE_PNG
      && $image_type !== IMAGETYPE_GIF
    );
  }
  
  private function getImageTypeFromFileName($file_name)
  {
    $file_name_parts = explode('.', $file_name);
    $ext = strtolower(array_pop($file_name_parts));
    if ($ext == 'jpg' || $ext == 'jpeg') {
      return IMAGETYPE_JPEG;
    } elseif ($ext == 'png') {
      return IMAGETYPE_PNG;
    } elseif ($ext == 'gif') {
      return IMAGETYPE_GIF;
    } else {
      return false;
    }
  }
}
