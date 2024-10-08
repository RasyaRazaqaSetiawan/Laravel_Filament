<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([

                        // Image upload with validation
                        Forms\Components\FileUpload::make('image')
                            ->label('Image')
                            ->required()
                            ->image() // Ensure only images can be uploaded
                            ->maxSize(5000) // Limit the file size to 5MB
                            ->directory('uploads/posts'), // Optional: specify a directory for storing images

                        Forms\Components\Grid::make(2)
                            ->schema([

                                // Title input with validation
                                Forms\Components\TextInput::make('title')
                                    ->label('Title')
                                    ->placeholder('Title')
                                    ->required()
                                    ->maxLength(255), // Set max length

                                // Category select
                                Forms\Components\Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->required(),
                            ]),

                        // Rich text editor for content
                        Forms\Components\RichEditor::make('content')
                            ->label('Content')
                            ->placeholder('Content')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Display circular image
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular(),  // Buat gambar berbentuk lingkaran

                // Title column with search functionality
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                // Category name
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
            ])
            ->filters([
                // Add filters if necessary
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // Menambahkan tombol delete di tabel
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }




    public static function getRelations(): array
    {
        return [
            // Add relation managers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
