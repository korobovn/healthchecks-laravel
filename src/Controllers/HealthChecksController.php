<?php

declare(strict_types = 1);

namespace AvtoDev\HealthChecks\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use AvtoDev\HealthChecks\HealthChecksInterface;
use Illuminate\Contracts\Routing\ResponseFactory;

class HealthChecksController extends \Illuminate\Routing\Controller
{
    /**
     * @param Request               $request
     * @param HealthChecksInterface $health_checks
     * @param ResponseFactory       $factory
     *
     * @return JsonResponse
     */
    public function check(Request $request,
                          HealthChecksInterface $health_checks,
                          ResponseFactory $factory): JsonResponse
    {
        $group = $request->input('group');

        if (\is_string($group) && $group !== '') {
            // Execute checks group
        }

        return $factory->json([
            // @todo: set data
        ]);
    }
}
