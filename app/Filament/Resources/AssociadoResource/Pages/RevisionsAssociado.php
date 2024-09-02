<?php

namespace App\Filament\Resources\AssociadoResource\Pages;

use App\Filament\Resources\AssociadoResource;
use Filament\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Mansoor\FilamentVersionable\RevisionsPage;

class RevisionsAssociado extends RevisionsPage
{
    protected static string $resource = AssociadoResource::class;

    public function getBreadcrumb(): string
    {
        return static::$breadcrumb ?? 'Alterações';
    }

    public function getContentTabLabel(): ?string
    {
        return 'Alterações';
    }

    public function restoreVersionAction(): Action
    {
        return Action::make('restoreVersion')
            ->label('Restaurar dados')
            ->disabled()
            ->color('gray')
            ->requiresConfirmation()
            ->modalDescription(__('filament-versionable::actions.restore.modal_description'))
            ->modalSubmitActionLabel(__('filament-versionable::actions.restore.modal_submit_action_label'))
            ->action(fn () => $this->restoreVersion());
    }

    public function previousVersionAction(): Action
    {
        return Action::make('previousVersion')
            ->label('Anterior')
            ->disabled(fn () => $this->version->previousVersion()->is($this->record->firstVersion))
            ->action(fn () => $this->previousVersion());
    }

    public function nextVersionAction(): Action
    {
        return Action::make('nextVersion')
            ->label('Próximo')
            /** @phpstan-ignore-next-line */
            ->disabled(fn () => $this->version->is($this->record->lastVersion))
            ->action(fn () => $this->nextVersion());
    }

    public function getTitle(): string|Htmlable
    {
        if (filled(static::$title)) {
            return static::$title;
        }

        $url = static::$resource::getUrl('edit', ['record' => $this->getRecord()]);

        return new HtmlString(__('Alteraçoes no associado :title', [
            'title' => "<a href=\"{$url}\" class=\"text-primary-500\">
                {$this->getRecordTitle()}
            </a>",
        ]));
    }
}
