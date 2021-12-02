<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\Renderer\Renderer;
use Knp\Menu\Renderer\RendererInterface;

class Bs5NavbarRenderer extends Renderer implements RendererInterface
{
    public const ALIGN_END = 'align_end';
    const DROPDOWN = 'dropdown';

    private MatcherInterface $matcher;

    public array $defaultOptions;

    public function __construct(MatcherInterface $matcher, array $defaultOptions = [], $charset = null)
    {
        $this->matcher = $matcher;
        $this->defaultOptions = $defaultOptions;

        parent::__construct($charset);
    }

    /**
     * {@inheritdoc}
     */
    public function render(ItemInterface $item, array $options = []): string
    {
        $classes = ['navbar-nav'];
        if (array_key_exists('attributes', $options) && array_key_exists('classes', $options['attributes'])) {
            $classes = array_merge($classes, $options['attributes']['classes']);
        }

        $attributes = $item->getAttributes();
        if (!empty($classes)) {
            $attributes['class'] = implode(' ', $classes);
        }

        $html = '<ul' . $this->renderHtmlAttributes($attributes) . '>';
        foreach ($item->getChildren() as $child) {
            $html .= $this->renderItem($child, $options);
        }
        $html .= '</ul>';

        return $html;
    }

    private function renderItem(ItemInterface $item, array $options = []): string
    {
        if (true === $item->getExtra(self::DROPDOWN)) {
            return $this->renderDropdown($item, $options);
        } else {
            return $this->renderLinkItem($item, $options);
        }
    }

    private function renderDropdown(ItemInterface $item, array $options = []): string
    {
        $classes = ['nav-item', 'dropdown'];
        $toggleClasses = ['nav-link', 'dropdown-toggle'];
        $dropdownMenuClasses = ['dropdown-menu'];

        $attributes = $item->getAttributes();
        $attributes['class'] = implode(' ', $classes);

        $toggleAttributes = $item->getLinkAttributes();
        $toggleAttributes['class'] = implode(' ', $toggleClasses);
        $toggleAttributes['href'] = '#';
        $toggleAttributes['data-bs-toggle'] = 'dropdown';
        $toggleAttributes['aria-haspopup'] = 'true';
        $toggleAttributes['aria-expanded'] = 'false';

        $dropdownMenuAttributes = [];
        if (null !== $align = $item->getExtra(self::ALIGN_END)) {
            if (true === $align) {
                $dropdownMenuClasses[] = 'dropdown-menu-end';
            }
        }
        $dropdownMenuAttributes['class'] = implode(' ', $dropdownMenuClasses);

        $html = '<li' . $this->renderHtmlAttributes($attributes) . '>';
        $html .= '<a' . $this->renderHtmlAttributes($toggleAttributes) . '>';
        $html .= $this->getLabel($item);
        $html .= '</a>';
        $html .= '<div' . $this->renderHtmlAttributes($dropdownMenuAttributes) . '>';
        foreach ($item->getChildren() as $child) {
            $html .= $this->renderDropdownItem($child, $options);
        }
        $html .= '</div>';

        $html .= '</li>';

        return $html;
    }

    private function renderLinkItem(ItemInterface $item, array $options = []): string
    {
        $classes = ['nav-item'];

        $attributes = $item->getAttributes();
        $attributes['class'] = implode(' ', $classes);

        $linkClasses = ['nav-link'];
        if ($this->matcher->isCurrent($item)) {
            $linkClasses[] = 'active';
        }

        $linkAttributes = $item->getLinkAttributes();
        $linkAttributes['href'] = $this->escape($item->getUri());
        $linkAttributes['class'] = implode(' ', $linkClasses);

        $label = $this->getLabel($item);

        $html = '<li' . $this->renderHtmlAttributes($attributes) . '>';
        $html .= '<a' . $this->renderHtmlAttributes($linkAttributes) . '>';
        $html .= $label;
        $html .= '</a>';
        $html .= '</li>';

        return $html;
    }

    private function renderDropdownItem(ItemInterface $item, array $options = []): string
    {
        $linkClasses = ['dropdown-item'];

        $linkAttributes = $item->getLinkAttributes();
        $linkAttributes['href'] = $this->escape($item->getUri());
        $linkAttributes['class'] = implode(' ', $linkClasses);

        $label = $this->getLabel($item);

        $html = '';

        if (true === $item->getExtra('divider_prepend')) {
            $html .= '<div class="dropdown-divider"></div>';
        }

        $html .= '<a' . $this->renderHtmlAttributes($linkAttributes) . '>';
        $html .= $label;
        $html .= '</a>';

        if (true === $item->getExtra('divider_append')) {
            $html .= '<div class="dropdown-divider"></div>';
        }

        return $html;
    }

    private function getLabel(ItemInterface $item): string
    {
        $label = $item->getLabel();
        if (null === $label) {
            $label = $item->getName();
        }

        return $this->escape($label);
    }

}
