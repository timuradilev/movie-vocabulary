<?php

namespace App\Extractor\Extractors;

use App\Extractor\ExtractorInterface;
use App\Extractor\SubtitleLine;
use App\Extractor\Word;

final class WordListExtractor implements ExtractorInterface {

	public function __construct(
		private readonly string $category,
		private readonly string $wordListFilename,
	) {
	}

	/**
	 * @param SubtitleLine $subtitleLine
	 * @return list<Word>
	 */
	public function extract(SubtitleLine $subtitleLine): array {
		$words = [];

		$A1words = require $this->wordListFilename;
		$A1words = array_flip($A1words);

		foreach ($subtitleLine as $i => $word) {
			if (\array_key_exists($word, $A1words)) {
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
