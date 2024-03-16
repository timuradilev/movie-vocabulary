<?php

namespace App\Extractor;

final class Word {

	public function __construct(
		public readonly string $value,
		public readonly string $category,
		public readonly string $leftText,
		public readonly string $rightText,
	) {
	}

}