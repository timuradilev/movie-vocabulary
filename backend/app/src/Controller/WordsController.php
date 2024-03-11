<?php

namespace App\Controller;

use App\Extractor\Extractor;
use App\Extractor\Extractors\A1Extractor;
use App\Extractor\Filters\ItalicTagFilter;
use App\Loader\SrtLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class WordsController extends AbstractController {

	#[Route('/api/words', name: 'words')]
	public function words(): JsonResponse {
		$filename = $this->getParameter('kernel.project_dir') . '/data/subtitles.srt';
		$srtLoader = new SrtLoader($filename);
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
}
