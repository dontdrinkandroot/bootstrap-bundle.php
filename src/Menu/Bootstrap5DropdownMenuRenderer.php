<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\ItemExtra;
use Knp\Menu\ItemInterface;
use Knp\Menu\Renderer\Renderer;
use Knp\Menu\Renderer\RendererInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Bootstrap5DropdownMenuRenderer extends Renderer implements RendererInterface
{
    public function __construct(private readonly TranslatorInterface $translator, ?string $charset = null)
    {
        parent::__construct($charset);
    }

    /**
     * {@inheritdoc}
     */
    public function render(ItemInterface $item, array $options = []): string
    {
        $html = '';
        $dropdownMenuAttributes = [];
        $dropdownMenuClasses = ['dropdown-menu'];
        if ((null !== $align = $item->getExtra(ItemExtra::ALIGN_END)) && true === $align) {
            $dropdownMenuClasses[] = 'dropdown-menu-end';
        }
        $dropdownMenuAttributes['class'] = implode(' ', $dropdownMenuClasses);
        $html .= '<div' . $this->renderHtmlAttributes($dropdownMenuAttributes) . '>';
        foreach ($item->getChildren() as $child) {
            if (true === $child->getExtra(ItemExtra::DROPDOWN_HEADER)) {
                $html .= $this->renderHeaderItem($child, $options);
            } elseif (true === $child->getExtra(ItemExtra::DROPDOWN_DIVIDER)) {
                $html .= $this->renderDivider($child, $options);
            } else {
                $html .= $this->renderDropdownItem($child, $options);
            }
        }
        $html .= '</div>';

        return $html;
    }

    private function renderDropdownItem(ItemInterface $item, array $options = []): string
    {
        $cssClasses = 'dropdown-item';

        $attributes = $item->getAttributes();
        if (array_key_exists('class', $attributes)) {
            $cssClasses .= ' ' . $attributes['class'];
        }

        $linkAttributes = $item->getLinkAttributes();
        if (array_key_exists('class', $linkAttributes)) {
            $cssClasses .= ' ' . $linkAttributes['class'];
        }

        $linkAttributes['class'] = $cssClasses;

        if (null !== $uri = $item->getUri()) {
            $linkAttributes['href'] = $this->escape($uri);
        }

        $label = $this->getLabel($item);

        $html = '';

        $html .= '<a' . $this->renderHtmlAttributes($linkAttributes) . '>';
        if (null !== ($icon = $item->getExtra(ItemExtra::ICON))) {
            $html .= '<span ' . $this->renderHtmlAttribute('class', $icon) . '></span>';
        }
        $html .= $label;
        $html .= '</a>';

        return $html;
    }

    private function renderHeaderItem(ItemInterface $item, array $options = []): string
    {
        $classes = ['dropdown-header'];
        $attributes = ['class' => implode(' ', $classes)];

        $label = $this->getLabel($item);

        $html = '';

        $html .= '<h6' . $this->renderHtmlAttributes($attributes) . '>';
        $html .= $label;
        $html .= '</h6>';

        return $html;
    }

    private function getLabel(ItemInterface $item): string
    {
        $translationDomain = $item->getExtra(ItemExtra::TRANSLATION_DOMAIN);
        if (false === $translationDomain) {
            $label = $item->getLabel();
        } else {
            $label = $this->translator->trans($item->getLabel(), [], $translationDomain);
        }

        return $this->escape($label);
    }

    private function renderDivider(ItemInterface $child, array $options): string
    {
        return '<div class="dropdown-divider"></div>';
    }
}
