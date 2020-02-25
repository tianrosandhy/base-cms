<?php
namespace Module\Media\Services\Traits;

use Module\Media\Exceptions\MediaException;
use Module\Media\Models\Media;
use Intervention\Image\Constraint;
use Validator;
use Image;
use Storage;

trait Uploader
{
  public 
    $current_extension,
    $current_basename;

  public function upload($file=null, $directory=null){
    $this->validateFile($file);

    //generate directory dynamically by year and month
    $base_directory = date('Y').'/'.date('m');
    if(!empty($directory)){
      $base_directory = $directory . '/' . $base_directory;
    }

    return $this->uploadMaster($file, $base_directory);
  }

  protected function validateFile($file){
    $validator = Validator::make([
      'file' => $file
    ], [
      'file' => 'file|mimetypes:image/*|mimes:jpg,jpeg,png|max:'.(file_upload_max_size(config('cms.max_filesize.image')) / 1024)
    ], [
      'file.file' => 'Please upload the image',
      'file.mimetypes' => 'Please upload valid image only',
      'file.mimes' => 'Please upload image file only',
      'file.max' => 'Sorry, image size is too large'
    ]);

    if($validator->fails()){
      throw new MediaException($validator->errors()->first());
    }
  }

  public function uploadMaster($file, $path_directory=null){
    $filename = $file->getClientOriginalName(); //kalo mw nyimpen by filename
    $this->current_basename = $this->createBaseName($filename);
    $this->current_extension = $file->getClientOriginalExtension();
    $this->current_mimetype = $file->getMimeType();

    //store to storage
    $main_image = $this->saveToStorage($file, $path_directory);
    $thumbs = config('image.thumbs');
    //thumbnail generation
    foreach($thumbs as $thumbname => $widthdata){
      $this->generateThumbnail($main_image, $path_directory, $thumbname, $widthdata);
    }

    //save to database
    return $this->saveToDatabase($path_directory);
  }

  protected function createBaseName($filename){
    $pch = explode('.', $filename);
    $basename = str_replace('.'.$pch[count($pch)-1], '', $filename);
    $check = Media::where('basename', $basename)->count();
    if($check > 0){
      $basename .= '-'.substr(sha1(uniqid().time()), 5, 10);
    }
    return $basename;
  }

  protected function saveToStorage($file, $directory=''){
    $image = Image::make($file)->orientate();

    //cek apakah gambar origin memiliki lebar yg melebihi yg diizinkan di config.
    if(config('image.origin_maximum_width')){
      if($image->width() > config('image.origin_maximum_width')){
        //mencegah uploadan dengan ukuran dan kualitas terlalu tinggi
        $image->resize(config('image.origin_maximum_width'), null, function(Constraint $constraint){
          $constraint->aspectRatio();
        });
      }
    }

    $image_return = $image;
    $image = $image->encode($this->current_extension, config('image.quality'));

    Storage::put($this->createPath($directory, $this->current_basename.'.'.$this->current_extension), (string)$image);
    return $image_return;
  }

  protected function generateThumbnail($image, $directory='', $thumbname='', $thumbconfig=[]){
    $current_width = $image->width();
    $current_height = $image->height();
    $thumb_filename = $this->current_basename.'-'.$thumbname.'.'.$this->current_extension;

    //you dont need to force resize image if the size is too small.
    $resize_condition = false;
    if($thumbconfig['type'] == 'fit'){
      $resize_condition = true;
    }
    else{
      if($current_width > $thumbconfig['width']){
        $resize_condition = true;
      }
      if($current_height > $thumbconfig['height']){
        $resize_condition = true;
      }
    }

    if($resize_condition){
      if($thumbconfig['type'] == 'fit'){
        //crop center
        $image = $image->fit($thumbconfig['width'], $thumbconfig['height']);
      }
      else{
        //resize with keep aspect ratio
        $image = $image->resize(
          $thumbconfig['width'], $thumbconfig['height'],
          function (Constraint $constraint) {
            $constraint->aspectRatio();
          }
        );
      }
    }
    $image = $image->encode($this->current_extension, config('image.quality'));
    Storage::put($this->createPath($directory, $thumb_filename), (string)$image);
  }


  protected function saveToDatabase($path=''){
    $filename = $this->current_basename.'.'.$this->current_extension;

    $store = new Media;
    $store->filename = $filename;
    $store->path = $this->createPath($path, $filename);
    $store->basename = $this->current_basename;
    $store->extension = $this->current_extension;
    $store->mimetypes = $this->current_mimetype;
    $store->save();

    return $store;
  }

  protected function createPath($directory, $filename){
    return (strlen($directory) > 0 ? $directory.'/' : '') . $filename;
  }

}