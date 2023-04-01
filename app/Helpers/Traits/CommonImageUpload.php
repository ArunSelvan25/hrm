<?php

namespace App\Helpers\Traits;

use Illuminate\Support\Facades\Storage;

/**
 * Common image upload in disks
 */
trait CommonImageUpload {

    /**
     * @param file $attachment
     * @param string $type
     * @return string
     */
    public function imageUpload($attachment, string $type = ''): string
    {
        /** Image extension */
        $attachmentExtension = $attachment->getClientOriginalExtension();
        /** Image mime type */
        $attachment->getClientMimeType();
        /** Image original name */
        $attachment->getClientOriginalName();
        /** Image size */
        $attachment->getSize();
        /** Set folder path */
        $folderName = 'hrm-'.$type;
        /** Set image name */
        $imageName = 'hrm-'.$type . '-' . time() .'.'.$attachmentExtension ;
        /** store image in storage path */
        Storage::disk($type)->putFileAs($folderName,$attachment,$imageName);
        /** return image name */
        return $imageName;
    }

}
