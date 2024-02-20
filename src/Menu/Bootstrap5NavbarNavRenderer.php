<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Override;
use Symfony\Contracts\Translation\TranslatorInterface;

class Bootstrap5NavbarNavRenderer extends AbstractBootstrap5Renderer
{
    public function __construct(
        MatcherInterface $matcher,
        TranslatorInterface $translator,
        private readonly Bootstrap5DropdownMenuRenderer $dropdownMenuRenderer,
        ?string $charset = null
    ) {
        parent::__construct($matcher, $translator, $charset);
    }

    #[Override]
    public function render(ItemInterface $item, array $options = []): string
    {
        $classes = array_merge(['navbar-nav'], $options['attributes']['classes'] ?? []);
        if (is_string($attributeClass = $item->getAttribute('class'))) {
            $classes = array_merge($classes, explode(' ', $attributeClass));
        }
        $attributes = $item->getAttributes();
        $attributes['class'] = implode(' ', $classes);

        $html = $this->renderOpeningTag('ul', $attributes, $item->getLevel());
        foreach ($item->getChildren() as $child) {
            $html .= $this->renderItem($child, $options);
        }
        $html .= $this->renderClosingTag('ul', $item->getLevel());

        return $html;
    }

    private function renderItem(ItemInterface $item, array $options = []): string
    {
        if ($item->hasChildren()) {
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

        $html = $this->renderOpeningTag('li', $attributes, $item->getLevel());
        $html .= $this->renderOpeningTag('a', $toggleAttributes, $item->getLevel() + 1);
        $html .= $this->renderItemLabelWithIcons($item, $item->getLevel() + 2);
        $html .= $this->renderClosingTag('a', $item->getLevel() + 1);

        $html .= $this->dropdownMenuRenderer->render($item);
        $html .= $this->renderClosingTag('li', $item->getLevel());

        return $html;
    }

    private function renderLinkItem(ItemInterface $item, array $options = []): string
    {
        $classes = ['nav-item'];

        $attributes = $item->getAttributes();
        $attributes['class'] = implode(' ', $classes);

        $linkAttributes = $item->getLinkAttributes();
        if (null !== $uri = $item->getUri()) {
            $linkAttributes['href'] = $this->escape($uri);
        }

        $linkClasses = 'nav-link';
        if ($this->matcher->isCurrent($item)) {
            $linkClasses .= ' active';
        }
        if (array_key_exists('class', $linkAttributes)) {
            $linkClasses .= ' ' . $linkAttributes['class'];
        }

        $linkAttributes['class'] = $linkClasses;

        $html = $this->renderOpeningTag('li', $attributes, $item->getLevel());
        $html .= $this->renderOpeningTag('a', $linkAttributes, $item->getLevel() + 1);
        $html .= $this->renderItemLabelWithIcons($item, $item->getLevel() + 2);
        $html .= $this->renderClosingTag('a', $item->getLevel() + 1);
        $html .= $this->renderClosingTag('li', $item->getLevel());

        return $html;
    }
}
