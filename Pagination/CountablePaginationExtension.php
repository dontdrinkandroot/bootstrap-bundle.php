<?php

namespace Dontdrinkandroot\BootstrapBundle\Pagination;

use Countable;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CountablePaginationExtension extends AbstractExtension
{
    /** @var UrlGeneratorInterface */
    private $generator;

    /** @var RequestStack  */
    private $requestStack;

    public function __construct(UrlGeneratorInterface $generator, RequestStack $requestStack)
    {
        $this->generator = $generator;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'ddr_bs_countable_pagination',
                [$this, 'generatePagination'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ddr_bs_paginator_pagination',
                [$this, 'generatePagination'],
                ['is_safe' => ['html']]
            )
        ];
    }

    public function generatePagination(Countable $countable, int $page, int $perPage): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $route = $request->attributes->get('_route');

        $total = $countable->count();
        $totalPages = $this->getTotalPages($total, $perPage);

        $params = array_merge($request->attributes->get('_route_params'), $request->query->all());

        $html = '<ul class="pagination">' . PHP_EOL;

        /* Render prev page */
        $cssClasses = [];
        if ($page === 1) {
            $cssClasses[] = 'disabled';
        }
        $cssClasses[] = 'page-item';
        $html .= $this->renderLink($page - 1, '&laquo;', $route, $params, $cssClasses, 'prev');

        $surroundingStartIdx = max(1, $page - 2);
        $surroundingEndIdx = min($totalPages, $page + 2);

        /* Render first page */
        if ($surroundingStartIdx > 1) {
            $html .= $this->renderLink(1, 1, $route, $params);
        }

        /* Render dots */
        if ($surroundingStartIdx > 2) {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#">&hellip;</a></li>' . PHP_EOL;
        }

        /* Render surrounding pages */
        if ($totalPages > 0) {
            for ($i = $surroundingStartIdx; $i <= $surroundingEndIdx; $i++) {
                $cssClasses = [];
                $cssClasses[] = 'page-item';
                if ($i === $page) {
                    $cssClasses[] = 'active';
                }
                $html .= $this->renderLink($i, $i, $route, $params, $cssClasses);
            }
        }

        /* Render dots */
        if ($surroundingEndIdx < $totalPages - 1) {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#">&hellip;</a></li>' . PHP_EOL;
        }

        /* Render last page */
        if ($surroundingEndIdx < $totalPages) {
            $html .= $this->renderLink($totalPages, $totalPages, $route, $params);
        }

        /* Render next page */
        $cssClasses = [];
        if ($page >= $totalPages) {
            $cssClasses[] = 'disabled';
        }
        $html .= $this->renderLink($page + 1, '&raquo;', $route, $params, $cssClasses, 'next');

        $html .= '</ul>' . PHP_EOL;

        return $html;
    }

    public function renderLink(
        int $page,
        string $text,
        string $route,
        array $params,
        array $cssClasses = [],
        string $rel = null
    ) {
        $params['page'] = $page;
        $path = '#';
        if (!in_array('disabled', $cssClasses)) {
            $path = $this->getPath($route, $params);
        }

        $html = '<li class="' . implode(' ', $cssClasses) . '">';
        $html .= '<a class=\'page-link\' href="' . $path . '"';
        if (null !== $rel) {
            $html .= ' rel="' . $rel . '"';
        }
        $html .= '>' . $text . '</a>';
        $html .= '</li>' . PHP_EOL;

        return $html;
    }

    public function getPath($name, $parameters = [], $relative = false)
    {
        return $this->generator->generate(
            $name,
            $parameters,
            $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }

    private function getTotalPages(int $total, int $perPage): int
    {
        if ($total === 0) {
            return 0;
        }

        return (int)(($total - 1) / $perPage + 1);
    }
}
