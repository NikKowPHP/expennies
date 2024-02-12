<?php

declare(strict_types=1);

namespace App\RequestValidators;

use finfo;
use App\Services\CategoryService;
use App\Exception\ValidationException;
use App\Contracts\RequestValidatorInterface;
use League\MimeTypeDetection\FinfoMimeTypeDetector;

class UploadReceiptRequestValidator implements RequestValidatorInterface
{
	public function __construct(protected readonly CategoryService $categoryService)
	{
	}

	public function validate(array $data): array
	{
		$uploadedFile = $data['receipt'] ?? null;
		if (!$uploadedFile) {
			throw new ValidationException(['receipt' => ['Please select a receipt file']]);
		}

		if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
			throw new ValidationException(['receipt' => ['Failed to upload the receipt file']]);
		}

		$maxFileSize = 5 * 1024 * 1024;

		if ($uploadedFile->getSize() > $maxFileSize) {
			throw new ValidationException(['receipt' => ['Maximum allowed size is 5 MB']]);
		}

		$filename = $uploadedFile->getClientFilename();
		if (!preg_match('/^[a-zA-Z0-9\s._-]+$/', $filename)) {
			throw new ValidationException(['receipt' => ['Invalid filename']]);
		}

		$allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];
		// $allowedExtensions = ['jpeg', 'png', 'pdf', 'jpg'];
		$tmpFilePath = $uploadedFile->getStream()->getMetadata('uri');

		if (!in_array($uploadedFile->getClientMediaType(), $allowedMimeTypes)) {
			throw new ValidationException(['receipt' => ['Receipt has to be either an image or a pdf document']]);
		}



		$detector = new FinfoMimeTypeDetector();

		$mimeType = $detector->detectMimeTypeFromFile($tmpFilePath);

		if (!in_array($mimeType, $allowedMimeTypes)) {
			throw new ValidationException(['receipt' => ['invalid file type']]);
		}

		return $data;
	}
	private function getExtension(string $path): string
	{
		return (new finfo(FILEINFO_EXTENSION))->file($path) ?: '';

	}
	private function getMimeType(string $path): string
	{
		return (new finfo(FILEINFO_MIME_TYPE))->file($path) ?: '';

	}
}