<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\ItemExtra;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Override;
use Symfony\Contracts\Translation\TranslatorInterface;

class Bootstrap5DropdownMenuRenderer extends AbstractBootstrap5Renderer
{
    public function __construct(MatcherInterface $matcher, TranslatorInterface $translator, ?string $charset = null)
    {
        parent::__construct($matcher, $translator, $charset);
    }

    #[Override]
    public function render(ItemInterface $item, array $options = []): string
    {
        $dropdownMenuAttributes = [];
        $dropdownMenuClasses = ['dropdown-menu'];
        if ((null !== $align = $item->getExtra(ItemExtra::ALIGN_END)) && true === $align) {
            $dropdownMenuClasses[] = 'dropdown-menu-end';
        }
        $dropdownMenuAttributes['class'] = implode(' ', $dropdownMenuClasses);
        $html = $this->renderOpeningTag('ul', $dropdownMenuAttributes, $item->getLevel());
        foreach ($item->getChildren() as $child) {
            if (true === $child->getExtra(ItemExtra::DROPDOWN_HEADER)) {
                $html .= $this->renderHeaderItem($child, $options);
            } elseif (true === $child->getExtra(ItemExtra::DROPDOWN_DIVIDER)) {
                $html .= $this->renderDivider($child, $options);
            } else {
                $html .= $this->renderDropdownItem($child, $options);
            }
        }
        $html .= $this->renderClosingTag('ul', $item->getLevel());

        return $html;
    }

    private function renderDropdownItem(ItemInterface $item, array $options = []): string
    {
        $html = $this->renderOpeningTag('li', [], $item->getLevel());

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

        $html .= $this->renderOpeningTag('a', $linkAttributes, $item->getLevel() + 1);
        $html .= $this->renderItemLabelWithIcons($item, $item->getLevel() + 2);
        $html .= $this->renderClosingTag('a', $item->getLevel() + 1);

        $html .= $this->renderClosingTag('li', $item->getLevel());

        return $html;
    }

    private function renderHeaderItem(ItemInterface $item, array $options = []): string
    {
        $classes = ['dropdown-header'];
        $attributes = ['class' => implode(' ', $classes)];

        $html = $this->renderOpeningTag('li', [], $item->getLevel());
        $html .= $this->renderFullTag('h6', $attributes, $this->getLabel($item), $item->getLevel() + 1);
        $html .= $this->renderClosingTag('li', $item->getLevel());

        return $html;
    }

    private function renderDivider(ItemInterface $item, array $options): string
    {
        $html = $this->renderOpeningTag('li', [], $item->getLevel());
        $html .= $this->renderFullTag('hr', ['class' => 'dropdown-divider'], '', $item->getLevel() + 1);
        $html .= $this->renderClosingTag('li', $item->getLevel());

        return $html;
    }
}
