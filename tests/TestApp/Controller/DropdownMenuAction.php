<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Controller;

use Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Model\RouteName;
use Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Model\RoutePath;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[Route(path: RoutePath::DROPDOWN_MENU, name: RouteName::DROPDOWN_MENU)]
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
