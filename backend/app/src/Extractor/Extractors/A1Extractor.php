<?php

namespace App\Extractor\Extractors;

use App\Extractor\ExtractorInterface;
use App\Extractor\SubtitleLine;
use App\Extractor\Word;

final class A1Extractor implements ExtractorInterface {

	public function __construct(private readonly string $wordListFilename) {
	}

	public function extract(SubtitleLine $subtitleLine): array {
		$words = [];

		$A1words = require $this->wordListFilename;
		$A1words = array_flip($A1words);

		$items = preg_split('/\s+/', $subtitleLine->text);
		foreach ($items as $i => $item) {
			if (\array_key_exists($item, $A1words)) {
				$words[] = new Word(
					$item,
					'A1',
					implode(' ', array_slice($items, 0, $i)),
					implode(' ', array_slice($items, $i + 1)),
				);
			}
		}

		return $words;
	}

}
