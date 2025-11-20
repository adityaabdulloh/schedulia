<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MahasiswaResource\Pages;
use App\Filament\Resources\MahasiswaResource\RelationManagers;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Manajemen Mahasiswa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nim')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('foto_profil')
                    ->image()
                    ->directory('mahasiswa-photos')
                    ->nullable(),
                Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('kelas_id')
                    ->label('Kelas')
                    ->options(Kelas::all()->pluck('nama_kelas', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('prodi_id')
                    ->label('Prodi')
                    ->options(Prodi::all()->pluck('nama_prodi', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('semester')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(14),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nim')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('foto_profil')
                    ->circular(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('prodi.nama_prodi')
                    ->label('Prodi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('semester')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('prodi_id')
                    ->label('Prodi')
                    ->options(Prodi::all()->pluck('nama_prodi', 'id'))
                    ->searchable()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin' || auth()->user()->role === 'dosen';
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMahasiswas::route('/'),
            'create' => Pages\CreateMahasiswa::route('/create'),
            'edit' => Pages\EditMahasiswa::route('/{record}/edit'),
        ];
    }
}
