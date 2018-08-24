<?php


namespace box\components;

use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yiidreamteam\upload\exceptions\FileUploadException;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * Created by Madetec-Solution.
 * Developer: Mirkhanov Z.S.
 * Class UploadBehavior
 * @package box\components
 */
class UploadBehavior extends ImageUploadBehavior
{
    /**
     * @throws FileUploadException
     * @throws \yii\base\Exception
     */
   public function afterSave()
   {
       if ($this->file instanceof UploadedFile !== true) {
           return;
       }

       $path = $this->getUploadedFilePath($this->attribute);

       FileHelper::createDirectory(pathinfo($path, PATHINFO_DIRNAME), 0775, true);
//       $move = move_uploaded_file($this->file->tempName, $path);
       $copy = copy($this->file->tempName, $path);
       if (!$copy) {
           throw new FileUploadException($this->file->error, 'File saving error.');
       }

       $this->owner->trigger(static::EVENT_AFTER_FILE_SAVE);
   }
}
