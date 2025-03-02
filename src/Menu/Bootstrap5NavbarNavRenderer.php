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

    /**
     * @param array<string,mixed> $options
     */
    private function renderItem(ItemInterface $item, array $options = []): string
    {
        if ($item->hasChildren()) {
            return $this->renderDropdown($item, $options);
        }

        return $this->renderLinkItem($item, $options);
    }

    /**
     * @param array<string,mixed> $options
     */
    private function renderDropdown(ItemInterface $item, array $options = []): string
    {
        $item->setAttribute(
            'class',
            $this->mergeClassesToString(['nav-item', 'dropdown'], $item->getAttribute('class'))
        );

        $toggleAttributes = $item->getLinkAttributes();
        $toggleAttributes['class'] = $this->implodeClasses(['nav-link', 'dropdown-toggle']);
        $toggleAttributes['href'] = '#';
        $toggleAttributes['data-bs-toggle'] = 'dropdown';
        $toggleAttributes['aria-haspopup'] = 'true';
        $toggleAttributes['aria-expanded'] = 'false';

        $html = $this->renderOpeningTag('li', $item->getAttributes(), $item->getLevel());
        $html .= $this->renderOpeningTag('a', $toggleAttributes, $item->getLevel() + 1);
        $html .= $this->renderItemLabelWithIcons($item, $item->getLevel() + 2);
        $html .= $this->renderClosingTag('a', $item->getLevel() + 1);

        $html .= $this->dropdownMenuRenderer->render($item);
        $html .= $this->renderClosingTag('li', $item->getLevel());

        return $html;
    }

    /**
     * @param array<string,mixed> $options
     */
    private function renderLinkItem(ItemInterface $item, array $options = []): string
    {
        $item->setAttribute(
            'class',
            $this->mergeClassesToString(
                'nav-item',
                $item->getAttribute('class')
            )
        );

        if (null !== $uri = $item->getUri()) {
            $item->setLinkAttribute('href', $this->escape($uri));
        }

        $additionalLinkClasses = ['nav-link'];
        if ($this->matcher->isCurrent($item)) {
            $additionalLinkClasses[] = 'active';
        }

        $item->setLinkAttribute(
            'class',
            $this->mergeClassesToString($additionalLinkClasses, $item->getLinkAttribute('class'))
        );

        $html = $this->renderOpeningTag('li', $item->getAttributes(), $item->getLevel());
        $html .= $this->renderOpeningTag('a', $item->getLinkAttributes(), $item->getLevel() + 1);
        $html .= $this->renderItemLabelWithIcons($item, $item->getLevel() + 2);
        $html .= $this->renderClosingTag('a', $item->getLevel() + 1);
        $html .= $this->renderClosingTag('li', $item->getLevel());

        return $html;
    }
}
