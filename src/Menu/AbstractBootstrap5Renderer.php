<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\ItemExtra;
use Dontdrinkandroot\Common\StringUtils;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\Renderer\Renderer;
use Knp\Menu\Renderer\RendererInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use function str_repeat;

abstract class AbstractBootstrap5Renderer extends Renderer implements RendererInterface
{
    public function __construct(
        protected readonly MatcherInterface $matcher,
        protected readonly TranslatorInterface $translator,
        ?string $charset = null,
    ) {
        parent::__construct($charset);
    }

    protected function getLabel(ItemInterface $item): string
    {
        $label = $item->getLabel();
        if ('' === $label) {
            return '';
        }
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

    protected function renderClosingTag(string $tag, ?int $level = null): string
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

    protected function addIconBeforeIfDefined(ItemInterface $item, string &$html, int $level): void
    {
        if (
            null !== ($icon = $item->getExtra(ItemExtra::ICON))
            || null !== ($icon = $item->getExtra(ItemExtra::ICON_BEFORE))
        ) {
            $html .= $this->renderFullTag('span', ['class' => $icon], '', $level);
        }
    }

    protected function addIconAfterIfDefined(ItemInterface $item, string &$html, int $level): void
    {
        if (null !== ($icon = $item->getExtra(ItemExtra::ICON_AFTER))) {
            $html .= $this->renderFullTag('span', ['class' => $icon], '', $level);
        }
    }

    protected function renderItemLabelWithIcons(ItemInterface $item, int $level): string
    {
        $html = '';
        $this->addIconBeforeIfDefined($item, $html, $level);
        $label = $this->getLabel($item);
        if (!StringUtils::isEmpty($label)) {
            $html .= $this->renderFullTag('span', [], $label, $level);
        }
        $this->addIconAfterIfDefined($item, $html, $level);

        return $html;
    }

    /**
     * @return string[]
     */
    protected function explodeClassString(bool|string|null $class): array
    {
        if (null === $class || is_bool($class)) {
            return [];
        }

        return array_filter(explode(' ', $class));
    }

    /**
     * @param string[] $classes
     */
    protected function implodeClasses(array $classes): string
    {
        return implode(' ', array_unique($classes));
    }

    /**
     * @param string[]|string|bool|null $left
     * @param string[]|string|bool|null $right
     * @return string
     */
    protected function mergeClassesToString(array|string|bool|null $left, array|string|bool|null $right): string
    {
        $leftExploded = is_bool($left) ? [] : (is_array($left) ? $left : $this->explodeClassString($left));
        $rightExploded = is_bool($right) ? [] : (is_array($right) ? $right : $this->explodeClassString($right));

        return $this->implodeClasses(array_merge($leftExploded, $rightExploded));
    }

    protected function translateTitleIfSet(ItemInterface $item): void
    {
        if (
            is_string($title = $item->getAttribute('title'))
            && is_string($translationDomain = $item->getExtra(ItemExtra::TRANSLATION_DOMAIN))
        ) {
            $item->setAttribute('title', $this->translator->trans($title, [], $translationDomain));
        }
    }
}
