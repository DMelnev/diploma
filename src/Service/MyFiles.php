<?php


namespace App\Service;


use Exception;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class MyFiles
{
    private SluggerInterface $slugger;
    private FilesystemOperator $uploadsPictureFilesystem;

    public function __construct(SluggerInterface $slugger, FilesystemOperator $uploadsPictureFilesystem)
    {
        $this->slugger = $slugger;
        $this->uploadsPictureFilesystem = $uploadsPictureFilesystem;
    }

    private function checkPath(string &$path): bool
    {
        return file_exists(trim($path));
    }

    public function getListFiles(string $path): array
    {
        $resultArray = [];
        if (!$this->checkPath($path)) return $resultArray;
        $filesList = array_diff(scandir($path), ['.', '..', '.gitignore']);
        foreach ($filesList as $file) {
            if (!is_dir($path . $file)) {
                $resultArray[] = [
                    'filename' => $file,
                    'size' => filesize($path . $file),
                    'created' => filectime($path . $file),
                    'link' => $path . $file,
                ];
            }
        }
        return $resultArray;
    }

    public function deleteFile(string $path): string
    {
        try {
            if ($path && $this->uploadsPictureFilesystem->fileExists($path)) {
                $this->uploadsPictureFilesystem->delete($path);
                return 'Файл ' . $path . ' удален!';
            }
            return 'Файл ' . $path . ' не найден!';
        } catch (FilesystemException $e) {
            return 'Не удалось удалить файл ' . $path . '. Ошибка ' . $e->getMessage();
        }
    }

    /**
     * @throws FilesystemException
     * @throws Exception
     */
    public function uploadFile(File $file): string
    {
        $fileName = $this->slugger
            ->slug(pathinfo(
                    $file instanceof UploadedFile ? $file->getClientOriginalName() : $file->getFilename(),
                    PATHINFO_FILENAME)
            )
            ->append('-' . uniqid())
            ->append('.' . $file->guessExtension())
            ->toString();
        try {
            $stream = fopen($file->getPathname(), 'r');
            $this->uploadsPictureFilesystem->writeStream($fileName, $stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
            return 'Файл ' . $fileName . ' сохранен!';
        } catch (\Exception $e) {
            return 'Не удалось записать файл ' . $fileName . '. Ошибка ' . $e->getMessage();
        }
    }

    /**
     * @throws FilesystemException
     * @throws \Exception
     */
    public function uploadDeleteFile(File $file, ?string $oldFileName = null): string
    {
        $fileName = $this->slugger
            ->slug(pathinfo(
                    $file instanceof UploadedFile ? $file->getClientOriginalName() : $file->getFilename(),
                    PATHINFO_FILENAME)
            )
            ->append('-' . uniqid())
            ->append('.' . $file->guessExtension())
            ->toString();


        try {
            $stream = fopen($file->getPathname(), 'r');
            $this->uploadsPictureFilesystem->writeStream($fileName, $stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        } catch (\Exception $e) {
            throw new \Exception('Не удалось записать файл ' . $fileName . '. Ошибка ' . $e->getMessage());
        }

        try {
            if ($oldFileName && $this->uploadsPictureFilesystem->fileExists($oldFileName)) {
                $this->uploadsPictureFilesystem->delete($oldFileName);
            }
        } catch (\Exception $e) {
            throw new \Exception('Не удалось удалить файл ' . $fileName . '. Ошибка ' . $e->getMessage());
        }

        return $fileName;
    }

}