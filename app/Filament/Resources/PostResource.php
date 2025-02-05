<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;
use Filament\Tables\Columns\CheckboxColumn;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->rules('min:3|max:100')->required(),
                TextInput::make('slug')->rules(['min:3','max:100'])->required(),
                // TextInput::make('slug')->numeric()->rules(['min:3','max:10'])->required(),

                Select::make('category_id')
                    ->label('Category Name')
                    ->options(Category::all()->pluck('name','id')),

                FileUpload::make('thumbnail')->disk('public')->directory('thumbnails'),

                ColorPicker::make('color')->required(),
                MarkdownEditor::make('content')->nullable()->columnSpan('full'),
                TagsInput::make('tags')->nullable(),
                Checkbox::make('published')->required(),

            ])->columns(
                [
                    'default' => 1,
                    'md' => 2,
                    'lg' => 3,
                    'xl' => 4,
                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()->toggleable(),
                TextColumn::make('slug')->toggleable(),
                TextColumn::make('category.name')->label('Category')->toggleable(),
                ColorColumn::make('color')->toggleable(),
                ImageColumn::make('thumbnail')->toggleable(),
                TextColumn::make('tags')->toggleable(),
                CheckboxColumn::make('published')->toggleable(),
                TextColumn::make('created_at')
                        ->label('Published On')
                        ->date()
                        ->sortable()
                        ->searchable()
                        ->toggleable(),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
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
