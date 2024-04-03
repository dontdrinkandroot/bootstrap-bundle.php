<?php

namespace Dontdrinkandroot\BootstrapBundle\Pagination;

use Countable;
use Dontdrinkandroot\Common\Asserted;
use Override;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CountablePaginationExtension extends AbstractExtension
{
    public function __construct(
        private readonly UrlGeneratorInterface $generator,
        private readonly RequestStack $requestStack
    ) {
    }

    #[Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'ddr_bs_countable_pagination',
                $this->generatePagination(...),
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ddr_bs_paginator_pagination',
                $this->generatePagination(...),
                ['is_safe' => ['html']]
            )
        ];
    }

    /**
     * @param array{prev_html?: string, next_html?: string, window_size?: int} $options
     */
    public function generatePagination(Countable $countable, int $page, int $perPage, array $options = []): string
    {
        $defaultOptions = [
            'prev_html' => '&lsaquo;',
            'next_html' => '&rsaquo;',
            'window_size' => 2
        ];
        $actualOptions = array_merge($defaultOptions, $options);

        $request = Asserted::notNull($this->requestStack->getCurrentRequest());
        $route = $request->attributes->get('_route');

        $total = $countable->count();
        $totalPages = $this->getTotalPages($total, $perPage);

        $params = array_merge($request->attributes->get('_route_params'), $request->query->all());

        $html = '<ul class="pagination">' . PHP_EOL;

        /* Render prev page */
        $cssClasses = ['page-item'];
        if ($page === 1) {
            $cssClasses[] = 'disabled';
        }
        $html .= $this->renderLink($page - 1, $actualOptions['prev_html'], $route, $params, $cssClasses, 'prev');

        $surroundingStartIdx = max(1, $page - $actualOptions['window_size']);
        $surroundingEndIdx = min($totalPages, $page + $actualOptions['window_size']);

        /* Render first page */
        if ($surroundingStartIdx > 1) {
            $html .= $this->renderLink(1, '1', $route, $params);
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
                $html .= $this->renderLink($i, (string)$i, $route, $params, $cssClasses);
            }
        }

        /* Render dots */
        if ($surroundingEndIdx < $totalPages - 1) {
            $html .= '<li class="page-item disabled"><a class="page-link" href="#">&hellip;</a></li>' . PHP_EOL;
        }

        /* Render last page */
        if ($surroundingEndIdx < $totalPages) {
            $html .= $this->renderLink($totalPages, (string)$totalPages, $route, $params);
        }

        /* Render next page */
        $cssClasses = ['page-item'];
        if ($page >= $totalPages) {
            $cssClasses[] = 'disabled';
        }
        $html .= $this->renderLink($page + 1, $actualOptions['next_html'], $route, $params, $cssClasses, 'next');

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
    ): string {
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

    public function getPath(string $name, array $parameters = [], bool $relative = false): string
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
