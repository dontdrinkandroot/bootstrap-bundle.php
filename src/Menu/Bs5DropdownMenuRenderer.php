<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\MenuItemExtra;
use Knp\Menu\ItemInterface;
use Knp\Menu\Renderer\Renderer;
use Knp\Menu\Renderer\RendererInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Bs5DropdownMenuRenderer extends Renderer implements RendererInterface
{
    /**
     * @deprecated Render divider as item with extra
     */
    final public const EXTRA_DIVIDER_PREPEND = 'divider_prepend';
    /**
     * @deprecated Render divider as item with extra
     */
    final public const EXTRA_DIVIDER_APPEND = 'divider_append';
    final public const EXTRA_DROPDOWN = 'dropdown';
    final public const EXTRA_ALIGN_END = 'align_end';

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
        if ((null !== $align = $item->getExtra(self::EXTRA_ALIGN_END)) && true === $align) {
            $dropdownMenuClasses[] = 'dropdown-menu-end';
        }
        $dropdownMenuAttributes['class'] = implode(' ', $dropdownMenuClasses);
        $html .= '<div' . $this->renderHtmlAttributes($dropdownMenuAttributes) . '>';
        foreach ($item->getChildren() as $child) {
            if (true === $child->getExtra(MenuItemExtra::DROPDOWN_HEADER)) {
                $html .= $this->renderHeaderItem($child, $options);
            } elseif (true === $child->getExtra(MenuItemExtra::DROPDOWN_DIVIDER)) {
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

        if (true === $item->getExtra(Bs5DropdownMenuRenderer::EXTRA_DIVIDER_PREPEND)) {
            $html .= '<div class="dropdown-divider"></div>';
        }

        $html .= '<a' . $this->renderHtmlAttributes($linkAttributes) . '>';
        if (null !== ($icon = $item->getExtra(MenuItemExtra::ICON))) {
            $html .= '<span ' . $this->renderHtmlAttribute('class', $icon) . '></span>';
        }
        $html .= $label;
        $html .= '</a>';

        if (true === $item->getExtra(Bs5DropdownMenuRenderer::EXTRA_DIVIDER_APPEND)) {
            $html .= '<div class="dropdown-divider"></div>';
        }

        return $html;
    }

    private function renderHeaderItem(ItemInterface $item, array $options = []): string
    {
        $classes = ['dropdown-header'];
        $attributes = ['class' => implode(' ', $classes)];

        $label = $this->getLabel($item);

        $html = '';

        if (true === $item->getExtra(Bs5DropdownMenuRenderer::EXTRA_DIVIDER_PREPEND)) {
            $html .= '<div class="dropdown-divider"></div>';
        }

        $html .= '<h6' . $this->renderHtmlAttributes($attributes) . '>';
        $html .= $label;
        $html .= '</h6>';

        if (true === $item->getExtra(Bs5DropdownMenuRenderer::EXTRA_DIVIDER_APPEND)) {
            $html .= '<div class="dropdown-divider"></div>';
        }

        return $html;
    }

    private function getLabel(ItemInterface $item): string
    {
        $translationDomain = $item->getExtra(MenuItemExtra::TRANSLATION_DOMAIN);
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
