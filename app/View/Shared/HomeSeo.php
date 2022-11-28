<?php

namespace App\View\Shared;

class HomeSeo
{
    public function __invoke(): array
    {
        return [
            'seoTitle' => 'Home',
            'seoUrl' => route('web.home'),
            'seoDescription' => 'Alyson Silva. Meu blog e site pessoal. Sou Engenheiro de Software Back-end. Apaixonado por compartilhar conhecimento e aprender coisas novas 🤓',
        ];
    }
}
