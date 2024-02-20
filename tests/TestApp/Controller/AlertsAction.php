<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Controller;

use Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Model\RouteName;
use Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Model\RoutePath;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

#[Route(path: RoutePath::ALERTS, name: RouteName::ALERTS)]
class AlertsAction extends AbstractController
{
    public function __invoke(Session $session): Response
    {
        $session->getFlashBag()->add('info', 'Info Alert');
        $session->getFlashBag()->add('success', 'Success Alert');
        $session->getFlashBag()->add('error', 'Error Alert');

        /** @var Environment $twig */
        $twig = $this->container->get('twig');
        return new Response(
            $twig->createTemplate(
                <<<EOF
{% extends '@DdrBootstrap/html5.html.twig' %}

{% block body %}
    {% include '@DdrBootstrap/Alert/flash.partial.html.twig' %}
{% endblock body %}
EOF
            )->render()
        );
    }
}
