<?php

namespace App\Extractor;

final class Extractor {

	/**
	 * @param list<FilterInterface> $filters
	 * @param list<ExtractorInterface> $extractors
	 */
	public function __construct(
		private readonly array $filters,
		private readonly array $extractors,
	) {
	}

	/**
	 * @param list<SubtitleLine> $subtitleLines
	 * @return list<Word>
	 */
	public function getWords(array $subtitleLines): array {
		$filtered = [];
		foreach ($subtitleLines as $subtitleLine) {
			foreach ($this->filters as $filter) {
				$subtitleLine = $filter->filter($subtitleLine);
				if ($subtitleLine->text === '') {
					break;
				}
			}

			if ($subtitleLine->text !== '') {
				$filtered[] = $subtitleLine;
			}
		}

		$words = [];
		foreach ($filtered as $subtitleLine) {
			foreach ($this->extractors as $extractor) {
				$words[] = $extractor->extract($subtitleLine);
				if ($subtitleLine->text === '') {
					break;
				}
			}
		}

		return array_merge(...$words);
	}

}
