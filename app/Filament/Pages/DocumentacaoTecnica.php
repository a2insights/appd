<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

class DocumentacaoTecnica extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';

    protected static string $view = 'filament.pages.documentacao-tecnica';

    protected static ?string $navigationLabel = 'Docs Técnicas';

    protected static ?string $title = 'Documentação Técnica';

    protected static ?string $slug = 'docs-tecnicas';

    protected static bool $shouldRegisterNavigation = false;

    public string $activeFile = '';
    public string $activeCategory = 'filtros';

    public function mount(): void
    {
        $this->activeCategory = request()->query('category', 'filtros');
        $this->activeFile = request()->query('file', '');
        
        if (empty($this->activeFile)) {
            $files = $this->getTechnicalFiles();
            if (!empty($files[$this->activeCategory])) {
                $this->activeFile = array_key_first($files[$this->activeCategory]);
            }
        }
    }

    public function changeFile(string $category, string $file): void
    {
        $this->activeCategory = $category;
        $this->activeFile = $file;
    }

    public function getTechnicalFiles(): array
    {
        $technicalPath = base_path('docs/technical');
        
        if (!File::exists($technicalPath)) {
            File::makeDirectory($technicalPath, 0755, true);
            return [];
        }

        $files = File::files($technicalPath);
        $organized = [
            'filtros' => [],
            'api' => [],
            'database' => [],
            'deployment' => [],
            'outros' => [],
        ];

        foreach ($files as $file) {
            if ($file->getExtension() !== 'md') {
                continue;
            }

            $filename = $file->getFilename();
            $basename = $file->getBasename('.md');
            
            // Categorizar por nome do arquivo
            if (str_contains($filename, 'filtro')) {
                $organized['filtros'][$basename] = $this->formatTitle($basename);
            } elseif (str_contains($filename, 'api')) {
                $organized['api'][$basename] = $this->formatTitle($basename);
            } elseif (str_contains($filename, 'database') || str_contains($filename, 'migration')) {
                $organized['database'][$basename] = $this->formatTitle($basename);
            } elseif (str_contains($filename, 'deploy') || str_contains($filename, 'install')) {
                $organized['deployment'][$basename] = $this->formatTitle($basename);
            } else {
                $organized['outros'][$basename] = $this->formatTitle($basename);
            }
        }

        return array_filter($organized, fn($category) => !empty($category));
    }

    protected function formatTitle(string $filename): string
    {
        $title = str_replace(['-', '_'], ' ', $filename);
        return ucwords($title);
    }

    public function getFileContent(string $filename): string
    {
        $filePath = base_path("docs/technical/{$filename}.md");
        
        if (!File::exists($filePath)) {
            return '<div class="text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">Documento não encontrado.</p>
            </div>';
        }

        $markdown = File::get($filePath);
        
        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new TableExtension());

        $converter = new MarkdownConverter($environment);
        
        return $converter->convert($markdown)->getContent();
    }

    public function getCategoryIcon(string $category): string
    {
        return match($category) {
            'filtros' => 'heroicon-o-funnel',
            'api' => 'heroicon-o-code-bracket-square',
            'database' => 'heroicon-o-circle-stack',
            'deployment' => 'heroicon-o-server',
            'outros' => 'heroicon-o-document-text',
            default => 'heroicon-o-folder',
        };
    }

    public function getCategoryTitle(string $category): string
    {
        return match($category) {
            'filtros' => '🔍 Filtros',
            'api' => '⚡ API',
            'database' => '💾 Database',
            'deployment' => '🚀 Deployment',
            'outros' => '📄 Outros',
            default => ucfirst($category),
        };
    }
}
