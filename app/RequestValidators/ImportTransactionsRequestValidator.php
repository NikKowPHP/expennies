<?php

declare(strict_types=1);

namespace App\RequestValidators;

use finfo;
use App\Services\CategoryService;
use App\Exception\ValidationException;
use App\Contracts\RequestValidatorInterface;
use League\MimeTypeDetection\FinfoMimeTypeDetector;

class ImportTransactionsRequestValidator implements RequestValidatorInterface
{
	public function __construct(protected readonly CategoryService $categoryService)
	{
	}

	public function validate(array $data): array
	{
		$uploadedFile = $data['importFile'] ?? null;
		if (!$uploadedFile) {
			throw new ValidationException(['importFile' => ['Please select an import file']]);
		}

		if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
			throw new ValidationException(['importFile' => ['Failed to upload the file']]);
		}

		$maxFileSize = 5 * 1024 * 1024;

		if ($uploadedFile->getSize() > $maxFileSize) {
			throw new ValidationException(['importFile' => ['Maximum allowed size is 5 MB']]);
		}

		$filename = $uploadedFile->getClientFilename();
		if (!preg_match('/^[a-zA-Z0-9\s._-]+$/', $filename)) {
			throw new ValidationException(['importFile' => ['Invalid filename']]);
		}

		$allowedMimeTypes = ['text/csv'];
		// $allowedExtensions = ['jpeg', 'png', 'pdf', 'jpg'];
		$tmpFilePath = $uploadedFile->getStream()->getMetadata('uri');

		if (!in_array($uploadedFile->getClientMediaType(), $allowedMimeTypes)) {
			throw new ValidationException(['importFile' => ['The imported file has to be a csv document']]);
		}

		// $detector = new FinfoMimeTypeDetector();

		// $mimeType = $detector->detectMimeTypeFromFile($tmpFilePath);

		// if (!in_array($mimeType, $allowedMimeTypes)) {
		// 	throw new ValidationException(['importFile' => ['invalid file type']]);
		// }

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