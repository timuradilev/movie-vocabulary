<?php

namespace App\Extractor\Extractors;

use App\Extractor\ExtractorInterface;
use App\Extractor\SubtitleLine;
use App\Extractor\Word;

final class FilteredExtractor implements ExtractorInterface {

	public function extract(SubtitleLine $subtitleLine): array {
		$words = [];
		foreach ($subtitleLine as $i => $word) {
			if (preg_match('/([\'’](d|t|ve|ll|m)|^[a-z]{1,2})$/iu', $word) === 1) {
				$words[] = new Word(
					$word,
					'Filtered',
					$subtitleLine->getTextToTheLeft($i),
					$subtitleLine->getTextToTheRight($i),
				);
			}
		}

		return $words;
	}

}
