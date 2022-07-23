<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Dontdrinkandroot\Common\Asserted;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\Renderer\Renderer;
use Knp\Menu\Renderer\RendererInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Bs5NavbarRenderer extends Renderer implements RendererInterface
{
    public const EXTRA_DROPDOWN = 'dropdown';
    public const EXTRA_ALIGN_END = 'align_end';
    public const EXTRA_DIVIDER_PREPEND = 'divider_prepend';
    public const EXTRA_DIVIDER_APPEND = 'divider_append';
    public const EXTRA_HEADER = 'header';

    public function __construct(
        private MatcherInterface $matcher,
        private TranslatorInterface $translator,
        private array $defaultOptions = [],
        ?string $charset = null
    ) {
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
        if (true === $item->getExtra(self::EXTRA_DROPDOWN)) {
            return $this->renderDropdown($item, $options);
        }

        return $this->renderLinkItem($item, $options);
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
        if ((null !== $align = $item->getExtra(self::EXTRA_ALIGN_END)) && true === $align) {
            $dropdownMenuClasses[] = 'dropdown-menu-end';
        }
        $dropdownMenuAttributes['class'] = implode(' ', $dropdownMenuClasses);

        $html = '<li' . $this->renderHtmlAttributes($attributes) . '>';
        $html .= '<a' . $this->renderHtmlAttributes($toggleAttributes) . '>';
        $html .= $this->getLabel($item);
        $html .= '</a>';
        $html .= '<div' . $this->renderHtmlAttributes($dropdownMenuAttributes) . '>';
        foreach ($item->getChildren() as $child) {
            if (true === $child->getExtra(self::EXTRA_HEADER)) {
                $html .= $this->renderHeaderItem($child, $options);
            } else {
                $html .= $this->renderDropdownItem($child, $options);
            }
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
        if (null !== $uri = $item->getUri()) {
            $linkAttributes['href'] = $this->escape($uri);
        }
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
        if (null !== $uri = $item->getUri()) {
            $linkAttributes['href'] = $this->escape($uri);
        }
        $linkAttributes['class'] = implode(' ', $linkClasses);

        $label = $this->getLabel($item);

        $html = '';

        if (true === $item->getExtra(self::EXTRA_DIVIDER_PREPEND)) {
            $html .= '<div class="dropdown-divider"></div>';
        }

        $html .= '<a' . $this->renderHtmlAttributes($linkAttributes) . '>';
        $html .= $label;
        $html .= '</a>';

        if (true === $item->getExtra(self::EXTRA_DIVIDER_APPEND)) {
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

        if (true === $item->getExtra(self::EXTRA_DIVIDER_PREPEND)) {
            $html .= '<div class="dropdown-divider"></div>';
        }

        $html .= '<h6' . $this->renderHtmlAttributes($attributes) . '>';
        $html .= $label;
        $html .= '</h6>';

        if (true === $item->getExtra(self::EXTRA_DIVIDER_APPEND)) {
            $html .= '<div class="dropdown-divider"></div>';
        }

        return $html;
    }

    private function getLabel(ItemInterface $item): string
    {
        $translationDomain = $item->getExtra('translation_domain');
        if (false === $translationDomain) {
            $label = $item->getLabel();
        } else {
            $label = $this->translator->trans($item->getLabel(), [], $translationDomain);
        }

        return $this->escape($label);
    }

}
