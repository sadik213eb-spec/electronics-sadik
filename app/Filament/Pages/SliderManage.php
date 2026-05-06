<?php

namespace App\Filament\Pages;

use App\Models\Media;
use App\Models\Slider;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class SliderManage extends Page
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Slider Manage';

    protected static string|UnitEnum|null $navigationGroup = 'Web Manage';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.slider-manage';

    // Banner fields
    public ?string $top_image = null;

    public ?string $top_link = null;

    public ?string $bottom_image = null;

    public ?string $bottom_link = null;

    // Edit fields
    public ?int $editing_id = null;

    public ?string $edit_name = null;

    public ?string $edit_link = null;

    public ?string $edit_image = null;

    public ?int $edit_order = null;

    public function mount(): void
    {
        $top = Slider::where('type', 'banner')->where('order', 1)->first();
        $bottom = Slider::where('type', 'banner')->where('order', 2)->first();

        $this->top_image = $top?->image;
        $this->top_link = $top?->link;
        $this->bottom_image = $bottom?->image;
        $this->bottom_link = $bottom?->link;
    }

    public function updateBanners(): void
    {
        Slider::updateOrCreate(
            ['type' => 'banner', 'order' => 1],
            ['image' => $this->top_image, 'link' => $this->top_link, 'is_active' => true]
        );

        Slider::updateOrCreate(
            ['type' => 'banner', 'order' => 2],
            ['image' => $this->bottom_image, 'link' => $this->bottom_link, 'is_active' => true]
        );

        Notification::make()->title('Banners updated!')->success()->send();
    }

    public function getSliderItems()
    {
        return Slider::where('type', 'slider')->orderBy('order')->get();
    }

    public function toggleSlider(int $id): void
    {
        $slider = Slider::findOrFail($id);
        $slider->update(['is_active' => ! $slider->is_active]);
    }

    public function deleteSlider(int $id): void
    {
        Slider::findOrFail($id)->delete();
        Notification::make()->title('Deleted!')->success()->send();
    }

    public function openEdit(int $id): void
    {
        $slider = Slider::findOrFail($id);
        $this->editing_id = $slider->id;
        $this->edit_name = $slider->name;
        $this->edit_link = $slider->link;
        $this->edit_image = $slider->image;
        $this->edit_order = $slider->order;
        $this->dispatch('open-edit-modal');
    }

    public function saveEdit(): void
    {
        Slider::findOrFail($this->editing_id)->update([
            'name' => $this->edit_name,
            'link' => $this->edit_link,
            'image' => $this->edit_image,
            'order' => $this->edit_order,
        ]);

        $this->editing_id = null;
        $this->dispatch('close-edit-modal');
        Notification::make()->title('Slider updated!')->success()->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('add_slider')
                ->label('Add New Slider')
                ->color('warning')
                ->form([
                    TextInput::make('name')
                        ->label('Name')
                        ->nullable(),

                    TextInput::make('order')
                        ->label('Priority')
                        ->numeric()
                        ->default(0),

                    TextInput::make('link')
                        ->label('Link URL')
                        ->nullable(),

                    Select::make('image')
                        ->label('Select Image from Media')
                        ->options(
                            Media::all()->mapWithKeys(fn ($media) => [$media->path => $media->name])
                        )
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    Slider::create([
                        'name' => $data['name'] ?? null,
                        'image' => $data['image'],
                        'link' => $data['link'] ?? null,
                        'order' => $data['order'] ?? 0,
                        'type' => 'slider',
                        'is_active' => true,
                    ]);

                    Notification::make()->title('Slider added!')->success()->send();
                }),
        ];
    }
}
