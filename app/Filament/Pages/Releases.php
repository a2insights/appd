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

class Releases extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static string $view = 'filament.pages.releases';

    protected static ?string $navigationLabel = 'Releases';

    protected static ?string $title = 'Notas de Versão';

    protected static ?string $slug = 'releases';

    protected static bool $shouldRegisterNavigation = false;

    public string $activeFile = '';

    public function mount(): void
    {
        $this->activeFile = request()->query('file', '');
        
        if (empty($this->activeFile)) {
            $files = $this->getReleaseFiles();
            if (!empty($files)) {
                $this->activeFile = array_key_first($files);
            }
        }
    }

    public function changeFile(string $file): void
    {
        $this->activeFile = $file;
    }

    public function getReleaseFiles(): array
    {
        $releasesPath = base_path('docs/releases');
        
        if (!File::exists($releasesPath)) {
            File::makeDirectory($releasesPath, 0755, true);
            return [];
        }

        $files = File::files($releasesPath);
        $releases = [];

        foreach ($files as $file) {
            if ($file->getExtension() !== 'md') {
                continue;
            }

            $basename = $file->getBasename('.md');
            $releases[$basename] = $this->formatTitle($basename);
        }

        // Ordenar por nome (versões mais recentes primeiro)
        krsort($releases);

        return $releases;
    }

    protected function formatTitle(string $filename): string
    {
        // Converter nome do arquivo em título legível
        // Ex: v2.0-sistema-documentacao -> v2.0 - Sistema Documentação
        $title = str_replace(['-', '_'], ' ', $filename);
        return ucwords($title);
    }

    public function getFileContent(string $filename): string
    {
        $filePath = base_path("docs/releases/{$filename}.md");
        
        if (!File::exists($filePath)) {
            return '<div class="text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">Release não encontrada.</p>
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

    public function getFileDate(string $filename): ?string
    {
        $filePath = base_path("docs/releases/{$filename}.md");
        
        if (!File::exists($filePath)) {
            return null;
        }

        $markdown = File::get($filePath);
        $pattern = '/> \*\*Data de Lançamento:\*\* (\d{2}\/\d{2}\/\d{4})/';
        preg_match($pattern, $markdown, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }

        return date('d/m/Y', File::lastModified($filePath));
    }
}
