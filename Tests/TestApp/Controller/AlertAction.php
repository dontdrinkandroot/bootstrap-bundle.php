<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Environment;

class AlertAction extends AbstractController
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
