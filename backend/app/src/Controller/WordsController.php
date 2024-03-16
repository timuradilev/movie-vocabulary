<?php

namespace App\Controller;

use App\Entity\SubtitlesFile;
use App\Entity\Word;
use App\Extractor\Extractor;
use App\Extractor\Extractors\A1Extractor;
use App\Extractor\Filters\ItalicTagFilter;
use App\Loader\SrtLoader;
use App\Repository\SubtitlesFileRepository;
use App\Repository\WordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class WordsController extends AbstractController {

	#[Route('/api/subtitles/list', 'subtitles_list')]
	public function getSubtitlesFiles(SubtitlesFileRepository $repository): JsonResponse {
		$files = $repository->findBy([], ['id' => 'DESC']);
		$files = array_map(static fn (SubtitlesFile $file): array => ['id' => $file->getId(), 'name' => $file->getName()], $files);

		return $this->json($files);
	}

	#[Route('/api/subtitles/upload', name: 'subtitles_upload')]
	public function uploadSubtitleFile(Request $request, EntityManagerInterface $entityManager): JsonResponse {
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

		$replacedCount = 0;
		$name = str_replace('.srt', '', $file->getClientOriginalName(), $replacedCount);
		if ($replacedCount === 0) {
			throw new \AssertionError('could not remove the extension');
		}

		$storagePath = uniqid();
		$storageDirectory = $this->getParameter('kernel.project_dir') . '/var/subtitles/';

		try {
			$file->move($storageDirectory, $storagePath);
		} catch (FileException $ex) {
			return $this->json(['error' => 'something went wrong when trying to upload the file'], 500);
		}

		$subtitlesFile = new SubtitlesFile();
		$subtitlesFile->setName($name);
		$subtitlesFile->setStoragePath($storagePath);
		$subtitlesFile->setCreatedTs(time());
		$entityManager->persist($subtitlesFile);
		$entityManager->flush();

		$filePath = "{$storageDirectory}{$subtitlesFile->getStoragePath()}";
		$srtLoader = new SrtLoader($filePath);
		$subtitleLines = $srtLoader->getSubtitleLines();
		
		$extractor = self::getExtractor($this->getParameter('kernel.project_dir'));
		$words = $extractor->getWords($subtitleLines);

		foreach ($words as $word) {
			$wordObject = new Word();
			$wordObject->setValue($word->value);
			$wordObject->setCreatedTs(time());
			$wordObject->setSubtitlesFile($subtitlesFile);

			$entityManager->persist($wordObject);
		}
		$entityManager->flush();

		return $this->json(['id' => $subtitlesFile->getId()]);
	}

	#[Route('/api/subtitles/{subtitlesFileId}', name: 'subtitles_words', requirements: ['subtitles_file_id' => '\d+'])]
	public function getSubtitlesFilesWords(int $subtitlesFileId, SubtitlesFileRepository $subtitlesFileRepository, WordRepository $wordRepository): JsonResponse {
		if ($subtitlesFileId === 0) {
			$subtitlesFile = $subtitlesFileRepository->findOneBy([], ['id' => 'DESC']);
			if ($subtitlesFile === null) {
				return $this->json([]);
			}
		} else {
			$subtitlesFile = $subtitlesFileRepository->find($subtitlesFileId);
			if ($subtitlesFile === null) {
				throw new NotFoundHttpException('file not found');
			}
		}

		$filePath = $this->getParameter('kernel.project_dir') . "/var/subtitles/{$subtitlesFile->getStoragePath()}";
		$srtLoader = new SrtLoader($filePath);
		$subtitleLines = $srtLoader->getSubtitleLines();

		$extractor = self::getExtractor($this->getParameter('kernel.project_dir'));
		$words = $extractor->getWords($subtitleLines);

		$wordValues = array_map(static fn (\App\Extractor\Word $word): string => $word->value, $words);
		$knownWords = $wordRepository->getKnownWords($wordValues, strtotime('-3 months'), $subtitlesFile->getCreatedTs(), $subtitlesFile);
		$knownWords = array_flip($knownWords);

		$response = [];
		foreach ($words as $word) {
			if (\array_key_exists($word->value, $knownWords)) {
				continue;
			}

			if (!\array_key_exists($word->category, $response)) {
				$response[$word->category] = ['category' => $word->category, 'words' => []];
			}
			$response[$word->category]['words'][] = [
				'id' => $word->value,
				'left' => $word->leftText,
				'middle' => $word->value,
				'right' => $word->rightText,
			];
		}
		$response = array_values($response);
		return $this->json($response);
	}

	private static function getExtractor(string $projectDir): Extractor {
		return new Extractor([new ItalicTagFilter()], [new A1Extractor("{$projectDir}/data/word_lists/A1.php")]);
	}
}
