<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Documentacao extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static string $view = 'filament.pages.documentacao';

    protected static ?string $navigationLabel = 'Documentação';

    protected static ?string $title = 'Central de Documentação';

    protected static ?string $slug = 'documentacao';

    // Remover do menu lateral
    protected static bool $shouldRegisterNavigation = false;

    public string $activeSection = 'inicio';

    public string $activeCategory = 'geral';

    public function mount(): void
    {
        $this->activeCategory = request()->query('category', 'geral');
        $this->activeSection = request()->query('section', 'inicio');
    }

    public function changeSection(string $category, string $section): void
    {
        $this->activeCategory = $category;
        $this->activeSection = $section;
    }

    public function getCategories(): array
    {
        return [
            'geral' => [
                'title' => '📚 Geral',
                'icon' => 'heroicon-o-book-open',
                'sections' => [
                    'inicio' => [
                        'title' => '🏠 Início',
                        'icon' => 'heroicon-o-home',
                    ],
                    'visao-geral' => [
                        'title' => '📊 Visão Geral do Sistema',
                        'icon' => 'heroicon-o-chart-bar',
                    ],
                    'primeiros-passos' => [
                        'title' => '🚀 Primeiros Passos',
                        'icon' => 'heroicon-o-rocket-launch',
                    ],
                ],
            ],
            'associados' => [
                'title' => '👥 Associados',
                'icon' => 'heroicon-o-users',
                'sections' => [
                    'cadastro' => [
                        'title' => '➕ Cadastro de Associados',
                        'icon' => 'heroicon-o-user-plus',
                    ],
                    'filtros' => [
                        'title' => '🔍 Filtros de Associados',
                        'icon' => 'heroicon-o-funnel',
                    ],
                    'carteirinhas' => [
                        'title' => '🎫 Carteirinhas',
                        'icon' => 'heroicon-o-identification',
                    ],
                    'beneficios' => [
                        'title' => '🎁 Benefícios',
                        'icon' => 'heroicon-o-gift',
                    ],
                ],
            ],
            'atendimentos' => [
                'title' => '📋 Atendimentos',
                'icon' => 'heroicon-o-clipboard-document-list',
                'sections' => [
                    'criar-atendimento' => [
                        'title' => '➕ Criar Atendimento',
                        'icon' => 'heroicon-o-plus-circle',
                    ],
                    'tipos-atendimento' => [
                        'title' => '📑 Tipos de Atendimento',
                        'icon' => 'heroicon-o-document-text',
                    ],
                ],
            ],
            'vagas' => [
                'title' => '💼 Vagas e Talentos',
                'icon' => 'heroicon-o-briefcase',
                'sections' => [
                    'cadastro-vagas' => [
                        'title' => '➕ Cadastro de Vagas',
                        'icon' => 'heroicon-o-document-plus',
                    ],
                    'talentos' => [
                        'title' => '⭐ Gestão de Talentos',
                        'icon' => 'heroicon-o-star',
                    ],
                    'encaminhamentos' => [
                        'title' => '🔄 Encaminhamentos',
                        'icon' => 'heroicon-o-arrow-path',
                    ],
                ],
            ],
            'relatorios' => [
                'title' => '📊 Relatórios',
                'icon' => 'heroicon-o-chart-pie',
                'sections' => [
                    'dashboard' => [
                        'title' => '📈 Dashboard',
                        'icon' => 'heroicon-o-presentation-chart-line',
                    ],
                    'exportacao' => [
                        'title' => '💾 Exportação de Dados',
                        'icon' => 'heroicon-o-arrow-down-tray',
                    ],
                ],
            ],
            'ajuda' => [
                'title' => '❓ Ajuda',
                'icon' => 'heroicon-o-question-mark-circle',
                'sections' => [
                    'faq' => [
                        'title' => '❓ Perguntas Frequentes',
                        'icon' => 'heroicon-o-chat-bubble-bottom-center-text',
                    ],
                    'troubleshooting' => [
                        'title' => '🔧 Solução de Problemas',
                        'icon' => 'heroicon-o-wrench-screwdriver',
                    ],
                    'suporte' => [
                        'title' => '💬 Contato e Suporte',
                        'icon' => 'heroicon-o-phone',
                    ],
                ],
            ],
        ];
    }

    public function getSectionContent(string $category, string $section): string
    {
        $viewPath = "docs.sections.{$category}.{$section}";

        if (view()->exists($viewPath)) {
            return view($viewPath)->render();
        }

        return view('docs.sections.em-construcao', [
            'category' => $category,
            'section' => $section,
        ])->render();
    }
}
