<?php

namespace App\Extractor\Extractors;

use App\Extractor\ExtractorInterface;
use App\Extractor\SubtitleLine;
use App\Extractor\Word;

final class PatternExtractor implements ExtractorInterface {

	public function __construct(
		private readonly string $category,
		private readonly string $pattern,
	)
	{
		
	}

	public function extract(SubtitleLine $subtitleLine): array {
		$words = [];

		foreach ($subtitleLine as $i => $word) {
			if (preg_match($this->pattern, $word) === 1) {
				$words[] = new Word(
					$word,
					$this->category,
					$subtitleLine->getTextToTheLeft($i),
					$subtitleLine->getTextToTheRight($i),
				);
				$subtitleLine->removeWord($i);
			}
		}

		return $words;
	}
}
