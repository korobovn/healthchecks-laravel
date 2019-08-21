<?php

declare(strict_types = 1);

namespace AvtoDev\HealthChecks\Controllers;

use Illuminate\Http\JsonResponse;
use AvtoDev\HealthChecks\HealthChecksInterface;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Routing\Controller;

class HealthChecksController extends Controller
{
    /**
     * @param HealthChecksInterface $health_checks
     * @param ResponseFactory       $factory
     *
     * @return JsonResponse
     */
    public function check(HealthChecksInterface $health_checks,
                          ResponseFactory $factory): JsonResponse
    {
        $response = [];

        foreach ($health_checks->classes() as $check_class) {
            $result = $health_checks->execute($check_class);

            $response[] = \array_filter([
                'check'  => $check_class,
                'passed' => $result->passed(),
                'error'  => $result->getErrorMessage(),
            ]);
        }

        return $factory->json($response);
    }
}
