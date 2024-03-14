<?php

namespace App\Controller;

use App\Extractor\Extractor;
use App\Extractor\Extractors\A1Extractor;
use App\Extractor\Filters\ItalicTagFilter;
use App\Loader\SrtLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class WordsController extends AbstractController {

	#[Route('/api/words/', name: 'words')]
	public function words(Request $request): JsonResponse {
		$filename = (string)$request->get('filename');
		if ($filename === '') {
			throw new BadRequestHttpException('filename not given');
		}

		$filePath = $this->getParameter('kernel.project_dir') . "/var/subtitles/{$filename}.srt";
		$srtLoader = new SrtLoader($filePath);
		$subtitleLines = $srtLoader->getSubtitleLines();

		$extractor = new Extractor([new ItalicTagFilter()], [new A1Extractor($this->getParameter('kernel.project_dir') . '/data/word_lists/A1.php')]);
		$words = $extractor->getWords($subtitleLines);

		$response = [];
		foreach ($words as $word) {
			if (!\array_key_exists($word->category, $response)) {
				$response[$word->category] = ['category' => $word->category, 'words' => []];
			}
			$response[$word->category]['words'][] = [
				'id' => $word->word,
				'left' => $word->leftText,
				'middle' => $word->word,
				'right' => $word->rightText,
			];
		}
		$response = array_values($response);
		return $this->json($response);
	}

	#[Route('/api/upload', name: 'upload')]
	public function upload(Request $request): JsonResponse {
		/**
		 * @var ?UploadedFile $file
		 */
		$file = $request->files->get('file');
		if ($file === null) {
			throw new BadRequestHttpException('no file given');
		}

		if ($file->getClientOriginalExtension() !== 'srt') {
			throw new BadRequestHttpException('only srt files are supported');
		}

		if ($file->getSize() > 1_000_000) {
			throw new BadRequestHttpException('file is too big');
		}
		try {
			$file->move($this->getParameter('kernel.project_dir') . '/var/subtitles/', $file->getClientOriginalName());
		} catch (FileException $ex) {
			return $this->json(['error' => 'something went wrong when trying to upload the file'], 500);
		}

		return $this->json([]);
	}

	#[Route('/api/subtitles', 'subtitles')]
	public function getFiles(): JsonResponse {
		$files = scandir($this->getParameter('kernel.project_dir') . '/var/subtitles/');
		if ($files === false) {
			throw new \UnexpectedValueException('could not list the files in the subtitles directory');
		}

		$files = array_values(array_diff($files, ['.', '..']));
		$files = array_map(static fn (string $file): string => str_replace('.srt', '', $file), $files);

		return $this->json($files);
	}
}
