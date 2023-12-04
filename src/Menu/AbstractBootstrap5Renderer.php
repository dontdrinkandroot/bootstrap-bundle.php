<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\ItemExtra;
use Knp\Menu\ItemInterface;
use Knp\Menu\Renderer\Renderer;
use Knp\Menu\Renderer\RendererInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use function str_repeat;

abstract class AbstractBootstrap5Renderer extends Renderer implements RendererInterface
{
    public function __construct(
        protected readonly TranslatorInterface $translator,
        ?string $charset = null,
    ) {
        parent::__construct($charset);
    }

    protected function getLabel(ItemInterface $item): string
    {
        $label = $item->getLabel();
        $label = (false === ($translationDomain = $item->getExtra(ItemExtra::TRANSLATION_DOMAIN))
            ? $label
            : $this->translator->trans($label, [], $translationDomain));

        return $this->escape($label);
    }

    protected function renderOpeningTag(string $tag, array $attributes, ?int $level = null): string
    {
        return sprintf(
            '%s<%s%s>%s',
            $this->indent($level),
            $tag,
            $this->renderHtmlAttributes($attributes),
            null !== $level ? "\n" : ''
        );
    }

    protected function renderClosingTag(string $tag, int $level): string
    {
        return sprintf(
            '%s</%s>%s',
            $this->indent($level),
            $tag,
            "\n"
        );
    }

    protected function renderFullTag(string $tag, array $attributes, string $content, ?int $level = null): string
    {
        $selfClosingTags = [
            'area',
            'base',
            'br',
            'col',
            'embed',
            'hr',
            'img',
            'input',
            'link',
            'meta',
            'param',
            'source',
            'track',
            'wbr'
        ];
        if (in_array($tag, $selfClosingTags)) {
            return sprintf(
                '%s<%s%s />%s',
                $this->indent($level),
                $tag,
                $this->renderHtmlAttributes($attributes),
                null !== $level ? "\n" : ''
            );
        }

        return sprintf(
            '%s<%s%s>%s</%s>%s',
            $this->indent($level),
            $tag,
            $this->renderHtmlAttributes($attributes),
            $content,
            $tag,
            null !== $level ? "\n" : ''
        );
    }

    protected function indent(?int $level = null): string
    {
        return (null === $level)
            ? ''
            : str_repeat(' ', $level * 4);
    }

    protected function addIconBeforeIfDefined(ItemInterface $item, string &$html): void
    {
        if (
            null !== ($icon = $item->getExtra(ItemExtra::ICON))
            || null !== ($icon = $item->getExtra(ItemExtra::ICON_BEFORE))
        ) {
            $html .= $this->renderFullTag('span', ['class' => $icon], '', $item->getLevel() + 2);
        }
    }

    protected function addIconAfterIfDefined(ItemInterface $item, string &$html): void
    {
        if (null !== ($icon = $item->getExtra(ItemExtra::ICON_AFTER))) {
            $html .= $this->renderFullTag('span', ['class' => $icon], '', $item->getLevel() + 2);
        }
    }
}
