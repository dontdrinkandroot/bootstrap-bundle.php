<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Controller;

use Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Model\RouteName;
use Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Model\RoutePath;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[Route(path: RoutePath::BUTTON_GROUP, name: RouteName::BUTTON_GROUP)]
class ButtonGroupAction extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        /** @var Environment $twig */
        $twig = $this->container->get('twig');
        return new Response(
            $twig->createTemplate(
                "{{ knp_menu_render('ddr.bootstrap.test.button_group', {}, 'ddr_bootstrap5_button_group') }}"
            )->render()
        );
    }
}
