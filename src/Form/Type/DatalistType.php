<?php

namespace Dontdrinkandroot\BootstrapBundle\Form\Type;

use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatalistType extends AbstractType
{
    #[Override]
    public function getParent(): string
    {
        return TextType::class;
    }

    #[Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['choices']);
    }

    #[Override]
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['choices'] = $options['choices'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'datalist';
    }
}
