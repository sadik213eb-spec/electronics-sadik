<?php

namespace App\Filament\Resources\CheckoutOffers\Pages;

use App\Filament\Resources\CheckoutOffers\CheckoutOfferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCheckoutOffers extends ListRecords
{
    protected static string $resource = CheckoutOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
