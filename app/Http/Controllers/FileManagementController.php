<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Traits\CommonImageUpload;
use Illuminate\Support\Facades\Storage;

class FileManagementController extends Controller
{
    use CommonImageUpload;

    /**
     * @param Request $request
     * @return void
     */
    public function simpleUploadFile(Request $request)
    {
        $this->imageUpload($request->simple_file,$request->type);
        return back();
    }

    public function onTimeDisks(Request $request)
    {
        $type = $request->driver;
        $attachment = $request->image;
        Storage::build([
            'driver' => $type,
            'root' => storage_path('app/public/'.$type),
        ]);

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

        return back();
    }
}
