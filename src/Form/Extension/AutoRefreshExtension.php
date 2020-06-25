<?php

declare(strict_types=1);

namespace App\Form\Extension;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class AutoRefreshExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        yield FormType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if ($options['auto_submit']) {
            $view->vars['attr']['data-auto-submit'] = true;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'auto_submit' => false,
        ]);
        $resolver->setAllowedTypes('auto_submit', 'bool');
    }
}
