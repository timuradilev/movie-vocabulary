<?php

namespace App\Extractor\Extractors;

use App\Extractor\ExtractorInterface;
use App\Extractor\SubtitleLine;
use App\Extractor\Word;

final class WordListExtractor implements ExtractorInterface {

	/**
	 * @var array<string, int> 
	 */
	private array $wordList;

	public function __construct(
		private readonly string $category,
		string $wordListFilename,
	) {
		$this->wordList = array_flip(require $wordListFilename);
	}

	/**
	 * @param SubtitleLine $subtitleLine
	 * @return list<Word>
	 */
	public function extract(SubtitleLine $subtitleLine): array {
		$words = [];

		foreach ($subtitleLine as $i => $word) {
			if (\array_key_exists($word, $this->wordList)) {
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
