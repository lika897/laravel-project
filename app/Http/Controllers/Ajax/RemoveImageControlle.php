<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RemoveImageControlle extends Controller
{

    public function __invoke(Image $image): JsonResponse
    {
        $disk = Storage::disk('public');
        $path = $image->path;
        $folder = dirname($path);

        try {
            if ($disk->exists($path)) {
                $disk->delete($path);
            }

            $image->deleteOrFail();

            if (empty($disk->allFiles($folder))) {
                $disk->deleteDirectory($folder);
            }

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully',
            ]);
        } catch (\Throwable $th) {
            logs()->error('[RemoveImageController] Error deleting image', [
                'image_id' => $image->id,
                'message' => $th->getMessage(),
                'exception' => $th,
            ]);

            return response()->json([
                'error' => 'Error deleting image',
                'details' => $th->getMessage(),
            ], 422);
        }
    }


//    public function __invoke(Image $image)
//    {
//        try {
//            $image->deleteOrFail();
//
//            return response()->json([
//                'message' => 'The image was successfully removed',
//            ]);
//
//        } catch (\Throwable $th){
//            logs()->error('[RemoveImageController]: ' . $th->getMessage(), [
//                'image_id' => $image->id,
//                'exception' => $th,
//            ]);
//
//            return response()->json([
//                'massage' => $th->getMessage(),
//            ], 422);
//        }
//    }
}
