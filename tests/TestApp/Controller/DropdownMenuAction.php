<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class DropdownMenuAction extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        /** @var Environment $twig */
        $twig = $this->container->get('twig');
        return new Response(
            $twig->createTemplate(
                "{{ knp_menu_render('ddr.bootstrap.test.dropdown', {}, 'ddr_bootstrap5_dropdown_menu') }}"
            )->render()
        );
    }
}
