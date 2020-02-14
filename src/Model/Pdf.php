<?php

namespace Zamoroka\WebapiDocuments\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Zamoroka\WebapiDocuments\Api\PdfInterface;

/**
 * Class BlogManagement
 */
class Pdf implements PdfInterface
{
    public const FILE_TYPE = 'application/pdf';
    /** @var Http */
    private $request;
    /** @var Filesystem */
    private $filesystem;
    /** @var UploaderFactory */
    private $uploaderFactory;
    /** @var Filesystem\Directory\WriteInterface */
    private $varDirectory;

    /**
     * BlogManagement constructor.
     *
     * @param Http $request
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     *
     * @throws FileSystemException
     */
    public function __construct(
        Http $request,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory
    ) {
        $this->request = $request;
        $this->filesystem = $filesystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->varDirectory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
    }

    /**
     * @inheritDoc
     */
    public function upload(): string
    {
        try {
            $fileInfo = $this->request->getFiles('filename');
            $this->validateFile($fileInfo);
            $this->saveFile();

            return 'File successfully uploaded';
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param array $fileInfo
     *
     * @throws ValidatorException
     */
    private function validateFile(array $fileInfo)
    {
        if (!$fileInfo) {
            throw new ValidatorException(__('File info is not set'));
        }
        if (!is_array($fileInfo)) {
            throw new ValidatorException(__('File data should be an array'));
        }
        if (isset($fileInfo['error']) && $fileInfo['error']) {
            throw new ValidatorException(__('Unknown error'));
        }
        if (!isset($fileInfo['name'])) {
            throw new ValidatorException(__('File name is not set'));
        }
        if (!isset($fileInfo['type']) || $fileInfo['type'] !== self::FILE_TYPE) {
            throw new ValidatorException(__('File type is not valid'));
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function saveFile()
    {
        $uploader = $this->uploaderFactory->create(['fileId' => 'filename']);
        $workingDir = $this->varDirectory->getAbsolutePath('webapidocuments/');

        return $uploader->save($workingDir);
    }
}
