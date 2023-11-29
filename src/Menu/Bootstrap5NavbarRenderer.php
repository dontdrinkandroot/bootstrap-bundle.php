<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\ItemExtra;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\Renderer\Renderer;
use Knp\Menu\Renderer\RendererInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Bootstrap5NavbarRenderer extends Renderer implements RendererInterface
{
    public function __construct(
        private readonly MatcherInterface $matcher,
        private readonly TranslatorInterface $translator,
        private readonly Bootstrap5DropdownMenuRenderer $dropdownMenuRenderer,
        private readonly array $defaultOptions = [],
        ?string $charset = null,
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
        if (true === $item->getExtra(ItemExtra::DROPDOWN)) {
            return $this->renderDropdown($item, $options);
        }

        return $this->renderLinkItem($item, $options);
    }

    private function renderDropdown(ItemInterface $item, array $options = []): string
    {
        $classes = ['nav-item', 'dropdown'];
        $toggleClasses = ['nav-link', 'dropdown-toggle'];

        $attributes = $item->getAttributes();
        $attributes['class'] = implode(' ', $classes);

        $toggleAttributes = $item->getLinkAttributes();
        $toggleAttributes['class'] = implode(' ', $toggleClasses);
        $toggleAttributes['href'] = '#';
        $toggleAttributes['data-bs-toggle'] = 'dropdown';
        $toggleAttributes['aria-haspopup'] = 'true';
        $toggleAttributes['aria-expanded'] = 'false';

        $html = '<li' . $this->renderHtmlAttributes($attributes) . '>';
        $html .= '<a' . $this->renderHtmlAttributes($toggleAttributes) . '>';
        if (null !== ($icon = $item->getExtra(ItemExtra::ICON))) {
            $html .= '<span ' . $this->renderHtmlAttribute('class', $icon) . '></span>';
        }
        $html .= $this->getLabel($item);
        $html .= '</a>';
        $html .= $this->dropdownMenuRenderer->render($item);
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
