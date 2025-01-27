<?php
// Helper function to apply base64_encode twice on an ID.
if (!function_exists('suCrypt')) {
    /**
     * @param mixed $id
     * @return string
     */
    function suCrypt($id)
    {
        return base64_encode(base64_encode($id));
    }

}


// Upload an image to the specified folder and name it using suCrypt($id).
if (!function_exists('imageUpload')) {
    /**
     * @param string $folderName The folder name to save the image.
     * @param \Illuminate\Http\UploadedFile $image The image file to upload.
     * @param int $id The ID to generate the image name.
     * @return string The name of the uploaded image file.
     * @throws \Exception If the image upload fails.
     */
    function imageUpload(string $folderName, $image, int $id): string{
        try {
            // Validate the image
            if (!$image->isValid()) {
                throw new \Exception("Invalid image file.");
            }

            // Generate the unique file name using suCrypt($id)
            $encryptedId = suCrypt($id);
            $extension = $image->getClientOriginalExtension();
            $fileName = $encryptedId . '.' . $extension;

            // Define the upload path
            $uploadPath = public_path($folderName);

            // Ensure the folder exists
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $image->move($uploadPath, $fileName);

            return $fileName;
        } catch (\Exception $e) {
            // Handle the exception and rethrow it
            throw new \Exception("Image upload failed: " . $e->getMessage());
        }
    }
}

// Delete an image from the specified folder.
if (!function_exists('deleteImage')) {
    /**
 * Delete image(s) with specified extensions or all matching files
 *
 * @param string $folderName The folder where images are stored
 * @param int $id The ID used to generate the encrypted filename
 * @param array|null $extensions Array of extensions to delete (e.g., ['jpg', 'png']) or null to delete all
 * @return array Result containing success status and details of deleted files
 * @throws \Exception If there is an issue with deletion process
 */
    function deleteImage($folderName, $id, $extensions = null) {
        try {
            $encryptedId = suCrypt($id);
            $basePattern = $folderName . '/' . $encryptedId;
            $deletedFiles = [];
            $notFoundFiles = [];

            // If no extensions provided, use glob to find all matching files
            if ($extensions === null) {
                $pattern = public_path($basePattern . '.*');
                $files = glob($pattern);
                
                if (empty($files)) {
                    throw new \Exception(" No File Found.");
                }

                foreach ($files as $file) {
                    if (file_exists($file)) {
                        unlink($file);
                        $deletedFiles[] = basename($file);
                    } else {
                        $notFoundFiles[] = basename($file);
                    }
                }
            } 
            // If extensions provided, look for specific extensions
            else {
                foreach ($extensions as $ext) {
                    $filePath = public_path($basePattern . '.' . ltrim($ext, '.'));
                    
                    if (file_exists($filePath)) {
                        unlink($filePath);
                        $deletedFiles[] = basename($filePath);
                    } else {
                        $notFoundFiles[] = basename($filePath);
                    }
                }
            }

            return true;

        } catch (\Exception $e) {
            throw new \Exception("Image deletion failed: " . $e->getMessage());
        }
    }
}

//  * Find an image in the specified folder.
if (!function_exists('findImage')) {
    /**
     * @param int $id The ID to generate the image name.
     * @param string $folderName The folder where the image is stored.
     * @return string|null The relative path to the image if found, null otherwise.
     * @throws \Exception If there is an issue finding the image.
     */
    function findImage(int $id, string $folderName): ?string
    {
        try {
            // Generate the encrypted file name using suCrypt($id)
            $encryptedId = suCrypt($id);

            // Create the filename in the same format
            $fileName = $encryptedId . '.' . 'jpg';

            // Define the path to check if the image exists
            $imagePath = public_path($folderName . '/' . $fileName);

            // Check if the image exists
            if (file_exists($imagePath)) {
                // Return just the relative path
                return $folderName . '/' . $fileName;
            } else {
                return null; // Image not found
            }
        } catch (\Exception $e) {
            // Handle the exception and rethrow it
            throw new \Exception("Image search failed: " . $e->getMessage());
        }
    }
}