<?php

namespace App\Extractor;

interface FilterInterface {

	public function filter(string $subtitleLine): ?string;

}
