<?php

namespace Dontdrinkandroot\BootstrapBundle\Model;

enum ButtonVariant: string
{
    case PRIMARY = 'btn-primary';
    case SECONDARY = 'btn-secondary';
    case SUCCESS = 'btn-success';
    case DANGER = 'btn-danger';
    case WARNING = 'btn-warning';
    case INFO = 'btn-info';
    case LIGHT = 'btn-light';
    case DARK = 'btn-dark';

    case OUTLINE_PRIMARY = 'btn-outline-primary';
    case OUTLINE_SECONDARY = 'btn-outline-secondary';
    case OUTLINE_SUCCESS = 'btn-outline-success';
    case OUTLINE_DANGER = 'btn-outline-danger';
    case OUTLINE_WARNING = 'btn-outline-warning';
    case OUTLINE_INFO = 'btn-outline-info';
    case OUTLINE_LIGHT = 'btn-outline-light';
    case OUTLINE_DARK = 'btn-outline-dark';

    case LINK = 'btn-link';
}
