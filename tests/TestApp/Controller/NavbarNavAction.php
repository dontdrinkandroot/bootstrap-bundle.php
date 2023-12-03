<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class NavbarNavAction extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        /** @var Environment $twig */
        $twig = $this->container->get('twig');
        return new Response(
            $twig->createTemplate(
                <<<EOF
{% extends '@DdrBootstrap/html5.html.twig' %}

{% block body %}
    {{ knp_menu_render('ddr.bootstrap.test.navbar_nav', {}, 'ddr_bootstrap5_navbar_nav') }}
{% endblock body %}
EOF
            )->render()
        );
    }
}
