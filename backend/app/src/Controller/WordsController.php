<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class WordsController extends AbstractController {

	#[Route('/api/words', name: 'words')]
	public function words(): JsonResponse {
		return $this->json([
			'category' => 'A1',
			'words' => [
				[
					'id' => 'word1',
					'left' => 'left txt dasdsadsadas',
					'middle' => 'word1',
					'right' => 'right txt',
				],
				[
					'id' => 'word2',
					'left' => 'left txt',
					'middle' => 'wordfdsfsdd2',
					'right' => 'right txtdsfsdfd',
				],
				[
					'id' => 'word3',
					'left' => 'left txt',
					'middle' => 'word3',
					'right' => 'right txt',
				],
				[
					'id' => 'word4',
					'left' => 'ldsffdsfsdfdseft txt',
					'middle' => 'word4',
					'right' => 'right txtdsfds',
				],
				[
					'id' => 'word5',
					'left' => 'left txt',
					'middle' => 'word5',
					'right' => 'right txt',
				],
			],
		]);
	}
}
