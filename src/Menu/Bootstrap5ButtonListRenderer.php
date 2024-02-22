<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Override;
use Symfony\Contracts\Translation\TranslatorInterface;

class Bootstrap5ButtonListRenderer extends AbstractBootstrap5Renderer
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
        $buttonGroupAttributes = $item->getAttributes();

        $html = $this->renderOpeningTag('div', $buttonGroupAttributes, $item->getLevel());
        foreach ($item->getChildren() as $child) {
            $html .= $this->renderButton($child, $options);
        }
        $html .= $this->renderClosingTag('div', $item->getLevel());

        return $html;
    }

    private function renderButton(ItemInterface $item, array $options): string
    {
        if ($item->hasChildren()) {
            $item->setAttribute(
                'class',
                $this->mergeClassesToString(['btn', 'dropdown-toggle'], $item->getAttribute('class'))
            );
            $item->setAttribute('data-bs-toggle', 'dropdown');
            $item->setAttribute('aria-expanded', 'false');
            $this->translateTitleIfSet($item);

            $html = $this->renderOpeningTag('button', $item->getAttributes(), $item->getLevel());
            $html .= $this->renderItemLabelWithIcons($item, $item->getLevel() + 1);
            $html .= $this->renderClosingTag('button', $item->getLevel());

            $html .= $this->dropdownMenuRenderer->render($item, $options);
        } else {
            $item->setAttribute('type', 'button');
            if (null !== $uri = $item->getUri()) {
                $item->setAttribute('href', $this->escape($uri));
            }
            $item->setAttribute('class', $this->mergeClassesToString(['btn'], $item->getAttribute('class')));
            $this->translateTitleIfSet($item);

            $html = $this->renderOpeningTag('a', $item->getAttributes(), $item->getLevel());
            $html .= $this->renderItemLabelWithIcons($item, $item->getLevel() + 1);
            $html .= $this->renderClosingTag('a', $item->getLevel());
        }

        return $html;
    }
}
