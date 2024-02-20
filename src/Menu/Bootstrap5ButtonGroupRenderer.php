<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Override;
use Symfony\Contracts\Translation\TranslatorInterface;

class Bootstrap5ButtonGroupRenderer extends AbstractBootstrap5Renderer
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
        $buttonGroupAttributes = [
            'class' => 'btn-group',
            'role' => 'group',
            'aria-label' => $this->getLabel($item),
        ];
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
            $attributes = $item->getAttributes();
            $attributes['class'] = $this->mergeClassesToString(['btn', 'dropdown-toggle'],
                $attributes['class'] ?? null);
            $attributes['data-bs-toggle'] = 'dropdown';
            $attributes['aria-expanded'] = 'false';

            $html = $this->renderOpeningTag('button', $attributes, $item->getLevel());
            $html .= $this->renderItemLabelWithIcons($item, $item->getLevel() + 1);
            $html .= $this->renderClosingTag('button', $item->getLevel());

            $html .= $this->dropdownMenuRenderer->render($item, $options);
        } else {
            $attributes = $item->getAttributes();
            $attributes['type'] = 'button';
            if (null !== $uri = $item->getUri()) {
                $attributes['href'] = $this->escape($uri);
            }
            $attributes['class'] = $this->mergeClassesToString(['btn'], $attributes['class'] ?? null);

            $html = $this->renderOpeningTag('a', $attributes, $item->getLevel());
            $html .= $this->renderItemLabelWithIcons($item, $item->getLevel() + 1);
            $html .= $this->renderClosingTag('a', $item->getLevel());
        }

        return $html;
    }
}
